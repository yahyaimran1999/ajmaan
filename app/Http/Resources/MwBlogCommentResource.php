<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MwBlogCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'blog_id' => $this->blog_id,
            'user_id' => $this->user_id,
            'comment' => $this->comment,
            'status' => $this->status,
            'date_added' => Carbon::parse($this->date_added)->format('Y-m-d'),
        ];
    }
}
