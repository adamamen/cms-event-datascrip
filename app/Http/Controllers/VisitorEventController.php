<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_VisitorEvent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use Mockery\Undefined;

class VisitorEventController extends Controller
{
    function index($page)
    {
        // dd(Auth::user());
        $type_menu = 'visitor_event';
        $data = $this->query();
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();

        if (!empty($masterEvent) || $page == "cms") {
            return view('visitor_event.index', [
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu
            ]);
        }
    }

    public function query()
    {
        $queryVisitorEvent = DB::table('tbl_visitor_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'event_id', 'registration_date', 'full_name', 'address', 'email', 'mobile', 'created_at', 'ticket_no', 'created_by', 'updated_by', 'updated_at')
            ->get();
        $queryMasterEvent =  M_MasterEvent::select('*')->get();

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
        $type_menu = 'dashboard';
        $data = M_MasterEvent::select('*')->get();

        if ($page == "cms") {
            return view('visitor_event.add-visitor-event', [
                'data' => $data,
                'type_menu' => $type_menu
            ]);
        }
    }

    public function add(Request $request)
    {
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

    public function edit($id)
    {
        $type_menu = 'dashboard';
        $data = M_VisitorEvent::select('*')->where('id', $id)->get();
        $event = M_MasterEvent::select('*')->get();

        return view('visitor_event.edit-visitor-event', [
            'data' => $data,
            'type_menu' => $type_menu,
            'event' => $event
        ]);
    }

    public function update(Request $request)
    {
        if ($request->namaEvent == 'undefined') {
            DB::table('tbl_visitor_event')
                ->where('id', $request->id)
                ->update([
                    'ticket_no' => $request->noTiket,
                    'registration_date' => $request->tanggalRegistrasi,
                    'full_name' => $request->namaLengkap,
                    'address' => $request->alamat,
                    'email' => $request->email,
                    'mobile' => $request->noHandphone,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->username,
                ]);
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
        }

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_VisitorEvent::find($id); // Fetch data based on ID

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
