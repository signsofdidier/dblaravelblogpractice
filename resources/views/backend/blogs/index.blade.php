@extends('layouts.backend')
@section('title')
    Blogs
@endsection
@section('cards')
@endsection
@section('charts')
@endsection
@section('content')
    <div class="overflow-hidden">
        @include('layouts.partials.flash_message')
        <div class="row gy-5">
            @if($blogs->isNotEmpty())
                @foreach($blogs as $blog)
                    @if($loop->odd)
                        <div class="col-12">
                            <div class="row align-items-center gy-3 gy-md-0 gx-xl-5">
                                <div class="col-xs-12 col-md-6">
                                    <div class="img-wrapper position-relative hcf-transform">
                                        <a href="#!">
                                            <span class="badge rounded-pill text-bg-warning position-absolute top-10px start-10px">Sports</span>
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
                                        <h2 class="h1 mb-3"><a class="link-dark text-decoration-none" href="#!">{{$blog->title}}</a></h2>
                                        <p class="mb-4">{{$blog->description}}</p>
                                        <a class="btn btn-primary" href="#!" target="_self">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($loop->even)
                        <div class="col-12">
                            <div class="row align-items-center flex-row-reverse gy-3 gy-md-0 gx-xl-5">
                                <div class="col-xs-12 col-md-6">
                                    <div class="img-wrapper position-relative hcf-transform">
                                        <a href="#!">
                                            <span class="badge rounded-pill text-bg-warning position-absolute top-10px end-10px">Travel</span>
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
                                        <h2 class="h1 mb-3"><a class="link-dark text-decoration-none" href="#!">{{$blog->title}}</a></h2>
                                        <p class="mb-4">{{$blog->description}}</p>
                                        <a class="btn btn-primary" href="#!" target="_self">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <p>Deze pagina heeft nog geen blogs.</p>
                </div>
            @endif
        </div>
    </div>
@endsection


