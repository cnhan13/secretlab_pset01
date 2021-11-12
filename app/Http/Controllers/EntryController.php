<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowEntryRequest;
use App\Http\Requests\StoreEntryRequest;
use App\Models\Entry;
use Symfony\Component\HttpFoundation\Response;

class EntryController extends Controller
{
    public function store(StoreEntryRequest $request)
    {
        /** @var Entry $entry */
        $entries = Entry::createMultipleFromKeyValuePairs($request->all());
        $status = empty($entries) ? Response::HTTP_OK : Response::HTTP_CREATED;
        return response($entries, $status);
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
