<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterEventController extends Controller
{
    public function add(Request $request)
    {
        $namaEvent = $request->namaEvent;
        $startEvent = $request->startEvent;
        $endEvent = $request->endEvent;
        $deskripsi = $request->deskripsi;
        $lokasi = $request->lokasi;


        dd($request->all());
    }
}
