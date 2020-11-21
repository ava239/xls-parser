@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="mb-5">{{ __('files.create_title') }}</h1>
        {{ BsForm::post(route('files.store'), ['files' => true, 'class' => 'w-50']) }}
        @include('files.form')
        <div>
            {{ BsForm::submit(__('layout.buttons.upload'))->primary() }}
        </div>
        {{ BsForm::close() }}
    </div>
@endsection
