<?php

namespace App\Http\Controllers;

//記得載入要用的程式
use App\Models\Article;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\Response;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\ArticleResource;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Requests\ArticleTagSyncRequest;
use App\Http\Requests\ArticleTagsAttachRequest;
use App\Http\Requests\ArticleTagsDetachRequest;
use Illuminate\Auth\Access\AuthorizationException;

class ArticleController extends Controller
{
    use ResponseTrait;

    protected $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $data = $this->articleService->getArticlePaginate($request->perPage);
            return $this->responseSuccess($data, 'Article list fetch successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $article = $this->articleService->getArticle($id);
            return $this->responseSuccess($article, 'Article fetched successfully!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ArticleStoreRequest $request): JsonResponse
    {
        try {
            $data = $this->articleService->saveArticle($request->validated());
            return $this->responseSuccess($data, 'Article create successfully!!');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(ArticleUpdateRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->articleService->updateArticle($id, $request->validated());
            return $this->responseSuccess($data, 'Article updated successfully!');
        } catch (AuthorizationException $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_FORBIDDEN);
        } catch (\Exception $e){
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->articleService->deleteArticle($id);
            return $this->responseSuccess(null, 'Article deleted successfully！');
        } catch (\Exception $e) {
            return $this->responseError($e->getMessage(), 'deleted failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    //輸出成excel
    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'id');
        $sheet->setCellValue('B1', 'title');
        $sheet->setCellValue('C1', 'body');
        $sheet->setCellValue('D1', 'tags');

        $articles = Article::get();
        $row = 2;

        foreach($articles as $article){
            $sheet->setCellValue('A'.$row, $article->id);
            $sheet->setCellValue('B'.$row, $article->title);
            $sheet->setCellValue('C'.$row, $article->body);
            $sheet->setCellValue('D'.$row, $article->tags->pluck('id')->join(',')); //pluck是collection方法
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('articles.xlsx');
        return response()->download('articles.xlsx');
    }

    public function attachTags(Article $article, ArticleTagsAttachRequest $request)
    {
        try {
            if (Gate::denies('update-article', $article)){
                throw new AuthorizationException('You are not authorized to update this article!');
            }
            $tags = $request->input('tags');
            $this->articleService->attachTags($article, $tags);
            return $this->responseSuccess(null, 'Tags attached successfully!');
        }catch (\Exception $e){
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
