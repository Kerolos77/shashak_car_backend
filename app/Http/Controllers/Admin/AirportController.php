<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AirportController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('airport_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.airport.index');
    }

    public function create()
    {
        abort_if(Gate::denies('airport_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.airport.create');
    }

    public function edit(Airport $airport)
    {
        abort_if(Gate::denies('airport_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.airport.edit', compact('airport'));
    }

    public function show(Airport $airport)
    {
        abort_if(Gate::denies('airport_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.airport.show', compact('airport'));
    }
}
