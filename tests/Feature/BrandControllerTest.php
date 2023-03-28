<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

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

        $response = $this->actingAs($this->superAdmin)->postJson(route('brands.store'),$brand);
        $file = Brand::where('name',$brand['name'])->first()->getFirstMediaPath();

        $response->assertStatus(201);
        $this->assertDatabaseHas('brands',[
            'name' => $brand['name'],
        ]);

        $this->assertFileExists($file);

    }
}
