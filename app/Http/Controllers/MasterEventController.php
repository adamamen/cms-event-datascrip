<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MasterEventController extends Controller
{
    function index($page)
    {
        $data        = $this->query();
        $type_menu   = 'master_event';
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('master_event.index', [
                'id'          => $userId,
                'masterEvent' => $masterEvent,
                'data'        => !empty($data) ? $data : array(),
                'type_menu'   => $type_menu
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function query()
    {
        $masterEvent = DB::table('tbl_master_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id_event) AS RowNumber'), 'status', 'title', 'desc', 'company', 'start_event', 'end_event', 'logo', 'location', 'id_event', 'created_at', 'createed_by', 'updated_at', 'updated_by', 'jenis_event', 'tanggal_terakhir_aplikasi', 'title_url', 'start_registrasi', 'end_registrasi')
            ->get();
        $companyEvent = companyEvent();

        foreach ($masterEvent as $master) {
            foreach ($companyEvent as $company) {
                if ($master->company == $company->id) {
                    $merge[] = [
                        'RowNumber'                      => $master->RowNumber,
                        'jenis_event'                    => $master->jenis_event,
                        'title_url'                      => $master->title_url,
                        'tanggal_terakhir_aplikasi'      => $master->tanggal_terakhir_aplikasi,
                        'tanggal_terakhir_aplikasi_indo' => tgl_indo(date('Y-m-d', strtotime($master->tanggal_terakhir_aplikasi))),
                        'status'                         => $master->status,
                        'title'                          => $master->title,
                        'desc'                           => $master->desc,
                        'company'                        => $master->company,
                        'start_event'                    => tgl_indo(date('Y-m-d', strtotime($master->start_event))),
                        'end_event'                      => tgl_indo(date('Y-m-d', strtotime($master->end_event))),
                        'logo'                           => $master->logo,
                        'location'                       => $master->location,
                        'id_event'                       => $master->id_event,
                        'created_at'                     => $master->created_at,
                        'createed_by'                    => $master->createed_by,
                        'updated_at'                     => $master->updated_at,
                        'updated_by'                     => $master->updated_by,
                        'start_registrasi'               => $master->start_registrasi,
                        'end_registrasi'                 => $master->end_registrasi,
                        'nama_divisi'                    => $company->name,
                    ];
                }
            }
        }

        $merge = !empty($merge) ? $merge : '';
        return $merge;
    }

    public function add_index($page)
    {
        $type_menu  = 'master_event';
        $listDivisi = listDivisi();

        if ($page == "cms") {
            return view('master_event.add_event', [
                'type_menu'  => $type_menu,
                'listDivisi' => !empty($listDivisi) ? $listDivisi : '',
            ]);
        }
    }

    public function add(Request $request)
    {
        $checkTitle    = checkTitleUrl($request->title_url);
        $lastCharacter = substr($request->title_url, -1);
        $symbols       = array("!", "@", "#", "$", "%", "&", "*", "+", "=", "?", "-", "_");

        if (in_array($lastCharacter, $symbols)) {
            return response()->json(['message' => 'failed last character']);
        } else if (!empty($checkTitle)) {
            return response()->json(['message' => 'failed']);
        } else {
            $logo      = $request->file('logo');
            $imageName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('images'), $imageName);

            $array_1 =
                [
                    'title'                     => preg_replace('/\s+/', ' ', $request->namaEvent),
                    'status'                    => $request->status,
                    'desc'                      => $request->deskripsi,
                    'company'                   => $request->divisi,
                    'start_event'               => $request->startEvent,
                    'end_event'                 => $request->endEvent,
                    'logo'                      => $imageName,
                    'location'                  => $request->lokasi,
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'updated_by'                => $request->username,
                    'createed_by'               => $request->username,
                    'title_url'                 => $request->title_url,
                    'jenis_event'               => $request->jenis_event,
                    'tanggal_terakhir_aplikasi' => $request->end_event_application,
                    'start_registrasi'          => $request->start_registrasi,
                    'end_registrasi'            => $request->end_registrasi,
                ];
            DB::table('tbl_master_event')->insert([$array_1]);

            return response()->json(['message' => 'success']);
        }
    }

    public function delete($id)
    {
        $data = M_MasterEvent::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }

    public function edit($id)
    {
        $data = DB::table('tbl_master_event as A')
            ->select('A.id_event', 'A.status as status_master_event', 'A.title', 'A.desc', 'A.company', 'A.start_event', 'A.end_event', 'A.logo', 'A.location', 'A.title_url', 'A.jenis_event', 'B.id', 'B.status as status_company_event', 'B.name', 'B.description', 'A.start_registrasi', 'A.end_registrasi')
            ->join('tbl_company_event as B', 'A.company', '=', 'B.id')
            ->where('A.id_event', $id)
            ->get();
        $listDivisi = listDivisi();
        $type_menu  = 'master_event';

        return view('master_event.edit_event', ['data' => $data, 'listDivisi' => $listDivisi, 'type_menu' => $type_menu]);
    }

    public function update(Request $request)
    {
        $checkTitle = checkTitleUrl($request->title_url);

        foreach ($checkTitle as $value) {
            $title['title_url'] = $value['title_url'];
        }

        $title_        = !empty($title['title_url']) ? $title['title_url'] : '';
        $lastCharacter = substr($request->title_url, -1);
        $symbols       = array("!", "@", "#", "$", "%", "&", "*", "+", "=", "?", "-", "_");

        if (in_array($lastCharacter, $symbols)) {
            return response()->json(['message' => 'failed last character']);
        } else if (($request->title_url_before == $title_) || empty($title['title_url'])) {
            if ($request->file('logo') != null) {
                $logo      = $request->file('logo');
                $imageName = time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('images'), $imageName);

                $a = DB::table('tbl_master_event')
                    ->where('id_event', $request->id_event)
                    ->update([
                        'title'                     => preg_replace('/\s+/', ' ', $request->namaEvent),
                        'status'                    => $request->status,
                        'desc'                      => $request->deskripsi,
                        'company'                   => $request->divisi,
                        'start_event'               => $request->startEvent,
                        'end_event'                 => $request->endEvent,
                        'logo'                      => $imageName != "undefined" ? $imageName : '',
                        'location'                  => $request->lokasi,
                        'updated_at'                => Carbon::now(),
                        'updated_by'                => $request->username,
                        'title_url'                 => $request->title_url,
                        'jenis_event'               => $request->jenis_event,
                        'tanggal_terakhir_aplikasi' => $request->end_event_application,
                        'start_registrasi'          => $request->start_registrasi,
                        'end_registrasi'            => $request->end_registrasi,
                    ]);

                return response()->json(['message' => 'success']);
            } else {
                DB::table('tbl_master_event')
                    ->where('id_event', $request->id_event)
                    ->update([
                        'title'                     => preg_replace('/\s+/', ' ', $request->namaEvent),
                        'status'                    => $request->status,
                        'desc'                      => $request->deskripsi,
                        'company'                   => $request->divisi,
                        'start_event'               => $request->startEvent,
                        'end_event'                 => $request->endEvent,
                        'location'                  => $request->lokasi,
                        'updated_at'                => Carbon::now(),
                        'updated_by'                => $request->username,
                        'title_url'                 => $request->title_url,
                        'jenis_event'               => $request->jenis_event,
                        'tanggal_terakhir_aplikasi' => $request->end_event_application,
                        'start_registrasi'          => $request->start_registrasi,
                        'end_registrasi'            => $request->end_registrasi,
                    ]);

                return response()->json(['message' => 'success']);
            }
        } else {
            return response()->json(['message' => 'url has been input']);
        }
    }
}
