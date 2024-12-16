<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    public function __construct(public readonly ArticleService $articleService) { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $articles = $this->articleService->getAll($request);
            return $this->successResponse(ArticleResource::collection($articles));
        } catch (\Throwable $th) {
            Log::error('Article fetch error: ' . $th->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');
        }
    }

    public function personalizedFeed(Request $request)
    {
       try {
        $articles = $this->articleService->getPersonalizedFeed($request);
        return $this->successResponse(ArticleResource::collection($articles));
       } catch (\Throwable $th) {
        Log::error('personalised articless fetch error: ' . $th->getMessage());
        return $this->errorResponse('Something went wrong. Please try again later.');
       }
    }

    public function categories()
    {
        try {
            return $this->successResponse($this->articleService->getCategories());
           } catch (\Throwable $th) {
            Log::error('error getting article categories: ' . $th->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');

           }
    }

    public function authors()
    {
        try {
            return $this->successResponse($this->articleService->getAuthors());
           } catch (\Throwable $th) {
            Log::error('error getting article authors: ' . $th->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');

           }
    }

    public function sources()
    {
        try {
            return $this->successResponse($this->articleService->getSources());
           } catch (\Throwable $th) {
            Log::error('error getting article sources: ' . $th->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.');

           }
    }

}
