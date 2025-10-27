@extends('admin.dashboard')

@section('admin')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-page-head">
                <div class="nk-block-head-between flex-wrap gap g-2">
                    <div class="nk-block-head-content">
                        <h2 class="display-6">Template Library</h2>
                    </div>
                    <div class="nk-block-head-content">
                        <div class="d-flex gap gx-4">
                            <div class="">
                                <ul class="d-flex gap gx-2">
                                    <li>
                                        <a href="{{ route('templates.create') }}" class="btn btn-primary">Add Template</a>
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
                    @foreach($templates as $key => $template)
                    <div class="col-sm-6 col-xxl-3 filter-item blog-content" data-category="blog-content">
                        <div class="card card-full shadow-none">
                            <div class="card-body">
                                <div class="media media-rg media-middle media-circle text-primary bg-primary bg-opacity-20 mb-3">
                                    <em class="{{ $template->icon }}"></em>
                                </div>
                                <a href="{{ route('templates.edit', $template->id) }}"> 
                                <h5 class="fs-4 fw-medium">{{ $template->title }}</h5>
                                <p class="small text-light line-clamp-2">{{ $template->description }}</p>
                                </a>
                            </div>
                        </div><!-- .card -->
                    </div>
                    @endforeach
                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
@endsection