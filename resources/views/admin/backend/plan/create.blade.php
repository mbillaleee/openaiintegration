@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
<div class="nk-content-body">
    <div class="nk-block-head nk-page-head">
        <div class="nk-block-head-between flex-wrap gap g-2">
            <div class="nk-block-head-content">
                <h2 class="display-6">Add plan</h2>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools">
                    <li><a class="btn btn-primary" href="{{ route('plans.index') }}"><em class="icon ni ni-arrow-left"></em><span>Back</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="nk-block">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-head-content">
               
            </div>
        </div><!-- .nk-block-head -->
        <div class="card shadown-none">
            <div class="card-body">
     
     <form action="{{ route('plans.store') }}" method="post" enctype="multipart/form-data">
        @csrf   

     <div class="row g-3 gx-gs">
                    
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInputText1" class="form-label">Name </label>
            <div class="form-control-wrap">
                <input type="text" name="name" class="form-control  @error('name') is-invalid @enderror" placeholder="Enter plan name">
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="monthly_word_usage" class="form-label">Monthly world limit </label>
            <div class="form-control-wrap">
                <input type="text" name="monthly_word_usage" id="monthly_word_usage" class="form-control @error('monthly_word_usage') is-invalid @enderror"   placeholder="Enter monthly world limit" >
                @error('monthly_word_usage')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="price" class="form-label">Price </label>
            <div class="form-control-wrap">
                <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror"   placeholder="Enter price" >
                @error('price')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="templates" class="form-label">Template </label>
            <div class="form-control-wrap">
                <input type="number" name="templates" id="templates" class="form-control  @error('templates') is-invalid @enderror" placeholder="Enter template nuber" >
                @error('templates')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>



          
    <div class="col-lg-12 col-xl-12">
<button type="submit" class="btn btn-secondary">Save Changes</button> 
    </div>
            
                    
    </div>
    </form> 



            </div><!-- .card-body -->
        </div><!-- .card -->
    </div><!-- .nk-block -->
     


</div>
</div>




@endsection