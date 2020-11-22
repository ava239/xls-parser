<?php

namespace App\Http\Controllers;

use App\Models\File;
use DB;
use Illuminate\Http\Request;

class RowController extends Controller
{
    public function index(File $file)
    {
        $dates = DB::table('rows')
            ->where('file_id', $file->id)
            ->distinct('import_date')
            ->get('import_date')
            ->map(fn($date) => $date->import_date)
            ->paginate(10);

        $rows = $file->rows()->whereIn('import_date', $dates->values())
            ->get(['import_id', 'import_name', 'import_date'])
            ->groupBy('import_date')
            ->map(function ($val, $key) {
                return [
                    'date' => $key,
                    'values' => $val->toArray()
                ];
            });

        return view('rows.index', compact('rows', 'dates'));
    }
}
