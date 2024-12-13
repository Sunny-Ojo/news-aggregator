<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'authors',
        'content',
        'url',
        'image_url',
        'source',
        'category',
        'description',
        'published_at'
    ];
   
}
