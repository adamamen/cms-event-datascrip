<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index($page)
    {
        $page = strtolower($page);
        $type_menu = 'dashboard';
        $totalVisitor = $this->query($page);
        $divisiEvent = $this->divisiEvent($page);
        $divisiEventCms = M_CompanyEvent::count();
        $totalDivisi = $page == "cms" ? $divisiEventCms : count($divisiEvent);
        $masterEvent = masterEvent($page);
        $masterEventCms = M_MasterEvent::count();
        $totalEvent = $page == "cms" ? $masterEventCms : count($masterEvent);
        $user = userAdmin();
        $totalAdmin = count(adminEvent($page));
        $userId = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            Log::info('User berada di menu Dashboard ' . strtoupper($page), ['username' => Auth::user()->username]);
            return view('dashboard.index', [
                'id' => $userId,
                'masterEvent' => empty($masterEvent) ? '' : $masterEvent,
                'type_menu' => $type_menu,
                'totalEvent' => $totalEvent,
                'totalDivisi' => $totalDivisi,
                'totalVisitor' => count($totalVisitor),
                'totalAdmin' => $totalAdmin
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function divisiEvent($page)
    {
        $q = DB::table('tbl_company_event as A')
            ->join('tbl_master_event as B', 'A.id', '=', 'B.company')
            ->where('title_url', $page)
            ->get()->toArray();

        return $q;
    }

    public function query($page)
    {
        $queryVisitorEvent = visitorEvent();
        if ($page == "cms") {
            $queryMasterEvent = masterEvent_1();
        } else {
            $queryMasterEvent =  masterEvent_2($page);
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
