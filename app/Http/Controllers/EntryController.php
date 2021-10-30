<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowEntryRequest;
use App\Http\Requests\StoreEntryRequest;
use App\Models\Entry;

class EntryController extends Controller
{
    public function store(StoreEntryRequest $request)
    {
        return Entry::create($request->all());
    }

    public function show(ShowEntryRequest $request, $key)
    {
        return Entry::showValue($key, $request->timestamp);
    }

    public function allRecords()
    {
        return Entry::all();
    }
}
