<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticleTagSyncRequest;
use App\Http\Requests\ArticleTagsAttachRequest;
use App\Http\Requests\ArticleTagsDetachRequest;

class TagController extends Controller
{
    use ResponseTrait;
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this ->tagService = $tagService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $tags = $this->tagService->getAllTags();
            return $this->responseSuccess($tags, 'Tags fetched successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $tag = $this->tagService->getTag($id);
            return $this->responseSuccess($tag, 'Tags fetched successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:tags,name',
            ]);
            $tag = $this->tagService->createTag($data);
            return $this->responseSuccess($tag, 'tag created successfully!');
        } catch (\Exception $e) {
            return $this->responseError(true, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->tagService->updateTags($id, $request->all());
            return $this->responseSuccess($data, 'Tag updated successfully!');
        }catch (\Exception $e){
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $this->tagService->deleteTag($id);
            return $this->responseSuccess(null, 'Tag deleted successfully!');
        }catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 'deleted failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function attachTags(Article $article, ArticleTagsAttachRequest $request)
    {
        $data= $request->validated();
        $article ->tags()->attach($data['ids']);
        return new ArticleResource($article);
    }

    //detachTags
    public function detachTags(Article $article, ArticleTagsDetachRequest $request)
    {
        $data= $request->validated();
        $article ->tags()->detach($data['ids']);
        return new ArticleResource($article);
    }
    //syncTags
    public function syncTags(Article $article, ArticleTagSyncRequest $request)
    {
        $data= $request->validated();
        $article ->tags()->sync($data['ids']);
        return new ArticleResource($article);
    }
}
