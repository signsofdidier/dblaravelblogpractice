@extends('layouts.backend')
@section('title')
    Blogs
@endsection
@section('cards')
@endsection
@section('charts')
@endsection
@section('content')
    @include('layouts.partials.flash_message')
    {{--BLOGS DATATABLE--}}
    <div class="row">
        <div class="col-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Photo</th>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Categories</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @if($blogs)
                    @foreach($blogs as $blog)
                        <tr>
                            <td class="align-self-center">{{$blog->id}}</td>
                            <td>
                                @if($blog->photo && file_exists(public_path('assets/img/' . $blog->photo->path)))
                                    <img src="{{asset('assets/img/' . $blog->photo->path)}}"
                                         alt="{{$blog->photo->alternate_text}}"
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
                            <td>{{$blog->user ? $blog->user->name : ''}}</td>
                            <td>{{$blog->title}}</td>
                            <td>
                                <div>
                                    @if($blog->categories)
                                        @foreach($blog->categories as $category)
                                            <span class="badge rounded-pill text-bg-success">{{$category->name}}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>{{$blog->created_at->diffForHumans()}}</td>
                            <td>{{$blog->updated_at->diffForHumans()}}</td>
                            {{--<td>{{$blog->deleted_at ? 'yes' : 'No'}}</td>--}}
                            <td>
                                <!-- Edit button -->
                                <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-info btn-sm" title="Edit Blog">
                                    <i class="fas fa-edit text-white"></i>
                                </a>
                                @if($blog->trashed())
                                    <!-- Restore button -->
                                    <form method="POST" action="{{ route('blogs.restore', $blog->id) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Restore Blog">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Delete button -->
                                    <form method="POST" action="{{ route('blogs.destroy', $blog->id) }}"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Blog">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            {{--<td>
                                <a href="{{ route('blogs.edit', $blog->id) }}"  class="btn btn-info btn-sm {{$blog->trashed() ? 'invisible' : ''}}" title="Edit Blog"><i class="fas fa-edit text-white"></i>
                                </a>
                                @if($blog->trashed())
                                    <form method="POST" action="{{ route('blogs.restore', $blog->id)  }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" title="Restore Blog">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else()
                                    <form method="POST" action="{{ route('blogs.destroy', $blog->id)  }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete Blog">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>--}}
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <div>
            {{$blogs->links()}}
        </div>
    </div>

    {{--BLOGS--}}
    <div class="row gy-5">
        @if($blogs->isNotEmpty())
            @php $visibleIndex = 0; @endphp
            @foreach($blogs as $blog)
                @if(!$blog->trashed())
                    @php $visibleIndex++ @endphp
                    @if($visibleIndex % 2 === 1)
                        <div class="col-12">
                            <div class="row align-items-center gy-3 gy-md-0 gx-xl-5">
                                <div class="col-xs-12 col-md-6">
                                    <div class="img-wrapper position-relative hcf-transform">
                                        <a href="#">
                                            <ul class="ps-0 position-absolute top-10px start-10px">
                                                @foreach($blog->categories as $category)
                                                    <li class="badge rounded-pill text-bg-warning" >{{$category->name}}</li>
                                                @endforeach
                                            </ul>
                                            @if($blog->photo && file_exists(public_path('assets/img/' . $blog->photo->path)))
                                                <img class="img-fluid rounded w-100 hcf-of-cover hcf-op-center hcf-ih-250 hcf-ih-md-400" loading="lazy"  src="{{asset('assets/img/' . $blog->photo->path)}}" alt="{{$blog->photo->alternate_text}}">
                                            @else
                                                <img
                                                    src="https://placehold.co/300"
                                                    class="img-fluid rounded w-100 hcf-of-cover hcf-op-center hcf-ih-250 hcf-ih-md-400" loading="lazy"
                                                    alt="No image"
                                                >
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div>
                                        <p class="text-secondary mb-1">{{$blog->created_at->diffForHumans()}}</p>
                                        <p class="text-secondary mb-1 small">Author: {{$blog->user ? $blog->user->name : ''}}</p>
                                        <h2 class="h1 mb-3"><a class="link-dark text-decoration-none" href="#">{{$blog->title}}</a></h2>
                                        <p class="mb-4">{{$blog->description}}</p>
                                        <a class="btn btn-primary" href="#!" target="_self">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <div class="row align-items-center flex-row-reverse gy-3 gy-md-0 gx-xl-5">
                                <div class="col-xs-12 col-md-6">
                                    <div class="img-wrapper position-relative hcf-transform">
                                        <a href="#">

                                            <ul class="position-absolute top-10px end-10px">
                                                @foreach($blog->categories as $category)
                                                    <li class="badge rounded-pill text-bg-warning">{{$category->name}}</li>
                                                @endforeach
                                            </ul>
                                            @if($blog->photo && file_exists(public_path('assets/img/' . $blog->photo->path)))
                                                <img class="img-fluid rounded w-100 hcf-of-cover hcf-op-center hcf-ih-250 hcf-ih-md-400" loading="lazy"  src="{{asset('assets/img/' . $blog->photo->path)}}" alt="{{$blog->photo->alternate_text}}">
                                            @else
                                                <img
                                                    src="https://placehold.co/300"
                                                    class="img-fluid rounded w-100 hcf-of-cover hcf-op-center hcf-ih-250 hcf-ih-md-400" loading="lazy"
                                                    alt="No image"
                                                >
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div>
                                        <p class="text-secondary mb-1">{{$blog->created_at->diffForHumans()}}</p>
                                        <p class="text-secondary mb-1 small">Author: {{$blog->user? $blog->user->name : ''}}</p>
                                        <h2 class="h1 mb-3"><a class="link-dark text-decoration-none" href="#">{{$blog->title}}</a></h2>
                                        <p class="mb-4">{{$blog->description}}</p>
                                        <a class="btn btn-primary" href="#!" target="_self">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <div class="col-12">
                <p>Deze pagina heeft nog geen blogs.</p>
            </div>
        @endif
    </div>
@endsection


