<?php

namespace App\DTOs;

class ArticleDto
{
    public function __construct(
        public string $title,
        public ?string $author,
        public string $description,
        public ?string $content,
        public string $url,
        public ?string $imageUrl,
        public string $source,
        public string $category,
        public string $publishedAt
    ) {}

    /**
     * Map the DTO to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'content' => $this->content,
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'source' => $this->source,
            'category' => ucfirst($this->category),
            'published_at' => $this->publishedAt,
        ];
    }
}
