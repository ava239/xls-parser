@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">{{ __('files.title') }}</h1>
        <div class="d-flex">
            <a href="{{ route('files.create') }}" class="btn btn-primary ml-auto">{{ __('layout.buttons.upload') }}</a>
        </div>
        <table class="table mt-2">
            <thead>
            <tr>
                <th>{{ __('layout.headers.id') }}</th>
                <th>{{ __('layout.headers.filename') }}</th>
                <th>{{ __('layout.headers.status') }}</th>
                <th>{{ __('layout.headers.uploaded_at') }}</th>
            </tr>
            </thead>
            @foreach($files as $file)
                <tr>
                    <td>{{ $file->id }}</td>
                    <td>{{ $file->name }}</td>
                    <td>{{ $file->status }}<br>{{ $file->rows_count }} {{ __('layout.texts.rows') }}</td>
                    <td>{{ $file->created_at }}</td>
                </tr>
            @endforeach
        </table>

        {{ $files->links() }}
    </div>
@endsection
