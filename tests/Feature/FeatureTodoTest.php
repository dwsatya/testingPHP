<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class FeatureLikeTest extends TestCase
{
    public function testStoreLike()
    {
        // 1. Cek halaman yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Like an item');

        // 2. User memberikan "like" pada sebuah item
        $data = [
            'item_id' => 2,
            'user_id' => 1,
        ];
        $storeData = $this->post(route('like.store'), $data);

        // 3. Apakah "like" berhasil ditambahkan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('likes', [
            'item_id' => 2,
            'user_id' => 1,
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testRemoveLike()
    {
        // 1. Cek halaman yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Like an item');

        // 2. User menghapus "like" dari sebuah item
        $storeData = $this->delete(route('like.destroy', ['item_id' => 2, 'user_id' => 1]));

        // 3. Apakah "like" berhasil dihapus
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('likes', [
            'item_id' => 2,
            'user_id' => 1,
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }
}
