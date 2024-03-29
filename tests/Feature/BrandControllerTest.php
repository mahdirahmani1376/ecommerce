<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BrandControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function brandStore()
    {
        Storage::fake();
        $image = UploadedFile::fake()->image('test');
        $brand = Brand::factory()->raw();
        $brand['image'] = $image;

        $response = $this->actingAs($this->superAdmin)->postJson(route('brand.store'), $brand);
        $file = Brand::where('name', $brand['name'])->first()->getFirstMediaPath();

        $response->assertStatus(201);
        $this->assertDatabaseHas('brands', [
            'name' => $brand['name'],
        ]);

        $this->assertFileExists($file);
    }
}
