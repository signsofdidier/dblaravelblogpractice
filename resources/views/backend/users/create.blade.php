@extends('layouts.backend')
@section('title')
    Create User
@endsection
@section('cards')
@endsection
@section('charts')
@endsection
@section('content')
    @include('layouts.partials.form_error')
    <form method="POST"
          action="{{ action('App\Http\Controllers\UserController@store') }}"
          enctype="multipart/form-data">
        @csrf
        <div class="form-group mt-1">
            <label for="name">Name:</label>
            <input
                type="text"
                name="name"
                id="name"
                class="form-control"
                value="{{ old('name') }}"
            >
        </div>
        <div class="form-group mt-1">
            <label for="email">E-mail:</label>
            <input
                type="text"
                name="email"
                id="email"
                class="form-control"
                value="{{ old('email') }}"
            >
        </div>
        <div class="form-group mt-1">
            <label for="role_id">Select roles:</label>
            <select name="role_id[]" id="role_id" class="form-control" multiple>
                <option value="" disabled {{ old('role_id') ? '' : 'selected' }}>Select a role</option>
                @foreach($roles as $id => $role)
                    <option value="{{ $id }}" {{ collect(old('role_id', []))->contains($id) ? 'selected' : '' }}>
                        {{ $role }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group mt-1">
            <label for="is_active">Select status:</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ old('is_active') == "1" ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('is_active') == "0" ? 'selected' : '' }}>Not Active</option>
            </select>
        </div>
        <div class="form-group mt-1">
            <label for="password">Password:</label>
            <input
                type="password"
                name="password"
                id="password"
                class="form-control"
            >
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
            <button type="submit" class="btn btn-primary">Create User</button>
        </div>
    </form>
@endsection
