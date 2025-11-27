@extends('client.user_dashboard')

@section('client')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-page-head">
            <h2>Upgrade your plan</h2>
        </div>
        <div class="nk-block">
            <div class="card">
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('user.process.checkout') }}" method="post" >
                        @csrf
                        <div class="form-group md-3">
                            <label for="plan_id">Select Plan</label>
                            <select name="plan_id" id="plan_id" class="form-control" required>
                                <option value="">Select Plan</option>
                                @foreach($allPlans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }} / month ({{ $plan->monthly_word_usage }} words)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="account_holder">Account holder name</label>
                            <input type="text" name="account_holder" id="account_holder" class="form-control">
                         </div>

                        <div class="form-group mb-3">
                            <label for="bank_name">Bank name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control">
                         </div>

                        <div class="form-group mb-3">
                            <label for="account_number">Account number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control">
                         </div>

                         <button type="submit" class="btn btn-primary btn-sm">Submit to bank transfer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection