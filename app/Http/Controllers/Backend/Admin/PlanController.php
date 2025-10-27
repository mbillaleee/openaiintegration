<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::latest()->get();
        return view('admin.backend.plan.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.backend.plan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'monthly_word_usage' => 'required|numeric',
            'price' => 'required|numeric',
            'templates' => 'required|numeric',
        ]);

        Plan::create([
            'name' =>$request->name,
            'monthly_word_usage' =>$request->monthly_word_usage,
            'price' =>$request->price,
            'templates' =>$request->templates,
        ]);

        $notification = array(
            'message' => 'Plan created successfully',
            'alert-type' =>'success'
        );
        
        return redirect()->route('plans.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $plan = Plan::findOrFail($id);
        return view('admin.backend.plan.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'name' => 'required',
            'monthly_word_usage' => 'required|numeric',
            'price' => 'required|numeric',
            'templates' => 'required|numeric',
        ]);

        Plan::find($id)->update([
            'name' =>$request->name,
            'monthly_word_usage' =>$request->monthly_word_usage,
            'price' =>$request->price,
            'templates' =>$request->templates,
        ]);

        $notification = array(
            'message' => 'Plan updared successfully',
            'alert-type' =>'success'
        );
        
        return redirect()->route('plans.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // dd($id);
        Plan::find($id)->delete();

        $notification = array(
            'message' => 'Plan deleted successfully',
            'alert-type' =>'success'
        );
        
        return redirect()->route('plans.index')->with($notification);
    }
}
