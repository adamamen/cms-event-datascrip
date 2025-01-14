<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\M_SendEmailCust;
use App\Models\M_MasterEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmailEventController extends Controller
{
    function index($page)
    {
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin(Auth::user()->username, Auth::user()->divisi);
        $userId      = !empty($user) ? $user[0]['id'] : '';
        $title       = str_replace('-', ' ', $titleUrl);
        $output      = ucwords($title);
        $listDivisi  = listDivisi();
        $data        = $page == 'cms' ? $this->cms() : $this->non_cms($masterEvent);

        return view('email_event.index', [
            'type_menu'   => 'email_event',
            'id'          => $userId,
            'page'        => $page,
            'data'        => $data,
            'titleUrl'    => $titleUrl,
            'masterEvent' => $masterEvent,
            'output'      => $output,
            'listDivisi'  => $listDivisi
        ]);
    }

    public function add_index($page)
    {
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin(Auth::user()->username, Auth::user()->divisi);
        $userId      = !empty($user) ? $user[0]['id'] : '';
        $listEvent   = $this->getListEvent($page);
        $status      = $page == 'cms' ? 0 : 1;

        return view('email_event.add', [
            'type_menu'   => 'email_event',
            'status'      => $status,
            'id'          => $userId,
            'titleUrl'    => $titleUrl,
            'page'        => $page,
            'masterEvent' => $masterEvent,
            'listEvent'   => $listEvent,
        ]);
    }

    function add(Request $request)
    {
        if ($request->page == "cms") {
            $count = M_SendEmailCust::where('type', 'CMS_Admin')->where('id_event', $request->event)->count();
        } else {
            $count = M_SendEmailCust::where('type', 'Event_Admin')->where('id_event', $request->event)->count();
        }

        if ($count > 0) {
            return response()->json(['message' => 'failed']);
        } else {
            DB::table('tbl_send_email_cust')->insert([
                'content'    => $request->content,
                'type'       => $request->type == "Registrasi" ? 'CMS_Admin' : 'Event_Admin',
                'created_at' => Carbon::now(),
                'created_by' => Auth::user()->username,
                'updated_at' => NULL,
                'updated_by' => NULL,
                'id_event'   => $request->event,
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    function edit($page, $id)
    {
        $data        = M_SendEmailCust::select('*')->where('id', $id)->first();
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin(Auth::user()->username, Auth::user()->divisi);
        $userId      = !empty($user) ? $user[0]['id'] : '';
        $listEvent   = $this->getListEvent($page);

        return view('email_event.edit', [
            'type_menu'   => 'email_event',
            'id'          => $userId,
            'listDivisi'  => !empty($listDivisi) ? $listDivisi : '',
            'titleUrl'    => $titleUrl,
            'page'        => $page,
            'data'        => $data,
            'masterEvent' => $masterEvent,
            'listEvent'   => $listEvent,
        ]);
    }

    function update(Request $request)
    {
        DB::table('tbl_send_email_cust')
            ->where('id', $request->id)
            ->update([
                'content'    => $request->content,
                'id_event'   => $request->event,
                'type'       => $request->type == "Registrasi" ? 'CMS_Admin' : 'Event_Admin',
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::user()->username,
            ]);

        return response()->json(['message' => 'success']);
    }

    private function cms()
    {
        if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0) {
            $data = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_email_cust.type', 'CMS_Admin')
                ->get();
        } else {
            $data = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_email_cust.type', 'CMS_Admin')
                ->where('tbl_master_event.company', Auth::user()->divisi)
                ->get();
        }

        return $data;
    }

    private function non_cms($masterEvent)
    {
        if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0) {
            $data = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_email_cust.type', 'Event_Admin')
                ->where('tbl_send_email_cust.id_event', $masterEvent[0]['id_event'])
                ->get();
        } else {
            $data = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_email_cust.type', 'Event_Admin')
                ->where('tbl_send_email_cust.id_event', $masterEvent[0]['id_event'])
                ->where('tbl_master_event.company', Auth::user()->divisi)
                ->get();
        }

        return $data;
    }

    private function getListEvent($page)
    {
        if ($page == "cms") {
            if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0) {
                $listEvent = M_MasterEvent::select('*')->where('status', 'A')->get();
            } else {
                $listEvent = M_MasterEvent::select('*')->where('company', Auth::user()->divisi)->where('status', 'A')->get();
            }
        } else {
            $listEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get();
        }

        return $listEvent;
    }
}
