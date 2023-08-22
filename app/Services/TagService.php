<?php

namespace App\Services;

use Exception;
use App\Models\Tag;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TagRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ArticleController;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Auth\Access\AuthorizationException;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function createTag($data): ?Tag
    {
        return $this->tagRepository->create($data);
    }

    public function getAllTags()
    {
        return $this->tagRepository->getAllTags();
    }

    public function getTag(int $tagId): ?Tag
    {
        return $this->tagRepository->find($tagId);
    }

    public function updateTags($id, array $data): ?Tag
    {
        $tag = $this->tagRepository->find($id);
        if (!$tag){
            throw new \Exception("Can't find this tag!");
        }
        $result = $this->tagRepository->update($id, $data);
        return $result;
    }

    public function deleteTag($tagId)
    {
        $tag = $this->tagRepository->find($tagId);
        if (!$tag){
            throw new \Exception("Can't find this tag.");
        }
        $this->tagRepository->delete($tagId);
    }

}
