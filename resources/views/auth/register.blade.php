<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.png">
    <title>Register - CopyGen - AI Writer</title>
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/css/style.css?v1.0.0">
</head>

<body class="nk-body ">
    <div class="nk-app-root " data-sidebar-collapse="lg">
        <div class="nk-main">
            <div class="nk-wrap has-shape flex-column">
                <div class="nk-shape bg-shape-blur-a start-0 top-0"></div>
                <div class="nk-shape bg-shape-blur-b end-0 bottom-0"></div>
                <div class="text-center pt-5">
                    <a href="index.html" class="logo-link">
                        <div class="logo-wrap">
                            <img class="logo-img logo-light" src="{{ asset('backend') }}/images/logo.png" srcset="{{ asset('backend') }}/images/logo2x.png 2x" alt="">
                            <img class="logo-img logo-dark" src="{{ asset('backend') }}/images/logo-dark.png" srcset="{{ asset('backend') }}/images/logo-dark2x.png 2x" alt="">
                            <img class="logo-img logo-icon" src="{{ asset('backend') }}/images/logo-icon.png" srcset="{{ asset('backend') }}/images/logo-icon2x.png 2x" alt="">
                        </div>
                    </a>
                </div>
                <div class="container p-2 p-sm-4 mt-auto">
                    <div class="row justify-content-center">
                        <div class="col-md-7 col-lg-5 col-xl-5 col-xxl-4">
                            <div class="nk-block">
                                <div class="nk-block-head text-center mb-4 pb-2">
                                    <div class="nk-block-head-content">
                                        <h1 class="nk-block-title mb-1">Log into Your Account</h1>
                                        <p class="small">Sign in to your account to customize your content generation settings and view your history.</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="name">Name</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control  @error('name') is-invalid @enderror" type="name" name="name" id="name" placeholder="Enter name address" />
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div><!-- .form-group -->
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="email">Email Address</label>
                                                <div class="form-control-wrap">
                                                    <input class="form-control  @error('email') is-invalid @enderror" type="email" name="email" id="email" placeholder="Enter email address" />
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div><!-- .form-group -->
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="password">Password</label>
                                                <div class="form-control-wrap">
                                                    <a href="password" class="password-toggle form-control-icon end" title="Toggle show/hide password">
                                                        <em class="icon ni ni-eye inactive"></em>
                                                        <em class="icon ni ni-eye-off active"></em>
                                                    </a>
                                                    <input class="form-control  @error('password') is-invalid @enderror" type="password" name="password" id="password" placeholder="Enter password" />
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div><!-- .form-group -->
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="password_confirmation">Confirm Password</label>
                                                <div class="form-control-wrap">
                                                    <a href="password_confirmation" class="password-toggle form-control-icon end" title="Toggle show/hide password">
                                                        <em class="icon ni ni-eye inactive"></em>
                                                        <em class="icon ni ni-eye-off active"></em>
                                                    </a>
                                                    <input class="form-control  @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" id="password_confirmation" placeholder="Enter password" />
                                                    @error('password_confirmation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div><!-- .form-group -->
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button class="btn btn-primary" type="submit">Register</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="samrator my-4 text-center">
                                    <span>Or sign in with </span>
                                </div>
                                <div class="text-center mt-3">
                                    <p class="small">Do you have an account? <a href="{{ route('login') }}">Login</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 @include('admin.body.footer')
            </div>
        </div>
    </div>
    <script src="{{ asset('backend') }}/assets/js/bundle.js?v1.0.0"></script>
    <script src="{{ asset('backend') }}/assets/js/scripts.js?v1.0.0"></script>
</body>

</html>