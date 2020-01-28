<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * @OA\Schema(schema="Article", title="Article")
 */
class ArticleResource extends Resource
{
    /**
     * @OA\Property(
     *     property="id",
     *     type="integer"
     * ),
     * @OA\Property(
     *     property="judul",
     *     type="string"
     * ),
     * @OA\Property(
     *     property="summary",
     *     type="integer"
     * ),
     * @OA\Property(
     *     property="deskripsi",
     *     type="integer"
     * ),
     * @OA\Property(
     *     property="penulis",
     *     type="integer"
     * ),
     * @OA\Property(
     *     property="image",
     *     type="integer"
     * ),
     * @OA\Property(
     *     property="created_at",
     *     type="string"
     * ),
     * @OA\Property(
     *     property="updated_at",
     *     type="string"
     * )
     *
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     *
     * @SuppressWarnings("unused")
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'summary' => $this->summary,
            'deskripsi' => $this->deskripsi,
            'penulis' => $this->penulis,
            'image' => $this->image,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d\TH:i:s\Z') : '',
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d\TH:i:s\Z') : ''
        ];
    }
}
