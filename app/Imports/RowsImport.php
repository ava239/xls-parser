<?php

namespace App\Imports;

use App\Models\File;
use App\Models\Row as ModelRow;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RowsImport implements
    ToModel,
    WithHeadingRow,
    WithBatchInserts,
    WithChunkReading,
    ShouldQueue,
    WithEvents,
    WithCalculatedFormulas
{
    protected File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function model($row)
    {
        ['id' => $id, 'name' => $name, 'date' => $date] = $row;
        return new ModelRow([
            'import_id' => $id,
            'import_name' => $name,
            'import_date' => Carbon::parse(Date::excelToTimestamp($date)),
            'file_id' => $this->file->id
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $this->file->status = 'parsed';
                $this->file->save();
            },
            BeforeImport::class => function (BeforeImport $event) {
                $this->file->status = 'parsing in progress';
                $this->file->save();
            }
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
