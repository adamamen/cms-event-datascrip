<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_AdminEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class AdminEventController extends Controller
{
    function index($page)
    {
        $type_menu = 'admin_event';
        $data = $this->query();
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();

        if (!empty($masterEvent) || $page == "cms") {
            return view('admin_event.index', [
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu
            ]);
        }
    }

    public function query()
    {
        $queryAdminEvent = DB::table('tbl_admin_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by admin_id) AS RowNumber'), 'admin_id', 'event_id', 'username', 'password', 'full_name', 'status', 'created_at', 'updated_at', 'password_encrypts')
            ->get();
        $queryMasterEvent =  M_MasterEvent::select('*')->get();

        if (!empty($queryAdminEvent) && !empty($queryMasterEvent)) {
            foreach ($queryAdminEvent as $admin) {
                foreach ($queryMasterEvent as $event) {
                    if ($admin->event_id == $event->id_event) {
                        $merge[] = [
                            'RowNumber' => $admin->RowNumber,
                            'admin_id' => $admin->admin_id,
                            'event_id' => $admin->event_id,
                            'username' => $admin->username,
                            'password' => $admin->password,
                            'full_name' => $admin->full_name,
                            'status' => $admin->status,
                            'title' => $event->title,
                            'password_encrypts' => Crypt::decryptString($admin->password_encrypts),
                            'updated_by' => $event->updated_by,
                            'updated_at' => $event->updated_at,
                            'created_by' => $event->created_by,
                            'created_at' => $event->created_at,
                        ];
                    }
                }
            }
        }
        // dd($merge);
        $merge = !empty($merge) ? $merge : [];

        return $merge;
    }

    function add_admin_index()
    {
        $type_menu = 'dashboard';
        $data = M_MasterEvent::select('*')->where('status', 'A')->get();

        return view('admin_event.add-admin-event', [
            'data' => $data,
            'type_menu' => $type_menu
        ]);
    }

    public function add(Request $request)
    {
        DB::table('tbl_admin_event')->insert([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'full_name' => $request->nama_lengkap,
            'event_id' => $request->event,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now(),
            'password_encrypts' => Crypt::encryptString($request->password)
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit($id)
    {
        $type_menu = 'dashboard';
        $data = M_AdminEvent::select('*')->where('admin_id', $id)->get();
        $event = M_MasterEvent::select('*')->get();

        foreach ($data as $value) {
            $val[] = [
                'admin_id' => $value->admin_id,
                'event_id' => $value->event_id,
                'username' => $value->username,
                'password' => $value->password,
                'full_name' => $value->full_name,
                'status' => $value->status,
                'created_at' => $value->created_at,
                'updated_at' => $value->updated_at,
                'password_encrypts' => Crypt::decryptString($value->password_encrypts),
            ];
        }

        return view('admin_event.edit-admin-event', [
            'data' => $val,
            'type_menu' => $type_menu,
            'event' => $event
        ]);
    }

    public function update(Request $request)
    {
        if ($request->event == NULL) {
            DB::table('tbl_admin_event')
                ->where('admin_id', $request->admin_id)
                ->update([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'full_name' => $request->nama_lengkap,
                    'status' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'password_encrypts' => Crypt::encryptString($request->password)
                ]);
        } else {
            DB::table('tbl_admin_event')
                ->where('admin_id', $request->admin_id)
                ->update([
                    'event_id' => $request->event,
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'full_name' => $request->nama_lengkap,
                    'status' => $request->status,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'password_encrypts' => Crypt::encryptString($request->password)
                ]);
        }

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_AdminEvent::find($id); // Fetch data based on ID

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
