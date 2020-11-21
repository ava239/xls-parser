<?php

namespace App\Http\Controllers;

use App\Models\File as FileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    public function index()
    {
        $files = FileModel::paginate(10);

        return view('files.index', compact('files'));
    }

    public function create()
    {
        $file = new FileModel();

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

        $file = new FileModel();

        $file->saveOrFail();

        $filename = "{$file->id}-{$request->file('file')->getClientOriginalName()}";
        $request->file->storeAs('', $filename);
        $file->name = $filename;
        $file->save();

        Redis::set("file:{$file->id}", 0);

        flash(__('files.flash.created'))->success();

        return redirect()
            ->route('files.index');
    }
}
