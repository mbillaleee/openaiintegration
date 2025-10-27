@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
<div class="nk-content-body">
    <div class="nk-block-head nk-page-head">
        <div class="nk-block-head-between flex-wrap gap g-2">
            <div class="nk-block-head-content">
                <h2 class="display-6">Add Template</h2>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools">
                    <li><a class="btn btn-primary" href="{{ route('templates.index') }}"><em class="icon ni ni-arrow-left"></em><span>Back</span></a></li>
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
     
     <form action="{{ route('templates.store') }}" method="post" enctype="multipart/form-data">
        @csrf   

     <div class="row g-3 gx-gs">
                    
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInputText1" class="form-label">Title </label>
            <div class="form-control-wrap">
                <input type="text" name="title" class="form-control  @error('title') is-invalid @enderror" placeholder="Enter plan title">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="form-label">Template Description </label>
            <div class="form-control-wrap">
                <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror"   placeholder="Enter monthly world limit" >
                @error('description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>



    <div class="col-md-6">
        <div class="form-group">
            <label for="category" class="form-label">Template Category </label>
            <div class="form-control-wrap">
                <select class="form-select" name="category" id="category" aria-label="Default select category">
                    <option selected="">Select Category</option>
                    <option value="Ads">Ads</option>
                    <option value="Artices">Artices</option>
                    <option value="Content">Content</option>
                    <option value="Ecommerce">Ecommerce</option>
                    <option value="Website">Website</option>
                    <option value="Social media">Social media</option>
                    <option value="Emails">Emails</option>
                    <option value="Marketing">Marketing</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="icon" class="form-label">Icon </label>
            <div class="form-control-wrap">
                <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror"  placeholder="(e.g., &lt;i class=&quot;fa-solid fa-book&quot;&gt;&lt;/i&gt;)"  >
                @error('icon')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked="">
            <label class="form-check-label" for="flexCheckChecked"> Activate Template </label>
        <input type="hidden" name="is_active" value="1">
        </div>
    </div>


    <div class="form-group">
        <div id="input-fields">
            <div class="row input-field-row">
              
                <div class="col-md-4">
                
                    <div class="form-group">
                        <label for="input_fields_0_title" class="form-label">Input Field Title * </label>
                        <div class="form-control-wrap">
                            <input type="text" name="input_fields[0][title]" id="input_fields_0_title" class="form-control" placeholder="Enter Input Field Title" required  >
                        </div>
                    </div> 
                </div>

                <div class="col-md-4">
                
                    <div class="form-group">
                        <label for="input_fields_0_description" class="form-label">Input Field Description * </label>
                        <div class="form-control-wrap">
                            <input type="text" name="input_fields[0][description]" id="input_fields_0_description" class="form-control" placeholder="Enter Input Field Description" required  >
                        </div>
                    </div> 
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="input_fields_0_type" class="form-label"> Field Type * </label>
                        <div class="form-control-wrap">
                            <select name="input_fields[0][type]" class="form-control" id="input_fields_0_type"  > 
                                <option value="text">Input Field</option>
                                <option value="textarea">Textarea Field</option> 
                            </select>
                        </div>
                    </div> 
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">  </label>
                        <div class="form-control-wrap">
                            <input type="hidden" name="input_fields[0][is_required]"    value="1">
                        </div>
                    </div> 
                </div> 
            </div> 
        </div> 
    </div>


    <div class="form-group mt-2">
        <label for="prompt">Custom Prompt</label>
        <textarea name="prompt" placeholder="Add Your Prompt Code" class="form-control"  rows="3"></textarea>
        <small>Write a 400 world aticale about {topic} with an introductions</small> <br>
        @error('prompt')
            <span class="text-danger">{{ $message }}</span>
        @enderror
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