<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Row;
use Tests\TestCase;

class RowsTest extends TestCase
{
    public function testIndex()
    {
        $file = File::factory()->create();
        $file->rows()->saveMany(
            Row::factory()->count(10)->make()
        );

        $row = Row::first();

        $response = $this->get(route('files.rows.index', $file));

        $response->assertOk()
            ->assertSee($row->import_name);
    }
}
