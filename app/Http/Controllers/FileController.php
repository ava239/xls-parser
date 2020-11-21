<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    public function index()
    {
        $files = File::paginate(10);

        return view('files.index', compact('files'));
    }

    public function create()
    {
        $file = new File();

        return view('files.create', compact('file'));
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'file' => [
                    'required',
                    'mimes:xls,xlsx',
                ],
            ]
        );

        $file = new File();

        $filename = $request->file('file')->getClientOriginalName();
        $request->file->storeAs('', $filename);
        $file->name = $filename;

        $file->saveOrFail();

        flash(__('files.flash.created'))->success();

        return redirect()
            ->route('files.index');
    }
}
