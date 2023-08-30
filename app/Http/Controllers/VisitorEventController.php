<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_VisitorEvent;
use App\Models\M_User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use Mockery\Undefined;

class VisitorEventController extends Controller
{
    function index($page)
    {
        $type_menu = 'visitor_event';
        $data = $this->query($page);
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        if (!empty($masterEvent) || $page == "cms") {
            return view('visitor_event.index', [
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu,
                'titleUrl' => $titleUrl,
                'pages' => $page,
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

    public function add_visitor_index($page)
    {
        $type_menu = 'visitor_event';
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        if ($page == "cms") {
            $data = M_MasterEvent::select('*')->where('status', 'A')->get();
        } else {
            $data = M_MasterEvent::select('*')->where('title_url', $page)->where('status', 'A')->get();
        }

        return view('visitor_event.add-visitor-event', [
            'id' => $userId,
            'titleUrl' => $titleUrl,
            'masterEvent' => $masterEvent,
            'data' => $data,
            'type_menu' => $type_menu
        ]);
    }

    public function add(Request $request)
    {
        $query = M_VisitorEvent::select('*')
            ->where('event_id', $request->namaEvent)
            ->where('ticket_no', $request->noTiket)
            ->get();

        if (!$query->isEmpty()) {
            return response()->json(['message' => 'failed']);
        } else {
            DB::table('tbl_visitor_event')->insert([
                'event_id' => $request->namaEvent,
                'ticket_no' => $request->noTiket,
                'registration_date' => $request->tanggalRegistrasi,
                'full_name' => $request->namaLengkap,
                'address' => $request->alamat,
                'email' => $request->email,
                'mobile' => $request->noHandphone,
                'created_at' => Carbon::now(),
                'created_by' => $request->username,
                'updated_at' => Carbon::now(),
                'updated_by' => $request->username,
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function edit()
    {
        $page = request('page');
        $id = request('id');
        $type_menu = 'visitor_event';
        $data = M_VisitorEvent::select('*')->where('id', $id)->get();
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        if ($page == 'cms') {
            $event = M_MasterEvent::select('*')->where('status', 'A')->get()->toArray();
        } else {
            $event = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        }

        if (!empty($masterEvent) || $page == "cms") {
            return view('visitor_event.edit-visitor-event', [
                'titleUrl' => $titleUrl,
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu,
                'event' => $event
            ]);
        } else {
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        if ($request->noTiket != $request->noTiketBefore) {
            $q = M_VisitorEvent::select('*')->where('ticket_no', $request->noTiket)->where('event_id', $request->namaEvent)->get()->toArray();

            if (!empty($q)) {
                return response()->json(['message' => 'failed']);
            } else {
                DB::table('tbl_visitor_event')
                    ->where('id', $request->id)
                    ->update([
                        'event_id' => $request->namaEvent,
                        'ticket_no' => $request->noTiket,
                        'registration_date' => $request->tanggalRegistrasi,
                        'full_name' => $request->namaLengkap,
                        'address' => $request->alamat,
                        'email' => $request->email,
                        'mobile' => $request->noHandphone,
                        'updated_at' => Carbon::now(),
                        'updated_by' => $request->username,
                    ]);

                return response()->json(['message' => 'success']);
            }
        } else {
            DB::table('tbl_visitor_event')
                ->where('id', $request->id)
                ->update([
                    'event_id' => $request->namaEvent,
                    'ticket_no' => $request->noTiket,
                    'registration_date' => $request->tanggalRegistrasi,
                    'full_name' => $request->namaLengkap,
                    'address' => $request->alamat,
                    'email' => $request->email,
                    'mobile' => $request->noHandphone,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->username,
                ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function delete($id)
    {
        $data = M_VisitorEvent::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
