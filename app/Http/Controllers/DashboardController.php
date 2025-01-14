<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use App\Models\M_SendWaCust;
use App\Models\M_SendEmailCust;
use App\Models\M_MasterUser;
use App\Models\M_AccessUser;

class DashboardController extends Controller
{
    public function index($page)
    {
        $page        = strtolower($page);
        $totalDivisi = $page === "cms" ? M_CompanyEvent::count() : count($this->divisiEvent($page));
        $masterEvent = masterEvent($page);
        $user        = userAdmin(Auth::user()->username, Auth::user()->divisi);

        if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0) {
            $masterEventCms  = M_MasterEvent::count();
            $totalWhatsapp   = M_SendWaCust::where('type', 'CMS_Admin')->count();
            $totalEmail      = M_SendEmailCust::where('type', 'CMS_Admin')->count();
            $totalMasterUser = M_MasterUser::count();
            $totalUserAccess = M_AccessUser::count();
        } else {
            $masterEventCms  = M_MasterEvent::where('company', Auth::user()->divisi)->count();
            $totalMasterUser = M_MasterUser::where('id_divisi', Auth::user()->divisi)->count();

            if ($page === "cms") {
                $totalWhatsapp = M_SendWaCust::join('tbl_master_event', 'tbl_send_wa_cust.id_event', '=', 'tbl_master_event.id_event')
                    ->where('tbl_send_wa_cust.type', 'CMS_Admin')
                    ->where('tbl_master_event.company', Auth::user()->divisi)
                    ->count();

                $totalEmail = M_SendEmailCust::join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                    ->where('tbl_send_email_cust.type', 'CMS_Admin')
                    ->where('tbl_master_event.company', Auth::user()->divisi)
                    ->count();
            } else {
                $eventId = $masterEvent[0]['id_event'] ?? null;

                if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0) {
                    $totalWhatsapp = $this->countEventNotifications('Event_Admin', $eventId);
                    $totalEmail    = $this->countEventEmails('Event_Admin', $eventId);
                } else {
                    $totalWhatsapp = $this->countEventNotifications('Event_Admin', $eventId, Auth::user()->divisi);
                    $totalEmail    = $this->countEventEmails('Event_Admin', $eventId, Auth::user()->divisi);
                }
            }
        }

        if (!empty($masterEvent) || $page === "cms") {
            return view('dashboard.index', [
                'id'                 => !empty($user) ? $user[0]['id'] : '',
                'masterEvent'        => $masterEvent ?: '',
                'type_menu'          => 'dashboard',
                'totalEvent'         => $page === "cms" ? $masterEventCms : count($masterEvent),
                'totalDivisi'        => $totalDivisi,
                'totalVisitor'       => count($this->query($page)),
                'totalAdmin'         => count(adminEvent($page)),
                'totalWhatsapp'      => $totalWhatsapp,
                'totalEmail'         => $totalEmail,
                'totalReportVisitor' => 0,
                'totalMasterUser'    => $totalMasterUser,
                'totalUserAccess'    => !empty($totalUserAccess) ? $totalUserAccess : '0',
            ]);
        } else {
            return view('error.error-404');
        }
    }

    private function countEventNotifications($type, $eventId, $company = null)
    {
        $query = M_SendWaCust::join('tbl_master_event', 'tbl_send_wa_cust.id_event', '=', 'tbl_master_event.id_event')
            ->where('tbl_send_wa_cust.type', $type)
            ->where('tbl_send_wa_cust.id_event', $eventId);

        if ($company) {
            $query->where('tbl_master_event.company', $company);
        }

        return $query->count();
    }

    private function countEventEmails($type, $eventId, $company = null)
    {
        $query = M_SendEmailCust::join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
            ->where('tbl_send_email_cust.type', $type)
            ->where('tbl_send_email_cust.id_event', $eventId);

        if ($company) {
            $query->where('tbl_master_event.company', $company);
        }

        return $query->count();
    }


    private function divisiEvent($page)
    {
        $q = DB::table('tbl_company_event as A')
            ->join('tbl_master_event as B', 'A.id', '=', 'B.company')
            ->where('title_url', $page)
            ->get()->toArray();

        return $q;
    }

    private function query($page)
    {
        $queryVisitorEvent = visitorEvent();

        if ($page == "cms") {
            $queryMasterEvent = masterEvent_1();
        } else {
            $queryMasterEvent = masterEvent_2($page);
        }

        if (!empty($queryVisitorEvent) && !empty($queryMasterEvent)) {
            foreach ($queryVisitorEvent as $visitor) {
                foreach ($queryMasterEvent as $event) {
                    if ($visitor->event_id == $event->id_event) {
                        $merge[] = [
                            'id'                => $visitor->id,
                            'event_id'          => $visitor->event_id,
                            'RowNumber'         => $visitor->RowNumber,
                            'title'             => $event->title,
                            'full_name'         => $visitor->full_name,
                            'mobile'            => $visitor->mobile,
                            'ticket_no'         => $visitor->ticket_no,
                            'email'             => $visitor->email,
                            'registration_date' => $visitor->registration_date,
                            'address'           => $visitor->address,
                            'created_by'        => $visitor->created_by,
                            'created_at'        => $visitor->created_at,
                            'updated_by'        => $visitor->updated_by,
                            'updated_at'        => $visitor->updated_at,
                            'title_url'         => $event->title_url,
                        ];
                    }
                }
            }
        }

        $merge = !empty($merge) ? $merge : [];

        return $merge;
    }
}
