<?php

namespace Tests\Feature;

use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function brandStore()
    {

        Storage::fake();
        $image = UploadedFile::fake()->image('test');
        $brand = Brand::factory()->raw();
        $brand['image'] = $image;

        $response = $this->post(route('brands.store'),$brand);

//        $response->assertStatus(200);
        $this->assertDatabaseHas('brands',[
            'name' => $brand['name'],
            'image' => 'test',
        ]);

        $file = Brand::where('name',$brand['name'])->first()->getFirstMediaPath();
        $this->assertFileExists($file);

    }
}
