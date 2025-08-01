<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MwAmenitiesCategoryGroupResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        // Define category mappings
        $categoryMappings = [
            'recreation_and_family' => [130, 134, 135, 138, 144, 150, 241, 247, 270, 293, 304, 308],
            'health_and_fitness' => [143, 148, 156, 164, 165, 239, 312, 313, 314],
            'laundry_and_kitchen' => [132, 196, 203, 204, 209, 217, 230, 290],
            'building' => [131, 225, 229, 243, 244, 257, 265, 274, 286, 291, 292, 305, 311, 315, 316, 317, 240],
            'business_and_security' => [170, 173, 182, 185, 186, 188, 189, 251, 254, 291, 294, 295, 303],
            'clean_and_maintenance' => [145, 169, 296, 300, 301, 302, 306],
            'features' => [287, 288, 289, 297, 307, 310, 297, 299],
        ];

        // Initialize result array with empty categories
        $result = [];
        foreach (array_keys($categoryMappings) as $category) {
            $result[$category] = [];
        }
        $result['others'] = [];

        // Group amenities by category
        foreach ($this->collection as $amenity) {
            // Safety check to ensure we have a proper model instance
            if (!is_object($amenity) || !isset($amenity->amenities_id)) {
                continue;
            }

            $amenityData = [
                'id' => $amenity->amenities_id,
                'name' => $amenity->mw_amenity ? $amenity->mw_amenity->amenities_name : 'Unknown',
            ];

            $categorized = false;
            foreach ($categoryMappings as $categoryName => $ids) {
                if (in_array($amenity->amenities_id, $ids)) {
                    $result[$categoryName][] = $amenityData;
                    $categorized = true;
                    break;
                }
            }

            // If not categorized, add to others
            if (!$categorized) {
                $result['others'][] = $amenityData;
            }
        }

        // Remove empty categories
        $result = array_filter($result, function($category) {
            return !empty($category);
        });

        return $result;
    }
}
