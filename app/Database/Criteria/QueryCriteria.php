<?php

namespace App\Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * Laravel Query Criteria Builder
 * 
 * A fluent query builder inspired by Yii's CDbCriteria
 * that works with Laravel's Eloquent ORM and Query Builder
 */
class QueryCriteria
{
    protected array $select = ['*'];
    protected bool $distinct = false;
    protected array $where = [];
    protected array $bindings = [];
    protected ?int $limit = null;
    protected ?int $offset = null;
    protected array $orderBy = [];
    protected array $groupBy = [];
    protected array $having = [];
    protected array $joins = [];
    protected array $with = [];
    protected ?string $alias = null;
    protected array $scopes = [];

    /**
     * Constructor
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Set SELECT columns
     */
    public function select($columns = ['*']): self
    {
        $this->select = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    /**
     * Add SELECT columns
     */
    public function addSelect($columns): self
    {
        $columns = is_array($columns) ? $columns : func_get_args();
        $this->select = array_merge($this->select, $columns);
        return $this;
    }

    /**
     * Set DISTINCT
     */
    public function distinct(bool $distinct = true): self
    {
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * Add WHERE condition
     */
    public function where($column, $operator = null, $value = null, string $boolean = 'and'): self
    {
        if (is_callable($column)) {
            $this->where[] = ['type' => 'nested', 'query' => $column, 'boolean' => $boolean];
        } else {
            $this->where[] = [
                'type' => 'basic',
                'column' => $column,
                'operator' => $operator,
                'value' => $value,
                'boolean' => $boolean
            ];
        }
        return $this;
    }

    /**
     * Add OR WHERE condition
     */
    public function orWhere($column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value, 'or');
    }

    /**
     * Add WHERE IN condition
     */
    public function whereIn(string $column, array $values, string $boolean = 'and'): self
    {
        if (empty($values)) {
            return $this->whereRaw('0 = 1', [], $boolean);
        }

        $this->where[] = [
            'type' => 'in',
            'column' => $column,
            'values' => $values,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add WHERE NOT IN condition
     */
    public function whereNotIn(string $column, array $values, string $boolean = 'and'): self
    {
        if (empty($values)) {
            return $this;
        }

        $this->where[] = [
            'type' => 'not_in',
            'column' => $column,
            'values' => $values,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add WHERE NULL condition
     */
    public function whereNull(string $column, string $boolean = 'and'): self
    {
        $this->where[] = [
            'type' => 'null',
            'column' => $column,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add WHERE NOT NULL condition
     */
    public function whereNotNull(string $column, string $boolean = 'and'): self
    {
        $this->where[] = [
            'type' => 'not_null',
            'column' => $column,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add WHERE BETWEEN condition
     */
    public function whereBetween(string $column, array $values, string $boolean = 'and'): self
    {
        $this->where[] = [
            'type' => 'between',
            'column' => $column,
            'values' => $values,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Add WHERE LIKE condition for search
     */
    public function whereLike(string $column, string $value, bool $escape = true, string $boolean = 'and'): self
    {
        if ($escape) {
            $value = '%' . str_replace(['%', '_', '\\'], ['\\%', '\\_', '\\\\'], $value) . '%';
        }

        return $this->where($column, 'LIKE', $value, $boolean);
    }

    /**
     * Add raw WHERE condition
     */
    public function whereRaw(string $sql, array $bindings = [], string $boolean = 'and'): self
    {
        $this->where[] = [
            'type' => 'raw',
            'sql' => $sql,
            'bindings' => $bindings,
            'boolean' => $boolean
        ];
        return $this;
    }

    /**
     * Smart comparison method (similar to Yii's compare)
     */
    public function compare(string $column, $value, bool $partialMatch = false, string $boolean = 'and'): self
    {
        if (is_array($value)) {
            return empty($value) ? $this : $this->whereIn($column, $value, $boolean);
        }

        $value = (string) $value;
        if ($value === '') {
            return $this;
        }

        // Parse operator from value
        if (preg_match('/^(?:\s*(<>|<=|>=|<|>|=|!=))?(.*)$/', $value, $matches)) {
            $actualValue = trim($matches[2]);
            $operator = $matches[1] ?: '=';
        } else {
            $actualValue = $value;
            $operator = '=';
        }

        if ($actualValue === '') {
            return $this;
        }

        if ($partialMatch) {
            if ($operator === '' || $operator === '=') {
                return $this->whereLike($column, $actualValue, true, $boolean);
            }
            if ($operator === '<>' || $operator === '!=') {
                return $this->where($column, 'NOT LIKE', '%' . $actualValue . '%', $boolean);
            }
        }

        return $this->where($column, $operator, $actualValue, $boolean);
    }

    /**
     * Set LIMIT
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit > 0 ? $limit : null;
        return $this;
    }

    /**
     * Set OFFSET
     */
    public function offset(int $offset): self
    {
        $this->offset = $offset >= 0 ? $offset : null;
        return $this;
    }

    /**
     * Add ORDER BY
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy[] = ['column' => $column, 'direction' => strtolower($direction)];
        return $this;
    }

    /**
     * Add GROUP BY
     */
    public function groupBy($columns): self
    {
        $columns = is_array($columns) ? $columns : func_get_args();
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }

    /**
     * Add HAVING condition
     */
    public function having(string $column, string $operator, $value): self
    {
        $this->having[] = ['column' => $column, 'operator' => $operator, 'value' => $value];
        return $this;
    }

    /**
     * Add JOIN
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'inner'): self
    {
        $this->joins[] = [
            'type' => $type,
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second
        ];
        return $this;
    }

    /**
     * Add LEFT JOIN
     */
    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'left');
    }

    /**
     * Add RIGHT JOIN
     */
    public function rightJoin(string $table, string $first, string $operator, string $second): self
    {
        return $this->join($table, $first, $operator, $second, 'right');
    }

    /**
     * Set relationships to eager load (for Eloquent)
     */
    public function with($relations): self
    {
        $this->with = is_array($relations) ? $relations : func_get_args();
        return $this;
    }

    /**
     * Add relationship to eager load
     */
    public function addWith($relations): self
    {
        $relations = is_array($relations) ? $relations : func_get_args();
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    /**
     * Set table alias
     */
    public function alias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Merge with another criteria
     */
    public function mergeWith(QueryCriteria $criteria, string $operator = 'and'): self
    {
        // Merge SELECT
        if ($this->select === ['*']) {
            $this->select = $criteria->select;
        } elseif ($criteria->select !== ['*']) {
            $this->select = array_unique(array_merge($this->select, $criteria->select));
        }

        // Merge WHERE conditions
        if (!empty($criteria->where)) {
            if (empty($this->where)) {
                $this->where = $criteria->where;
            } else {
                $this->where[] = [
                    'type' => 'nested',
                    'query' => $criteria->where,
                    'boolean' => $operator
                ];
            }
        }

        // Merge other properties
        $this->distinct = $criteria->distinct ?: $this->distinct;
        $this->limit = $criteria->limit ?: $this->limit;
        $this->offset = $criteria->offset ?: $this->offset;
        $this->orderBy = array_merge($this->orderBy, $criteria->orderBy);
        $this->groupBy = array_merge($this->groupBy, $criteria->groupBy);
        $this->having = array_merge($this->having, $criteria->having);
        $this->joins = array_merge($this->joins, $criteria->joins);
        $this->with = array_merge($this->with, $criteria->with);
        $this->alias = $criteria->alias ?: $this->alias;

        return $this;
    }

    /**
     * Apply criteria to Laravel Query Builder
     */
    public function applyToQuery(QueryBuilder $query): QueryBuilder
    {
        // Apply SELECT
        if ($this->select !== ['*']) {
            $query->select($this->select);
        }

        // Apply DISTINCT
        if ($this->distinct) {
            $query->distinct();
        }

        // Apply WHERE conditions
        foreach ($this->where as $condition) {
            $this->applyWhereCondition($query, $condition);
        }

        // Apply JOINs
        foreach ($this->joins as $join) {
            $query->join($join['table'], $join['first'], $join['operator'], $join['second'], $join['type']);
        }

        // Apply GROUP BY
        if (!empty($this->groupBy)) {
            $query->groupBy($this->groupBy);
        }

        // Apply HAVING
        foreach ($this->having as $having) {
            $query->having($having['column'], $having['operator'], $having['value']);
        }

        // Apply ORDER BY
        foreach ($this->orderBy as $order) {
            $query->orderBy($order['column'], $order['direction']);
        }

        // Apply LIMIT and OFFSET
        if ($this->limit !== null) {
            $query->limit($this->limit);
        }
        if ($this->offset !== null) {
            $query->offset($this->offset);
        }

        return $query;
    }

    /**
     * Apply criteria to Eloquent Builder
     */
    public function applyToEloquent(Builder $builder): Builder
    {
        $this->applyToQuery($builder->getQuery());

        // Apply eager loading
        if (!empty($this->with)) {
            $builder->with($this->with);
        }

        return $builder;
    }

    /**
     * Apply individual WHERE condition
     */
    protected function applyWhereCondition(QueryBuilder $query, array $condition): void
    {
        $method = $condition['boolean'] === 'or' ? 'orWhere' : 'where';

        switch ($condition['type']) {
            case 'basic':
                $query->$method($condition['column'], $condition['operator'], $condition['value']);
                break;
            case 'in':
                $method = $condition['boolean'] === 'or' ? 'orWhereIn' : 'whereIn';
                $query->$method($condition['column'], $condition['values']);
                break;
            case 'not_in':
                $method = $condition['boolean'] === 'or' ? 'orWhereNotIn' : 'whereNotIn';
                $query->$method($condition['column'], $condition['values']);
                break;
            case 'null':
                $method = $condition['boolean'] === 'or' ? 'orWhereNull' : 'whereNull';
                $query->$method($condition['column']);
                break;
            case 'not_null':
                $method = $condition['boolean'] === 'or' ? 'orWhereNotNull' : 'whereNotNull';
                $query->$method($condition['column']);
                break;
            case 'between':
                $method = $condition['boolean'] === 'or' ? 'orWhereBetween' : 'whereBetween';
                $query->$method($condition['column'], $condition['values']);
                break;
            case 'raw':
                $method = $condition['boolean'] === 'or' ? 'orWhereRaw' : 'whereRaw';
                $query->$method($condition['sql'], $condition['bindings']);
                break;
            case 'nested':
                $method = $condition['boolean'] === 'or' ? 'orWhere' : 'where';
                $query->$method(function ($subQuery) use ($condition) {
                    foreach ($condition['query'] as $subCondition) {
                        $this->applyWhereCondition($subQuery, $subCondition);
                    }
                });
                break;
        }
    }

    /**
     * Convert to array representation
     */
    public function toArray(): array
    {
        return [
            'select' => $this->select,
            'distinct' => $this->distinct,
            'where' => $this->where,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'orderBy' => $this->orderBy,
            'groupBy' => $this->groupBy,
            'having' => $this->having,
            'joins' => $this->joins,
            'with' => $this->with,
            'alias' => $this->alias,
            'scopes' => $this->scopes,
        ];
    }

    /**
     * Create a new instance from array
     */
    public static function fromArray(array $data): self
    {
        return new static($data);
    }
}
