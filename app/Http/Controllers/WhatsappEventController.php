<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\M_SendWaCust;
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
            $data = M_SendWaCust::select('*')->where('type', 'CMS_Admin')->get();
        } else {
            $data = M_SendWaCust::select('*')->where('type', 'Event_Admin')->get();
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

        return view('whatsapp_event.add', [
            'type_menu'   => $type_menu,
            'listDivisi'  => !empty($listDivisi) ? $listDivisi : '',
            'titleUrl'    => $titleUrl,
            'page'        => $page,
            'masterEvent' => $masterEvent,
        ]);
    }

    function add(Request $request)
    {
        if ($request->page == "cms") {
            $count = M_SendWaCust::select('*')->where('type', 'CMS_Admin')->count();
        } else {
            $count = M_SendWaCust::select('*')->where('type', 'Event_Admin')->count();
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

        return view('whatsapp_event.edit', [
            'type_menu'  => $type_menu,
            'listDivisi' => !empty($listDivisi) ? $listDivisi : '',
            'titleUrl'   => $titleUrl,
            'page'       => $page,
            'data'       => $data,
            'masterEvent' => $masterEvent,
        ]);
    }

    function update(Request $request)
    {
        DB::table('tbl_send_wa_cust')
            ->where('id', $request->id)
            ->update([
                'content'    => $request->content,
                'type'       => $request->type == "Registrasi" ? 'CMS_Admin' : 'Event_Admin',
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::user()->username,
            ]);

        return response()->json(['message' => 'success']);
    }
}
