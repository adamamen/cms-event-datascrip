<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\M_SendWaCust;
use App\Models\M_MasterEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WhatsappEventController extends Controller
{
    function index($page)
    {
        $type_menu   = 'whatsapp_event';
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $title       = str_replace('-', ' ', $titleUrl);
        $output      = ucwords($title);
        if ($page == "cms") {
            // $data = M_SendWaCust::select('*')->where('type', 'CMS_Admin')->get();
            $data = M_SendWaCust::select('tbl_send_wa_cust.id', 'tbl_send_wa_cust.content', 'tbl_send_wa_cust.type', 'tbl_send_wa_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_wa_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_wa_cust.type', 'CMS_Admin')
                ->get();
        } else {
            // $data = M_SendWaCust::select('*')->where('type', 'Event_Admin')->get();
            $data = M_SendWaCust::select('tbl_send_wa_cust.id', 'tbl_send_wa_cust.content', 'tbl_send_wa_cust.type', 'tbl_send_wa_cust.id_event', 'tbl_master_event.title')
                ->join('tbl_master_event', 'tbl_send_wa_cust.id_event', '=', 'tbl_master_event.id_event')
                ->where('tbl_send_wa_cust.type', 'Event_Admin')
                ->where('tbl_send_wa_cust.id_event', $masterEvent[0]['id_event'])
                ->get();
        }

        return view('whatsapp_event.index', [
            'id'          => $userId,
            'type_menu'   => $type_menu,
            'page'        => $page,
            'data'        => $data,
            'titleUrl'    => $titleUrl,
            'masterEvent' => $masterEvent,
            'output'      => $output,
        ]);
    }

    public function add_index($page)
    {
        $type_menu   = 'whatsapp_event';
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin();
        $userId      = $user[0]['id'];

        if ($page == "cms") {
            $listEvent = M_MasterEvent::select('*')->where('status', 'A')->get();
            $status = 0;
        } else {
            $listEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get();
            $status = 1;
        }

        return view('whatsapp_event.add', [
            'status'      => $status,
            'id'          => $userId,
            'type_menu'   => $type_menu,
            'listDivisi'  => !empty($listDivisi) ? $listDivisi : '',
            'titleUrl'    => $titleUrl,
            'page'        => $page,
            'masterEvent' => $masterEvent,
            'listEvent'   => $listEvent,
        ]);
    }

    function add(Request $request)
    {
        if ($request->page == "cms") {
            $count = M_SendWaCust::select('*')->where('type', 'CMS_Admin')->where('id_event', $request->event)->count();
        } else {
            $count = M_SendWaCust::select('*')->where('type', 'Event_Admin')->where('id_event', $request->event)->count();
        }

        if ($count > 0) {
            return response()->json(['message' => 'failed']);
        } else {
            DB::table('tbl_send_wa_cust')->insert([
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
        $type_menu   = 'whatsapp_event';
        $data        = M_SendWaCust::select('*')->where('id', $id)->first();
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        if ($page == "cms") {
            $listEvent   = M_MasterEvent::select('*')->where('status', 'A')->get();
        } else {
            $listEvent   = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get();
        }

        return view('whatsapp_event.edit', [
            'id'          => $userId,
            'type_menu'   => $type_menu,
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
        DB::table('tbl_send_wa_cust')
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
}
