<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\M_CompanyEvent;
use App\Models\M_User;
use App\Models\M_MasterEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyEventController extends Controller
{
    function index($page)
    {
        $type_menu = 'company_event';
        $data = DB::table('tbl_company_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'status', 'name', 'description', 'created_by', 'created_at', 'updated_by', 'updated_at')
            ->get();
        $masterEvent = masterEvent($page);
        $user = userAdmin();
        $userId = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('company_event.index', [
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function add_company_index($page)
    {
        $type_menu = 'company_event';
        $companyEvent = companyEvent();

        if ($page == "cms") {
            return view('company_event.add-company-event', [
                'data' => $companyEvent,
                'type_menu' => $type_menu
            ]);
        }
    }

    public function add(Request $request)
    {
        $companyEvent = M_CompanyEvent::select('*')->where('name', trim($request->name))->count();

        if ($companyEvent > 0) {
            return response()->json(['message' => 'failed']);
        } else {
            DB::table('tbl_company_event')->insert([
                'status' => $request->status,
                'name' => trim($request->name),
                'description' => $request->deskripsi,
                'created_by' => $request->username,
                'created_at' => Carbon::now(),
                'updated_by' => $request->username,
                'updated_at' => Carbon::now(),
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function edit($id)
    {
        $type_menu = 'company_event';
        $data = M_CompanyEvent::select('*')->where('id', $id)->get();

        return view('company_event.edit-company-event', [
            'data' => $data,
            'type_menu' => $type_menu
        ]);
    }

    public function update(Request $request)
    {
        DB::table('tbl_company_event')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'description' => $request->deskripsi,
                'status' => $request->status,
                'created_by' => $request->username,
                'updated_at' => Carbon::now(),
                'updated_by' => $request->username,
            ]);
        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_CompanyEvent::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
