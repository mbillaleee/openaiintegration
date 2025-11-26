@extends('client.user_dashboard')


@section('client')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="nk-content-inner">
<div class="nk-content-body">
    <div class="nk-block-head nk-page-head">
        <div class="nk-block-head-between">
            <div class="nk-block-head-content">
                <h2 class="display-6">Personal Profile </h2>
                 
            </div>
        </div>
    </div><!-- .nk-page-head -->
    <div class="nk-block">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-head-content">
               
            </div>
        </div><!-- .nk-block-head -->
        <div class="card shadown-none">
            <div class="card-body">
     
     <form action="{{ route('user.password.update') }}" method="post" enctype="multipart/form-data">
        @csrf   

        <div class="row g-3 gx-gs">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="old_password" class="form-label">Old password </label>
                    <div class="form-control-wrap">
                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" placeholder="Enter old password">
                        @error('old_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="new_password" class="form-label">New password </label>
                    <div class="form-control-wrap">
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Enter new password">
                        @error('new_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Confirm password </label>
                    <div class="form-control-wrap">
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Enter password confirmation">
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