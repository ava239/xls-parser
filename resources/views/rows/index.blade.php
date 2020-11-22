@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">{{ __('rows.title') }}</h1>
        <table class="table mt-2">
            <thead>
            <tr>
                <th>{{ __('layout.headers.date') }}</th>
                <th class="text-center">{{ __('layout.headers.id') }} : {{ __('layout.headers.name') }}</th>
            </tr>
            </thead>
            @foreach($rows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td class="d-flex flex-wrap">
                        {!! collect($row['values'])->map(fn($elem) => "<span class='w-25 text-center'>{$elem['import_id']} : {$elem['import_name']}</span>")->join('') !!}
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $dates->links() }}
    </div>
@endsection
