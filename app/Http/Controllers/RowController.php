<?php

namespace App\Http\Controllers;

use App\Models\Row;
use Illuminate\Http\Request;

class RowController extends Controller
{
    public function index()
    {
        $dates = Row::distinct('import_date')->get('import_date')->paginate(10);
        $rows = Row::whereIn('import_date', $dates->values())
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
