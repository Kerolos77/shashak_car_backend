<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caption;
use App\Models\Service;
use Illuminate\Http\Request;

class CaptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Caption $caption)
    {
        $rows = $caption->with('service')->get();
        return view('admin.captions.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Service $service)
    {
        
        $services = $service->get();
        return view('admin.captions.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Caption::create($request->all());
        return redirect()->route('admin.captions.index')->with('success', __('app.created_successfully'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Caption $caption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($caption_id, Service $service)
    {
        $row = Caption::findOrFail($caption_id);
        $services = $service->get();
        return view('admin.captions.edit', compact('services', 'row'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $caption_id)
    {
        Caption::findOrFail($caption_id)->update($request->all());
        return redirect()->route('admin.captions.index')->with('success', __('app.deleted_successfully'));  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($caption_id)
    {
        Caption::findOrFail($caption_id)->delete();
        return redirect()->route('admin.captions.index')->with('success', __('app.deleted_successfully'));
    }
}
