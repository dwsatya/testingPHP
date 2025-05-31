<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class FeatureTodoTest extends TestCase
{
    public function testStoreDataActivity()
    {
        // 1. Cek url yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Enter an activity');

        // 2. User mengirim data ke server
        $data = [
            'item' => 'Testing',
        ];
        $storeData = $this->post(route('item.store'), $data);

        // 3. Apakah data berhasil ditambahkan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Testing',
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testStoreDataActivityWithTag()
    {
        // 1. Cek url yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Enter an activity');

        // 2. User mengirim data ke server
        $data = [
            'item' => 'Testing With Tag|tag1',
        ];
        $storeData = $this->post(route('item.store'), $data);

        // 3. Apakah data berhasil ditambahkan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Testing With Tag',
        ]);
        $this->assertDatabaseHas('tags', [
            'tag_name' => 'tag1',
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testDeleteDataActivity()
    {
        // 1. Cek url yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Enter an activity');

        // 2. User mengirim data ke server
        $storeData = $this->delete(route('item.destroy', ['id' => 3]));

        // 3. Apakah data berhasil dihapus
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseMissing('tasks', [
            'id' => 3,
        ]);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }

    public function testStoreDataActivityWithMultipleTags()
    {
        // 1. Cek url yang diakses
        $response = $this->get(route('dashboard'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Enter an activity');

        // 2. User mengirim data ke server dengan beberapa tag
        $data = [
            'item' => 'Task With Tags|urgent,home,work',
        ];
        $storeData = $this->post(route('item.store'), $data);

        // 3. Periksa apakah data dan semua tag tersimpan
        $storeData->assertStatus(Response::HTTP_FOUND);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Task With Tags',
        ]);
        $this->assertDatabaseHas('tags', ['tag_name' => 'urgent']);
        $this->assertDatabaseHas('tags', ['tag_name' => 'home']);
        $this->assertDatabaseHas('tags', ['tag_name' => 'work']);

        // 4. Redirect ke halaman dashboard
        $storeData->assertRedirect(route('dashboard'));
    }
}
