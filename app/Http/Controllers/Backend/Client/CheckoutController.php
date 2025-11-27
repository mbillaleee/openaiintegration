<?php

namespace App\Http\Controllers\Backend\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BillingHistory;
use App\Models\Plan;
use Barryvdh\DomPDF\Facade\Pdf;

class CheckoutController extends Controller
{
    public function userCheckout()
    {
        $user = Auth::user();
        $userPlan = $user->plan;
        $allPlans = Plan::where('price', '>', 0)->get();
        return view('client.backend.checkout.user_checkout', compact('allPlans'));
    }

    public function processCheckout(Request $request)
    {
        // dd('ok');
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'bank_name' => 'required|string',
        ]);

        $user = Auth::user();
        $newPlan = Plan::find($request->plan_id);

        $billing = new BillingHistory();
        $billing->user_id = $user->id;
        $billing->plan_id = $newPlan->id;
        $billing->payment_date = now();
        $billing->total =  $newPlan->price;
        $billing->bank_name =  $request->bank_name;
        $billing->account_holder =   $request->account_holder;
        $billing->account_number =  $request->account_number;
        $billing->save();

        // Update user plan
        $user->plan_id = $newPlan->id;
        $user->current_word_usage = $user->current_word_usage + $newPlan->monthly_word_usage;
        $user->save();

        $notification = array(
            'message' => 'Plan upgreate submitted',
            'alert-type' =>'success'
        );
        
        return redirect()->route('payment.success')->with($notification);
    }

    public function paymentSuccess()
    {
        return view('client.backend.checkout.payment_success');
    }

    public function invoiceGenerate($id)
    {
        $billing = BillingHistory::with('user','plan')->findOrFail($id);
        $pdf = Pdf::loadView('client.backend.checkout.invoice', compact('billing'));

        return $pdf->download('invoice-' . $billing->id. '.pdf');
    }
}
