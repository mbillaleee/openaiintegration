@extends('admin.dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
<div class="nk-content-body">
    <div class="nk-block-head nk-page-head">
        <div class="nk-block-head-between flex-wrap gap g-2">
            <div class="nk-block-head-content">
                <h2 class="display-6">Add Assistant</h2>
            </div>
            <div class="nk-block-head-content">
                <ul class="nk-block-tools">
                    <li><a class="btn btn-primary" href="{{ route('all.assistants') }}"><em class="icon ni ni-arrow-left"></em><span>Back</span></a></li>
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
     
     <form action="{{ route('chat.assistant.store') }}" method="post" enctype="multipart/form-data">
        @csrf   

     <div class="row g-3 gx-gs">
                    
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInputText1" class="form-label">Select chat assistant avater </label>
            <div class="form-control-wrap">
                <input type="file" name="avater" class="form-control  @error('avater') is-invalid @enderror" placeholder="Enter avater">
                @error('avater')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    

    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">Chat assistant name </label>
            <div class="form-control-wrap">
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"   placeholder="Enter name" >
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="role_description" class="form-label">Chat assistant role description </label>
            <div class="form-control-wrap">
                <input type="text" name="role_description" id="role_description" class="form-control  @error('role_description') is-invalid @enderror" placeholder="Enter template nuber" >
                @error('role_description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="welcome_message" class="form-label">Chat assistant welcome message </label>
            <div class="form-control-wrap">
                <input type="text" name="welcome_message" id="welcome_message" class="form-control  @error('welcome_message') is-invalid @enderror" placeholder="Enter template nuber" >
                @error('welcome_message')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="welcome_message" class="form-label">Chat assistant group </label>
            <div class="form-control-wrap">
                <select class="form-select" id="category" name="category" aria-label="Default select example">
                    <option selected="">Open this select menu</option>
                    <option value="Business">Business</option>
                    <option value="Education">Education</option>
                    <option value="Health">Health</option>
                </select>
                @error('welcome_message')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="is_active" class="form-label">Active chat assistant </label>
            <div class="form-control-wrap">
                <input type="checkbox" name="is_active" id="is_active" class=" @error('is_active') is-invalid @enderror"  value="1">
                @error('is_active')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="instructions" class="form-label">Chat instruction </label>
            <div class="form-control-wrap">
                <textarea placeholder="Explain in details what AI Chat Assistant Needs to do..." name="instructions" class="form-control" id="instructions" rows="3"></textarea>
                @error('instructions')
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