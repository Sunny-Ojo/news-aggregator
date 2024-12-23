<?php

namespace App\Actions;

use App\DTOs\ArticleDto;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class CreateArticleAction
{
    public function handle(ArticleDto $articleDto): void
    {
        $data = $articleDto->toArray();
        DB::transaction(function () use ($data) {
            Article::updateOrCreate(['url' => $data['url']], $data);
        });
    }
}
