<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class FeatureCommentTest extends TestCase
{
    public function testStoreComment()
    {
        // 1. Cek halaman yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Write a comment');

        // 2. User mengirim data komentar ke server
        $data = [
            'content' => 'This is a test comment',
            'item_id' => 1,
        ];
        $storeData = $this->post(route('comment.store'), $data);

        // 3. Apakah data berhasil ditambahkan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'item_id' => 1,
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testStoreCommentWithTags()
    {
        // 1. Cek halaman yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Write a comment');

        // 2. User mengirim data komentar dengan tag ke server
        $data = [
            'content' => 'This comment has a tag|tag2',
            'item_id' => 1,
        ];
        $storeData = $this->post(route('comment.store'), $data);

        // 3. Apakah data berhasil ditambahkan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('comments', [
            'content' => 'This comment has a tag',
            'item_id' => 1,
        ]);
        $this->assertDatabaseHas('tags', [
            'tag_name' => 'tag2',
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testDeleteComment()
    {
        // 1. Cek halaman yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Write a comment');

        // 2. User menghapus komentar tertentu
        $storeData = $this->delete(route('comment.destroy', ['id' => 5]));

        // 3. Apakah data berhasil dihapus
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('comments', [
            'id' => 5,
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }
}