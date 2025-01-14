<?php

namespace App\Http\Controllers;

use App\Models\M_AccessUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\M_CompanyEvent;
use App\Models\M_User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;

class UserAccessController extends Controller
{
    public function index($page)
    {
        $type_menu   = 'user_access';
        $data        = M_AccessUser::select('*')->get();
        $masterEvent = masterEvent($page);
        $user        = userAdmin(Auth::user()->username, Auth::user()->divisi);
        $userId      = !empty($user) ? $user[0]['id'] : '';

        return view('user_access.index', [
            'id'          => $userId,
            'masterEvent' => $masterEvent,
            'data'        => $data,
            'type_menu'   => $type_menu,
            'pages'       => $page
        ]);
    }

    public function add_user_access_index($page)
    {
        $type_menu   = 'user_access';
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $division    = M_CompanyEvent::select('*')->get();

        return view('user_access.add-access-user', [
            'type_menu' => $type_menu,
            'pages'     => $page,
            'titleUrl'  => $titleUrl,
            'division'  => $division,
        ]);
    }

    public function add(Request $request)
    {
        $namaDivisi  = M_CompanyEvent::select('*')->where('id', $request->division)->first();
        $ownerDivisi = M_CompanyEvent::select('*')->where('id', $request->division_owner)->first();

        DB::table('tbl_access_user')->insert([
            'id_divisi'         => $request->division,
            'nama_divisi'       => $namaDivisi->name,
            'id_divisi_owner'   => $request->division_owner,
            'nama_divisi_owner' => $ownerDivisi->name,
            'start_date'        => $request->start_date,
            'end_date'          => $request->end_date,
            'status'            => $request->status,
            'created_by'        => Auth::user()->username,
            'created_date'      => Carbon::now(),
            'update_by'         => NULL,
            'update_date'       => NULL,
            'created_at'        => Carbon::now(),
            'updated_at'        => NULL,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function edit($page, $id, $id_divisi)
    {
        $type_menu = 'user_access';
        $data      = M_AccessUser::select('*')->where('id', $id)->get();
        $division  = M_CompanyEvent::all();
        $user      = M_User::select('*')->where('divisi', $id_divisi)->get();

        return view('user_access.edit-access-user', [
            'type_menu' => $type_menu,
            'data'      => $data,
            'pages'     => $page,
            'division'  => $division,
            'user'      => $user
        ]);
    }

    public function view($page, $id, $id_divisi)
    {
        $type_menu = 'user_access';
        $data      = M_AccessUser::select('*')->where('id', $id)->get();
        $division  = M_CompanyEvent::all();
        $user      = M_User::select('*')->where('divisi', $id_divisi)->get();

        return view('user_access.view-access-user', [
            'type_menu' => $type_menu,
            'data'      => $data,
            'pages'     => $page,
            'division'  => $division,
            'user'      => $user
        ]);
    }

    public function delete($id)
    {
        $data = M_AccessUser::findOrFail($id);
        $data->delete();

        return response()->json(['success' => 'Data has been deleted successfully.']);
    }

    public function update(Request $request)
    {
        $namaDivisi  = M_CompanyEvent::select('*')->where('id', $request->division)->first();
        $ownerDivisi = M_CompanyEvent::select('*')->where('id', $request->division_owner)->first();;

        DB::table('tbl_access_user')
            ->where('id', $request->admin_id)
            ->update([
                'id_divisi'         => $request->division,
                'nama_divisi'       => $namaDivisi->name,
                'id_divisi_owner'   => $request->division_owner,
                'nama_divisi_owner' => $ownerDivisi->name,
                'start_date'        => $request->start_date,
                'end_date'          => $request->end_date,
                'status'            => $request->status,
                'created_by'        => Auth::user()->username,
                'created_date'      => Carbon::now(),
                'update_by'         => NULL,
                'update_date'       => NULL,
                'created_at'        => Carbon::now(),
                'updated_at'        => NULL,
            ]);

        return response()->json(['message' => 'success']);
    }

    public function fetchDivisionOwners(Request $request)
    {
        $divisionId = $request->input('division_id');

        if (!$divisionId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Division ID is required.',
            ], 400);
        }

        $owners = DB::table('tbl_user')
            ->where('divisi', $divisionId)
            ->select('id', 'username')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $owners,
        ]);
    }

    public function export_excel()
    {
        $customHeadings = [
            'No',
            'Division',
            'Owner Division',
            'Start Date',
            'End Date',
            'Created By',
            'Created Date',
            'Status',
        ];
        $filename = 'Data User Access.xlsx';

        return Excel::download(new ExportExcel('user_access', $customHeadings), $filename);
    }
}
