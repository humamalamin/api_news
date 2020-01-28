<?php

namespace Tests\Admin;

use App\Models\Article;
use TestCase;

class FieldTest extends TestCase
{
    private $article;

    public function setUp():void
    {
        parent::setUp();

        $this->article = factory(Article::class)->create();
    }

    /**
     * ../admin/articles [GET]
     */
    public function testGetArticle()
    {
        $this->get(route('api.v1.admin.articles.index'));
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' => [
                '*' =>
                    [
                        'id',
                        'judul',
                        'summary',
                        'deskripsi',
                        'penulis',
                        'image'
                    ]
            ],
            'status',
            'message',
            'errors'
        ]);
    }

    /**
     * ../admin/articles/{articleId} [GET]
     */
    public function testGetArticleByID()
    {
        $this->get(route('api.v1.admin.articles.show', ['articleId' => $this->article->id]), []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([
            'data' =>
                [
                    'id',
                    'judul',
                    'summary',
                    'deskripsi',
                    'penulis',
                    'image',
                    'created_at',
                    'updated_at',
                ],
            'status',
            'message',
            'errors'
        ]);
    }

    /**
     * ../admin/articles/{articleId} [GET]
     */
    public function testGetArticleByIDError404()
    {
        $this->get(route('api.v1.admin.articles.show', ['articleId' => 0]), []);
        $this->seeStatusCode(404);
        $this->seeJsonStructure(
            [
                'status',
                'data',
                'errors',
                'message'
            ]
        );
    }

    /**
     * ../admin/articles [POST]
     */
    public function testStoreArticle()
    {
        $this->post(route('api.v1.admin.articles.store'), [
            'judul' => 'Harry Potter',
            'summary' => 'afaegvdzvdv',
            'deskripsi' => 'adadadadasaafsafg',
            'penulis' => 'Roland'
        ]);

        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'data' => [
                'id',
                'judul',
                'summary',
                'deskripsi',
                'penulis',
                'image',
                'created_at'
            ],
            'status',
            'message',
            'errors'
        ]);
    }

    /**
     * ../admin/articles [POST]
     */
    public function testStoreArticle422()
    {
        $this->post(route('api.v1.admin.articles.store'), [
            'judul' => 'Harry Potter',
            'summary' => 'afaegvdzvdv',
            'deskripsi' => 'adadadadasaafsafg',
            'penuliss' => 'Roland'
        ]);

        $this->seeStatusCode(422);
        $this->seeJsonStructure([
            'data',
            'status',
            'message',
            'errors'
        ]);
    }

    /**
     * ../admin/articles/{articleId} [DELETE]
     */
    public function testCanDeleteArticle()
    {
        $this->delete(route('api.v1.admin.articles.delete', ['articleId' => $this->article->id]));
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'status',
                'data',
                'errors',
                'message'
            ]
        );
    }

    /**
     * ../admin/articles/{articleId} [DELETE]
     */
    public function testErrorDeleteArticle404()
    {
        $this->delete(route('api.v1.admin.articles.delete', ['articleId' => 0]));
        $this->seeStatusCode(404);
        $this->seeJsonStructure(
            [
                'status',
                'data',
                'errors' => [
                    '*' =>
                        [
                            'status',
                            'detail'
                        ]
                ],
                'message'
            ]
        );
    }
}
