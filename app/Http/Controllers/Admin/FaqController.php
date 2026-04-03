<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('faq_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faqs = Faq::latest()->paginate(10);

        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        abort_if(Gate::denies('faq_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('faq_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'enable' => 'boolean',
        ]);

        $validated['enable'] = $request->has('enable');

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully');
    }

    public function show(Faq $faq)
    {
        abort_if(Gate::denies('faq_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faq.show', compact('faq'));
    }

    public function edit(Faq $faq)
    {
        abort_if(Gate::denies('faq_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        abort_if(Gate::denies('faq_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'enable' => 'boolean',
        ]);

        $validated['enable'] = $request->has('enable');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully');
    }

    public function destroy(Faq $faq)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('faq_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:faq,id',
        ]);

        Faq::whereIn('id', $request->ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}