<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class TagRepository
{
    protected $Tag;

    public function __construct(Tag $Tag)
    {
        $this->Tag = $Tag;
    }

    public function getAllTags():Collection
    {
        return Tag::all();
    }

    public function find(int $tagId): ?Tag
    {
        return Tag::findORFail($tagId);
    }

    public function create($data): ?Tag
    {
        try {
            return $this->Tag->create([
                'name' => $data['name'],
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function update($tagId, array $data):Tag|null
    {
        $tag = Tag::findOrFail($tagId);
        $tag->update($data);
        return $tag;
    }

    public function delete($tagId)
    {
        $tag = Tag::find($tagId);
        if ($tag) {
            $tag->delete();
        }
    }

}
