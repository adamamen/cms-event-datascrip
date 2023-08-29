<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use App\Models\M_User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterEventController extends Controller
{
    function index($page)
    {
        $data = $this->query();
        $type_menu = 'master_event';
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        $user = M_User::select('*')->where('event_id', '0')->get()->toArray();
        $userId = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('master_event.index', [
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => !empty($data) ? $data : array(),
                'type_menu' => $type_menu
            ]);
        } else {
            return abort(404);
        }
    }

    public function query()
    {
        $masterEvent = DB::table('tbl_master_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id_event) AS RowNumber'), 'status', 'title', 'desc', 'company', 'start_event', 'end_event', 'logo', 'location', 'id_event', 'created_at', 'createed_by', 'updated_at', 'updated_by')
            ->get();
        $companyEvent = M_CompanyEvent::select('*')->get();

        foreach ($masterEvent as $master) {
            foreach ($companyEvent as $company) {
                if ($master->company == $company->id) {
                    $merge[] = [
                        'RowNumber' => $master->RowNumber,
                        'status' => $master->status,
                        'title' => $master->title,
                        'desc' => $master->desc,
                        'company' => $master->company,
                        'start_event' => $this->tgl_indo(date('Y-m-d', strtotime($master->start_event))),
                        'end_event' => $this->tgl_indo(date('Y-m-d', strtotime($master->end_event))),
                        'logo' => $master->logo,
                        'location' => $master->location,
                        'id_event' => $master->id_event,
                        'created_at' => $master->created_at,
                        'createed_by' => $master->createed_by,
                        'updated_at' => $master->updated_at,
                        'updated_by' => $master->updated_by,
                        'nama_divisi' => $company->name,
                    ];
                }
            }
        }

        $merge = !empty($merge) ? $merge : '';
        return $merge;
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public function add(Request $request)
    {
        $logo = $request->file('logo');
        $imageName = time() . '.' . $logo->getClientOriginalExtension();
        $logo->move(public_path('images'), $imageName);

        $array_1 =
            [
                'status' => $request->status,
                'desc' => $request->deskripsi,
                'company' => $request->divisi,
                'start_event' => $request->startEvent,
                'end_event' => $request->endEvent,
                'logo' => $imageName,
                'location' => $request->lokasi,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => $request->username,
                'createed_by' => $request->username,
                'title_url' => preg_replace('/\s+/', '-', strtolower($request->namaEvent)),
            ];
        // preg_replace('/\s+/', ' ', $string);
        M_MasterEvent::updateOrCreate(['title' => preg_replace('/\s+/', ' ', $request->namaEvent)], $array_1);

        return response()->json(['message' => 'success']);
    }

    public function add_index($page)
    {
        $type_menu = 'master_event';
        $listDivisi = M_CompanyEvent::select('*')->where('status', 'A')->get();

        if ($page == "cms") {
            return view('master_event.add_event', [
                'type_menu' => $type_menu,
                'listDivisi' => !empty($listDivisi) ? $listDivisi : '',
            ]);
        }
    }

    public function delete($id)
    {
        $data = M_MasterEvent::find($id); // Fetch data based on ID

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }

    public function edit($id)
    {
        $data = DB::table('tbl_master_event')
            ->join('tbl_company_event', 'tbl_master_event.company', '=', 'tbl_company_event.id')
            ->where('id_event', $id)
            ->get();
        $listDivisi = M_CompanyEvent::select('*')->where('status', 'A')->get();
        $type_menu = 'master_event';

        return view(
            'master_event.edit_event',
            [
                'data' => $data,
                'listDivisi' => $listDivisi,
                'type_menu' => $type_menu,
                'status' => $data[0]->status
            ]
        );
    }

    public function update(Request $request)
    {
        if ($request->file('logo') != null) {
            $logo = $request->file('logo');
            $imageName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $imageName);

            DB::table('tbl_master_event')
                ->where('id_event', $request->id_event)
                ->update([
                    'title' => preg_replace('/\s+/', ' ', $request->namaEvent),
                    'status' => $request->status,
                    'desc' => $request->deskripsi,
                    'company' => $request->divisi,
                    'start_event' => $request->startEvent,
                    'end_event' => $request->endEvent,
                    'logo' => $imageName != "undefined" ? $imageName : '',
                    'location' => $request->lokasi,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->username,
                ]);

            return response()->json(['message' => 'success']);
        } else {
            DB::table('tbl_master_event')
                ->where('id_event', $request->id_event)
                ->update([
                    'title' => preg_replace('/\s+/', ' ', $request->namaEvent),
                    'status' => $request->status,
                    'desc' => $request->deskripsi,
                    'company' => $request->divisi,
                    'start_event' => $request->startEvent,
                    'end_event' => $request->endEvent,
                    'location' => $request->lokasi,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $request->username,
                ]);

            return response()->json(['message' => 'success']);
        }
    }
}
