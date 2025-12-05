@extends('admin.dashboard')

@section('admin')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-page-head">
                <div class="nk-block-head-between flex-wrap gap g-2">
                    <div class="nk-block-head-content">
                        <h2 class="display-6">Assistant Library</h2>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="d-flex gap gx-4">
                            <div class="">
                                <ul class="d-flex gap gx-2">
                                    <li>
                                        <a href="{{ route('add.assistants') }}" class="btn btn-primary">Add Assistant</a>
                                    </li>
                                    <li>
                                        <a href="templates-list.html" class="btn btn-md btn-icon btn-outline-light"><em class="icon ni ni-view-list-wd"></em></a>
                                    </li>
                                    <li>
                                        <a href="templates.html" class="btn btn-md btn-icon btn-primary btn-soft"><em class="icon ni ni-grid-fill"></em></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="">
                                <div class="form-control-wrap">
                                    <div class="form-control-icon start md text-light">
                                        <em class="icon ni ni-search"></em>
                                    </div>
                                    <input type="text" class="form-control form-control-md" placeholder="Search Template">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .nk-page-head -->
            <div class="nk-block">
                <ul class="filter-button-group mb-4 pb-1">
                    <li><button class="filter-button active" type="button" data-filter="*"> All </button></li>
                    <li><button class="filter-button" type="button" data-filter=".blog-content"> Blog Content </button></li>
                    <li><button class="filter-button" type="button" data-filter=".social-media"> Social Media </button></li>
                    <li><button class="filter-button" type="button" data-filter=".website-copy-seo"> Website Copy &amp; SEO </button></li>
                </ul>
                <div class="row g-gs filter-container" data-animation="true">
                    @foreach($assistants as $key => $assistant)
                    <div class="col-sm-4 col-xxl-3 filter-item blog-content" data-category="blog-content">
                        <div class="card card-full shadow-none">
                            <div class="card-body text-center">  <!-- Content center -->
                                
                                <a href="{{ route('chatassistants.chat', $assistant->id) }}"> 
                                    <div class="d-flex justify-content-center mb-3">
                                        <img id="showImage" 
                                            src="{{ (!empty($assistant->avater)) ? url('upload/avater/'.$assistant->avater) : url('upload/no_image.jpg') }}" 
                                            class="rounded-circle"
                                            style="width: 120px; height:120px; object-fit: cover; border-radius: 50%;">
                                    </div>
                                </a>

                                <a href="{{ route('templates.edit', $assistant->id) }}"> 
                                    <h5 class="fs-4 fw-medium">{{ $assistant->name }}</h5>
                                    <p class="small text-light line-clamp-2">{{ $assistant->role_description }}</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    @endforeach
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
@endsection