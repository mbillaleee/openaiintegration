@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
<div class="nk-content-body">
    <div class="nk-block-head nk-page-head">
        <div class="nk-block-head-between flex-wrap gap g-2">
            <div class="nk-block-head-content">
                <h2 class="display-6">Update Slider</h2>
            </div>
            <div class="nk-block-head-content">
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
     
     <form action="{{ route('updte.slider', $slider->id) }}" method="post" enctype="multipart/form-data">
        @csrf   

     <div class="row g-3 gx-gs">
                    
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="form-label">Slider title </label>
            <div class="form-control-wrap">
                <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" placeholder="Enter plan title" value="{{ $slider->title }}">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="form-label">Description </label>
            <div class="form-control-wrap">
                <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror"   placeholder="Enter monthly world limit"  value="{{ $slider->description }}">
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="link" class="form-label">Link </label>
            <div class="form-control-wrap">
                <input type="text" name="link" id="link" class="form-control @error('link') is-invalid @enderror"   placeholder="Enter link"  value="{{ $slider->link }}">
                @error('link')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="image" class="form-label">Slider Image </label>
            <div class="form-control-wrap">
                <input type="file" name="image" id="image" class="form-control  @error('image') is-invalid @enderror" placeholder="Enter template nuber" >
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInputText1" class="form-label">  </label>
            <div class="form-control-wrap">
                <img id="showImage" src="{{ (!empty($slider->image)) ? url($slider->image) : url('upload/no_image.jpg') }}" class="rounded-circle avatar-xl img-thumbnail float-start" style="width: 80px; height:80px;">
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        })
    })

</script>



@endsection