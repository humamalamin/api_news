<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/v1/admin/articles",
     *     tags={"article"},
     *     summary="Fetch all articles",
     *     description="Returns all articles",
     *     operationId="listArticles",
     *     @OA\Response(
     *         response=200,
     *         description="List of articles",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Article")
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return Illuminate\Http\Resources\Json\Resource
     */
    public function index()
    {
        $articles = Article::all();

        return ArticleResource::collection($articles);
    }

    /**
     * @OA\Get(
     *   path="/api/v1/admin/articles/{articleId}",
     *     tags={"article"},
     *     summary="Get article by ID",
     *     description="Returns a single article data",
     *     operationId="getArticleByID",
     *     @OA\Parameter(
     *         name="articleId",
     *         in="path",
     *         description="ID of article to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Article")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Field not found"
     *     )
     * )
     *
     * @param  int  $articleId
     * @return Illuminate\Http\Resources\Json\Resource
     */
    public function show($articleId)
    {
        $article = Article::findOrFail($articleId);

        return new ArticleResource($article);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/admin/articles",
     *     tags={"article"},
     *     summary="Add a new article",
     *     description="Adding new article data",
     *     operationId="addArticle",
     *     @OA\RequestBody(
     *         description="",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="judul",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="summary",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="deskripsi",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="penulis",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid article parameter"
     *     )
     * )
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'summary' => 'required',
            'deskripsi' => 'required',
            'penulis' => 'required',
            'image' => 'mimes:jpeg, png, jpg'
        ]);

        // @codeCoverageIgnoreStart
        $pathImage = "";
        if ($request->hasFile('image')) {
            $targetPath = 'article/'.date('YmdHis'). '_' . strtolower(str_replace(' ', '_', $request->file('image')->getClientOriginalName()));
            Storage::disk('public')->put($targetPath, file_get_contents($request->file('image')));
            $pathImage = $targetPath;
        }
        // @codeCoverageIgnoreEnd

        $article = Article::create(array_merge($request->all(), [
            'image' => $pathImage
        ]));

        return new ArticleResource($article);
    }

    /**
     * @OA\Put(
     *   path="/api/v1/admin/articles/{articleId}",
     *     tags={"article"},
     *     summary="Edit article",
     *     description="Updating article data",
     *     operationId="editArticle",
     *     @OA\RequestBody(
     *         description="",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="judul",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="summary",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="deskripsi",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="penulis",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid article parameter"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID article"
     *     )
     * )
     *
     * @param  Request  $request
     * @param  int  $articleId
     * @return Illuminate\Http\Resources\Json\Resource
     */
    public function update(Request $request, $articleId)
    {
        $this->validate($request, [
            'judul' => 'required',
            'summary' => 'required',
            'deskripsi' => 'required',
            'penulis' => 'required',
            'image' => 'mimes:jpeg, png, jpg'
        ]);

        $article = Article::findOrFail($articleId);

        // @codeCoverageIgnoreStart
        $pathImage = "";
        if ($request->hasFile('image')) {
            $fileUpload = $request->file('image');
            $targetFileUpload =  'article/' . date('YmdHis') .'_' . strtolower(str_replace(' ', '_', $fileUpload->getClientOriginalName()));

            Storage::disk('public')->put($targetFileUpload, file_get_contents($fileUpload));
            $pathImage = $targetFileUpload;
        }
        // @codeCoverageIgnoreEnd

        $article->update(array_merge($request->all(), [
            'image' => $pathImage
        ]));

        return new ArticleResource($article);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/articles/{articleId}",
     *     tags={"article"},
     *     summary="Delete article based on ID",
     *     description="",
     *     operationId="deleteArticleById",
     *     @OA\Parameter(
     *         name="articleId",
     *         in="path",
     *         description="ID of article to return",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="article not found"
     *     )
     * )
     *
     * @param $articleId
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function destroy($articleId)
    {
        $article = Article::findOrFail($articleId);
        $article->delete();

        return response()->json(['status' => 200, 'data' => null, 'errors' => [], 'message' => null], 200);
    }
}
