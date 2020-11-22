<?php

namespace App\Imports;

use App\Models\File;
use App\Models\Row as ModelRow;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RowsImport implements OnEachRow, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents
{
    use Importable;
    use RemembersRowNumber;

    protected File $file;
    protected int $chunk = 1000;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function onRow(Row $row)
    {
        $numRow = $row->getIndex();
        $start = Redis::get("file:{$this->file->id}") ?? 0;
        if ($numRow <= $start + 1 || $numRow >= $start + 2 + $this->chunk) {
            return null;
        }
        $coll = $row->toArray(null, true);
        $coll['date'] = Date::excelToTimestamp($coll['date']);
        ['id' => $id, 'name' => $name, 'date' => $date] = $coll;
        ModelRow::create([
            'import_id' => $id,
            'import_name' => $name,
            'import_date' => Carbon::parse($date),
            'file_id' => $this->file->id
        ]);
    }

    public function chunkSize(): int
    {
        return $this->chunk;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $start = Redis::get("file:{$this->file->id}") ?? 0;
                $end = $start + $this->chunk;
                [$rows] = array_values($event->getReader()->getTotalRows());
                if ($rows <= $end) {
                    Redis::del("file:{$this->file->id}");
                    return null;
                }
                Redis::set("file:{$this->file->id}", $end);
                Excel::queueImport(new RowsImport($this->file), $this->file->name)->delay(30);
            }
        ];
    }
}
