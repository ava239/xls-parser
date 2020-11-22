<?php

namespace App\Http\Controllers;

use App\Models\Row;
use DB;
use Illuminate\Http\Request;

class RowController extends Controller
{
    public function index()
    {
        $dates = DB::table('rows')
            ->distinct('import_date')
            ->get('import_date')
            ->map(fn($date) => $date->import_date)
            ->paginate(10);

        $rows = Row::whereIn('import_date', $dates)
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
