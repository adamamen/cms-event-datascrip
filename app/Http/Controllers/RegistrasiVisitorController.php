<?php

namespace App\Http\Controllers;

use App\Models\M_MasterEvent;

class RegistrasiVisitorController extends Controller
{
    public function index_register($page, $page1)
    {
        // $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page1)->get()->toArray();
        $id = '';
        $type_menu = '';
        $data = M_MasterEvent::select('*')->where('title_url', $page1)->where('status', 'A')->get();
        // dd($data);

        // if ($page == "visitor-event" && !empty($masterEvent)) {
        return view('visitor_event.register', [
            // 'masterEvent' => $data,
            // 'id' => $id,
            // 'type_menu' => $type_menu,
            // 'titleUrl' => $page1,
            // 'data' => $data
        ]);
        // } else {
        //     return view('error.error-404');
        // }
    }
}
