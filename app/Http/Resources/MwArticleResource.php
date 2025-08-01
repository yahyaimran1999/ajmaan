<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MwArticleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'article_id' => $this->article_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'status' => $this->status,
            'page_title' => $this->page_title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'f_type' => $this->f_type,
            // 'main_image' => $this->main_image,
            'main_image' => $this->getProcessedImage(),
            'can_d' => $this->can_d,
            'featured' => $this->featured,
            'channel' => $this->channel,
            'y_u' => $this->y_u,
            'date_added' => Carbon::parse($this->date_added)->format('Y-m-d'),
            'last_updated' => Carbon::parse($this->last_updated)->format('Y-m-d'),
            'article_categories' => MwArticleToCategoryResource::collection($this->whenLoaded('mw_article_to_categories')),
            'article_view' => new MwArticleViewResource($this->whenLoaded('mw_article_view')),
            'blog_comments' => MwBlogCommentResource::collection($this->whenLoaded('mw_blog_comments')),
            'translate_relations' => MwTranslateRelationResource::collection($this->whenLoaded('mw_translate_relations')),
        ];
    }

    protected function getProcessedImage(): ?string
    {
        $images = [];
        
        if (!empty($this->main_image)) {
            return env('SHOW_IMAGE_URL') . 'uploads/banner/' . $this->main_image;
        } else {
            preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $this->content, $images);
           if (!empty($images[1]) && (str_starts_with($images[1], '/frontend/assets') || str_starts_with($images[1], 'https://images.khaleejtimes.com/'))) {
                return env('SHOW_IMAGE_URL') . 'uploads/banner/' . $this->getJpgImages()[array_rand($this->getJpgImages())];
            }
            return $images[1] ?? null;
        }
    }

    /**
     * Get array of JPG image filenames
     * 
     * @return array
     */
    protected function getJpgImages(): array
    {
        return [
            '75220220218082437.jpg',
            '75420211214100452.jpg',
            '75420220225080710.jpg',
            '75420230425115839.jpg',
            '75420240122062601.jpg',
            '75420240523102859.jpg',
            '75920230103115334.jpg',
            '75920250428053459.jpg',
            '759Al-Rashidiya-Towers-21012020.jpg',
            '76120240228111210.jpg'
        ];
    }

}
