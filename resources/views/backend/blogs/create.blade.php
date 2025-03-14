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

        <select class="form-control mt-2" name="category_id[]" id="category_id" class="form-control" multiple>
            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
            @foreach($categories as $id => $category)
                <option value="{{ $id }}" {{ collect(old('category_id', []))->contains($id) ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <div class="form-group mt-1">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="5">{{ old('description') }}</textarea>
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
