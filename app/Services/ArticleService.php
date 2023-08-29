<?php

namespace App\Services;

use Exception;
use App\Models\Article;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Controllers\ArticleController;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Auth\Access\AuthorizationException;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticlePaginate($perPage): Paginator
    {
        $result = $this->articleRepository->getPaginate($perPage);
        return $result;
    }

    public function getArticle($articleId): Article
    {
        $result = $this->articleRepository->find($articleId);
        return $result;
    }

    public function saveArticle(array $data): Article
    {
        $data['user_id'] = Auth::id();
        $result = $this -> articleRepository -> save($data);
        return $result;

    }

    public function updateArticle($articleId, array $data): Article
    {
        $article = $this->articleRepository->find($articleId);
        if (!$article || $article->user_id != Auth::id()){
            throw new \Exception("You don't have permission to delete this article.");
        }
        $result = $this->articleRepository->update($articleId, $data);
        return $result;
    }

    public function deleteArticle($articleId): void
    {
        $article = $this->articleRepository->find($articleId);
        if (!$article || $article->user_id != Auth::id()){
            throw new \Exception("You don't have permission to delete this article.");
        }
        $this->articleRepository->delete($articleId);
    }

    public function attachTags(Article $article, array $tags): void
    {
        $article->tags()->attach($tags);
    }

    public function detachTags(Article $article, array $tags): void
    {
        $article->tags()->detach($tags);
    }

    public function syncTags(Article $article, array $tags): void
    {
        $article->tags()->sync($tags);
    }

    public function exportArticles(): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'title');
        $sheet->setCellValue('C1', 'body');
        $sheet->setCellValue('D1', 'tags');
        $articles = $this->articleRepository->getAllArticles();
        $row = 2;
        foreach ($articles as $article){
            $sheet->setCellValue('A'.$row, $article->id);
            $sheet->setCellValue('B'.$row, $article->title);
            $sheet->setCellValue('C'.$row, $article->body);
            $sheet->setCellValue('D'.$row, $article->tags->pluck('id')->join(','));
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('articles.xlsx');
        return 'articles.xlsx';
    }
}
