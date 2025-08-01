<?php

// Usage Examples for Laravel QueryCriteria

use App\Database\Criteria\QueryCriteria;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Example 1: Basic usage with Eloquent Model
function getActivePostsExample()
{
    $criteria = new QueryCriteria();
    $criteria->where('status', '=', 'active')
             ->where('published_at', '<=', now())
             ->orderBy('created_at', 'desc')
             ->limit(10)
             ->with(['author', 'tags']);

    $posts = $criteria->applyToEloquent(Post::query())->get();
    return $posts;
}

// Example 2: Using the compare method (similar to Yii's compare)
function searchPostsExample($searchParams)
{
    $criteria = new QueryCriteria();
    
    // Smart comparison - handles operators in values
    if (!empty($searchParams['title'])) {
        $criteria->compare('title', $searchParams['title'], true); // partial match
    }
    
    if (!empty($searchParams['status'])) {
        $criteria->compare('status', $searchParams['status']); // exact match
    }
    
    if (!empty($searchParams['view_count'])) {
        $criteria->compare('view_count', $searchParams['view_count']); // supports >, <, >=, <=, <>
    }
    
    if (!empty($searchParams['category_ids'])) {
        $criteria->whereIn('category_id', $searchParams['category_ids']);
    }

    return $criteria->applyToEloquent(Post::query())->paginate(15);
}

// Example 3: Complex query with joins
function getPostsWithAuthorInfoExample()
{
    $criteria = new QueryCriteria();
    $criteria->select(['posts.*', 'users.name as author_name', 'users.email as author_email'])
             ->leftJoin('users', 'posts.user_id', '=', 'users.id')
             ->where('posts.status', '=', 'published')
             ->whereNotNull('users.email_verified_at')
             ->orderBy('posts.created_at', 'desc')
             ->groupBy(['posts.id']);

    return $criteria->applyToQuery(DB::table('posts'))->get();
}

// Example 4: Merging criteria (useful for building complex filters)
function buildComplexCriteriaExample()
{
    // Base criteria
    $baseCriteria = new QueryCriteria();
    $baseCriteria->where('status', '=', 'active')
                 ->orderBy('created_at', 'desc');

    // Additional criteria for premium users
    $premiumCriteria = new QueryCriteria();
    $premiumCriteria->where('is_premium', '=', true)
                    ->orWhere('trial_ends_at', '>', now());

    // Merge criteria
    $baseCriteria->mergeWith($premiumCriteria, 'and');

    return $baseCriteria->applyToEloquent(User::query())->get();
}

// Example 5: Using with Query Builder directly
function getRawQueryExample()
{
    $criteria = new QueryCriteria();
    $criteria->select(['id', 'title', 'created_at'])
             ->where('status', '=', 'published')
             ->whereBetween('created_at', [now()->subDays(7), now()])
             ->orderBy('view_count', 'desc')
             ->limit(5);

    return $criteria->applyToQuery(DB::table('posts'))->get();
}

// Example 6: Advanced search with multiple conditions
function advancedSearchExample($filters)
{
    $criteria = new QueryCriteria();
    
    // Text search
    if (!empty($filters['search'])) {
        $criteria->where(function($criteria) use ($filters) {
            $criteria->whereLike('title', $filters['search'])
                     ->orWhere('content', 'LIKE', '%' . $filters['search'] . '%');
        });
    }
    
    // Date range
    if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
        $criteria->whereBetween('created_at', [$filters['date_from'], $filters['date_to']]);
    }
    
    // Category filter
    if (!empty($filters['categories'])) {
        $criteria->whereIn('category_id', $filters['categories']);
    }
    
    // Status filter
    if (!empty($filters['status'])) {
        $criteria->where('status', '=', $filters['status']);
    } else {
        // Default to published posts
        $criteria->where('status', '=', 'published');
    }
    
    // Sorting
    $sortBy = $filters['sort_by'] ?? 'created_at';
    $sortOrder = $filters['sort_order'] ?? 'desc';
    $criteria->orderBy($sortBy, $sortOrder);

    return $criteria->applyToEloquent(Post::with(['author', 'category']))->paginate(20);
}

// Example 7: Reusable criteria for common queries
class PostCriteria
{
    public static function published(): QueryCriteria
    {
        return (new QueryCriteria())
            ->where('status', '=', 'published')
            ->where('published_at', '<=', now());
    }
    
    public static function recent($days = 7): QueryCriteria
    {
        return (new QueryCriteria())
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc');
    }
    
    public static function popular($minViews = 1000): QueryCriteria
    {
        return (new QueryCriteria())
            ->where('view_count', '>=', $minViews)
            ->orderBy('view_count', 'desc');
    }
    
    public static function byAuthor($authorId): QueryCriteria
    {
        return (new QueryCriteria())
            ->where('user_id', '=', $authorId);
    }
}

// Usage of reusable criteria
function getPopularRecentPostsExample()
{
    $criteria = PostCriteria::published();
    $criteria->mergeWith(PostCriteria::recent(30))
             ->mergeWith(PostCriteria::popular(500))
             ->limit(10)
             ->with(['author', 'tags']);

    return $criteria->applyToEloquent(Post::query())->get();
}

// Example 8: Converting criteria to array for caching or API responses
function getCriteriaConfigExample()
{
    $criteria = new QueryCriteria();
    $criteria->where('status', '=', 'active')
             ->orderBy('created_at', 'desc')
             ->limit(10);
    
    // Convert to array
    $criteriaArray = $criteria->toArray();
    
    // Store in cache or send as API response
    cache()->put('search_criteria', $criteriaArray, 3600);
    
    // Later, recreate from array
    $restoredCriteria = QueryCriteria::fromArray($criteriaArray);
    
    return $restoredCriteria->applyToEloquent(Post::query())->get();
}