<?php

namespace Tests\Feature;

use App\Models\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class FilesTest extends TestCase
{
    use WithFaker;

    public function testIndex()
    {
        $file = File::factory()->create();

        $response = $this->get(route('files.index'));

        $response->assertOk()
            ->assertSee($file->name);
    }

    public function testCreate()
    {
        $response = $this->get(route('files.create'));

        $response->assertOk();
    }

    public function testStore()
    {
        Storage::fake();
        Excel::fake();
        $fileName = "{$this->faker->word}.xls";
        $data = [
            'file' => UploadedFile::fake()->create($fileName)
        ];
        $response = $this->post(route('files.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $file = File::latest()->first();
        $supposedFilename = "{$file->id}-{$fileName}";

        Excel::assertQueued($supposedFilename);

        $this->assertDatabaseHas('files', ['name' => $supposedFilename]);
        Storage::disk()->assertExists($supposedFilename);
    }
}
