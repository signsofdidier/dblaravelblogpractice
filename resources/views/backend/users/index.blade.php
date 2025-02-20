@extends('layouts.backend')
{{--@section('cards')--}}
{{--@endsection--}}
@section('charts')
@endsection
@section('content')
    <h1>Users</h1>
    @include('layouts.partials.flash_message')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Id</th>
            <th>Photo</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Role</th>
            <th>Active</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Deleted</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if($users)
            @foreach($users as $user)
                <tr>
                    <td class="align-self-center">{{$user->id}}</td>
                    <td>
                        @if($user->photo && file_exists(public_path('assets/img/' . $user->photo->path)))
                            <img src="{{asset('assets/img/' . $user->photo->path)}}"
                                 alt="{{$user->photo->alternate_text}}"
                                 class="img-fluid rounded-circle object-fit-cover"
                                 style="width: 40px; height:40px"
                            >
                        @else
                            <img
                                src="https://placehold.co/60"
                                alt="No image"
                                class="img-fluid rounded-circle object-fit-cover"
                                style="width: 40px; height:40px"
                            >
                        @endif
                    </td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        <div>
                            @foreach($user->roles as $role)
                                <span
                                    class="badge rounded-pill
                                    @if($role->id == 1 ) bg-danger
                                    @elseif($role->id == 2) bg-warning
                                    @else bg-success
                                    @endif
                                    "
                                >{{$role->name}}</span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="badge rounded-pill {{$user->is_active == 1 ? 'bg-success' : 'bg-secondary'}}">
                            {{$user->is_active == 1 ? 'Active':'Not Active'}}
                        </div>
                    </td>
                    <td>{{$user->created_at->diffForHumans()}}</td>
                    <td>{{$user->updated_at->diffForHumans()}}</td>
                    <td>{{$user->deleted_at ? 'yes' : 'No'}}</td>
                    <td>
                        {{--EDIT BUTTON--}}
                        <a href="{{ route('users.edit', $user->id) }}"  class="btn btn-info btn-sm {{$user->trashed() ? 'invisible' : ''}}" title="Edit User"><i class="fas fa-edit text-white"></i>
                        </a>
                        @if($user->trashed())
                            <form method="POST" action="{{ route('users.restore', $user->id)  }}" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" title="Restore User">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                        @else()
                            <form method="POST" action="{{ route('users.destroy', $user->id)  }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
    <div class="">
        {{$users->links()}}
    </div>
@endsection

