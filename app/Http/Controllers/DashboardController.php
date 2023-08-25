<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use App\Models\M_AdminEvent;

class DashboardController extends Controller
{
    public function index($page)
    {
        $page = strtolower($page);
        $type_menu = 'dashboard';
        $masterEventCount = M_MasterEvent::count();
        $adminEventCount = DB::table('tbl_admin_event as A')
            ->join('tbl_master_event as B', 'A.event_id', '=', 'B.id_event')
            ->get();
        $divisiEvent = M_CompanyEvent::count();
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        
        if (!empty($masterEvent) || $page == "cms") {
            return view('dashboard.index', [
                'masterEvent' => empty($masterEvent) ? '' : $masterEvent,
                'type_menu' => $type_menu,
                'masterEventCount' => $masterEventCount,
                'divisiEvent' => $divisiEvent,
                'adminEventCount' => count($adminEventCount)
            ]);
        } else {
            return abort(404);
        }
    }
}
