@extends('layouts.backend')
@section('title')
    Create Blog
@endsection
@section('cards')
@endsection
@section('charts')
@endsection
@section('content')
    @include('layouts.partials.form_error')
    <form method="POST"
          action="{{ action('App\Http\Controllers\BlogController@store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group mt-1">
            <label for="name">title:</label>
            <input
                type="text"
                name="title"
                id="title"
                class="form-control"
                value="{{ old('title') }}"
            >
        </div>
        <div class="form-group mt-1">
            <label for="description">Description:</label>
{{--            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>--}}
            <textarea class="form-control" name="description" id="default" cols="30" rows="10"></textarea>
        </div>

        <div class="form-group mt-1">
            <label for="photo_id">Image:</label>
            <input
                type="file"
                name="photo_id"
                id="photo_id"
                class="form-control"
            >
        </div>
        <div class="form-group mt-2">
            <button type="submit" class="btn btn-primary">Create Blog</button>
        </div>
    </form>
@endsection
