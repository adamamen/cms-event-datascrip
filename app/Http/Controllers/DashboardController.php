<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use App\Models\M_User;

class DashboardController extends Controller
{
    public function index($page)
    {
        $page = strtolower($page);
        $type_menu = 'dashboard';
        $adminEventCount = $this->query($page);
        $divisiEvent = DB::table('tbl_company_event as A')
            ->join('tbl_master_event as B', 'A.id', '=', 'B.company')
            ->where('title_url', $page)
            ->get();
        $divisiEventCms = M_CompanyEvent::count();
        $divisiEventCount = $page == "cms" ? $divisiEventCms : count($divisiEvent);
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $masterEventCms = M_MasterEvent::count();
        $masterEventCount = $page == "cms" ? $masterEventCms : count($masterEvent);
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('dashboard.index', [
                'id' => $userId,
                'masterEvent' => empty($masterEvent) ? '' : $masterEvent,
                'type_menu' => $type_menu,
                'masterEventCount' => $masterEventCount,
                'divisiEvent' => $divisiEventCount,
                'adminEventCount' => count($adminEventCount)
            ]);
        } else {
            return abort(404);
        }
    }

    public function query($page)
    {
        $queryVisitorEvent = DB::table('tbl_visitor_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'event_id', 'registration_date', 'full_name', 'address', 'email', 'mobile', 'created_at', 'ticket_no', 'created_by', 'updated_by', 'updated_at')
            ->get();
        if ($page == "cms") {
            $queryMasterEvent =  M_MasterEvent::select('*')->get();
        } else {
            $queryMasterEvent =  M_MasterEvent::select('*')->where('title_url', $page)->get();
        }

        if (!empty($queryVisitorEvent) && !empty($queryMasterEvent)) {
            foreach ($queryVisitorEvent as $visitor) {
                foreach ($queryMasterEvent as $event) {
                    if ($visitor->event_id == $event->id_event) {
                        $merge[] = [
                            'id' => $visitor->id,
                            'event_id' => $visitor->event_id,
                            'RowNumber' => $visitor->RowNumber,
                            'title' => $event->title,
                            'full_name' => $visitor->full_name,
                            'mobile' => $visitor->mobile,
                            'ticket_no' => $visitor->ticket_no,
                            'email' => $visitor->email,
                            'registration_date' => $visitor->registration_date,
                            'address' => $visitor->address,
                            'created_by' => $visitor->created_by,
                            'created_at' => $visitor->created_at,
                            'updated_by' => $visitor->updated_by,
                            'updated_at' => $visitor->updated_at,
                            'title_url' => $event->title_url,
                        ];
                    }
                }
            }
        }

        $merge = !empty($merge) ? $merge : [];

        return $merge;
    }
}
