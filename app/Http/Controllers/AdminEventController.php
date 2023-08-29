<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    function index($page)
    {
        $type_menu = 'admin_event';
        $data = $this->query($page);
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('admin_event.index', [
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu,
                'pages' => $page
            ]);
        } else {
            return abort(404);
        }
    }

    public function query($page)
    {
        $queryAdminEvent = DB::table('tbl_user')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'event_id', 'username', 'password', 'full_name', 'status', 'created_at', 'updated_at', 'password_encrypts')
            ->where('event_id', '<>', '0')
            ->get();
        if ($page == "cms") {
            $queryMasterEvent =  M_MasterEvent::select('*')->get();
        } else {
            $queryMasterEvent =  M_MasterEvent::select('*')->where('title_url', $page)->get();
        }

        if (!empty($queryAdminEvent) && !empty($queryMasterEvent)) {
            foreach ($queryAdminEvent as $admin) {
                foreach ($queryMasterEvent as $event) {
                    if ($admin->event_id == $event->id_event) {
                        $merge[] = [
                            'RowNumber' => $admin->RowNumber,
                            'admin_id' => $admin->id,
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
                            'title_url' => $event->title_url,
                        ];
                    }
                }
            }
        }
        $merge = !empty($merge) ? $merge : [];

        return $merge;
    }

    function add_admin_index($page)
    {
        $type_menu = 'admin_event';
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        if ($page == "cms") {
            $data = M_MasterEvent::select('*')->where('status', 'A')->get();
        } else {
            $data = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get();
        }

        return view('admin_event.add-admin-event', [
            'id' => $userId,
            'titleUrl' => $titleUrl,
            'data' => $data,
            'masterEvent' => $masterEvent,
            'type_menu' => $type_menu
        ]);
    }

    public function add(Request $request)
    {
        // dd($request->all());
        DB::table('tbl_user')->insert([
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

    public function edit()
    {
        $page = request('page');
        $id = request('id');
        dd($page);
        $type_menu = 'admin_event';
        $data = M_User::select('*')->where('id', $id)->get();
        $event = M_MasterEvent::select('*')->get();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        foreach ($data as $value) {
            $val[] = [
                'admin_id' => $value->id,
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

        if (!empty($masterEvent) || $page == "cms") {
            return view('admin_event.edit-admin-event', [
                'titleUrl' => $titleUrl,
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $val,
                'type_menu' => $type_menu,
                'event' => $event
            ]);
        }
    }

    public function update(Request $request)
    {
        if ($request->event == NULL) {
            DB::table('tbl_user')
                ->where('id', $request->admin_id)
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
            DB::table('tbl_user')
                ->where('id', $request->admin_id)
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
        $data = M_User::find($id); // Fetch data based on ID

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
