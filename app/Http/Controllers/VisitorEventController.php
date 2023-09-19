<?php

namespace App\Http\Controllers;

use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_VisitorEvent;
use App\Models\M_MetodeBayar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class VisitorEventController extends Controller
{
    function index($page)
    {
        $type_menu = 'visitor_event';
        $data = visitorEventandMasterEvent($page);
        $masterEvent = masterEvent($page);
        $user = userAdmin();
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
                'jenis_events' => !empty($data[0]['jenis_event']) ? $data[0]['jenis_event'] : ''
            ]);
        } else if ($page == "cetak-invoice") {
            $this->generate_pdf($page, $userId);
        } else {
            return view('error.error-404');
        }
    }

    public function add_visitor_index($page)
    {
        $type_menu = 'visitor_event';
        $masterEvent = masterEvent($page);
        $user = userAdmin();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        if ($page == "cms") {
            $data = masterEvent_3();
        } else {
            $data = masterEvent_4($page);
        }

        return view('visitor_event.add-visitor-event', [
            'id' => $userId,
            'titleUrl' => $titleUrl,
            'masterEvent' => $masterEvent,
            'data' => $data,
            'type_menu' => $type_menu
        ]);
    }


    function initials($str)
    {
        $ret = '';
        foreach (explode('-', $str) as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }

    public function add(Request $request)
    {
        // dd($request->all());
        $query = M_VisitorEvent::select('*')
            ->where('event_id', $request->namaEvent)
            ->where('ticket_no', $request->noTiket)
            ->get();
        $masterEvent = M_MasterEvent::select('*')->where('id_event', $request->namaEvent)->where('jenis_event', 'A')->first();
        if ($masterEvent != null) {
            $noInvoice = 'INV' . date("y") . '/' . $this->initials($masterEvent->title_url) . date("md") . '/' . str_repeat("0", (5 - strlen($request->noTiket))) . $request->noTiket;
        } else {
            $noInvoice = '';
        }

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
                'jenis_event' => !empty($masterEvent->jenis_event) ? $masterEvent->jenis_event : '',
                'no_invoice' => $noInvoice,
                'status_pembayaran' => 'Belum Dibayar',
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function edit()
    {
        $page = request('page');
        $page_1 = request('page_1');
        $id = request('id');
        $type_menu = 'visitor_event';
        $data = M_VisitorEvent::select('*')->where('id', $id)->get();
        $masterEvent = masterEvent($page);
        $user = userAdmin();
        $userId = $user[0]['id'];
        $titleUrl = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $metodeBayar = M_MetodeBayar::select('*')->get();

        if ($page == 'cms') {
            $event = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page_1)->get()->toArray();
        } else {
            $event = masterEvent($page);
        }

        if (!empty($masterEvent) || $page == "cms") {
            return view('visitor_event.edit-visitor-event', [
                'titleUrl' => $titleUrl,
                'id' => $userId,
                'masterEvent' => $masterEvent,
                'data' => $data,
                'type_menu' => $type_menu,
                'event' => $event,
                'metodeBayar' => $metodeBayar,
                'event_1' => $event,
                'event_2' => $event,
                // 'jenisEvent' => $event[0]['jenis_event']
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
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
                        'metode_bayar' => $request->metode_bayar,
                        'status_pembayaran' => $request->status_bayar,
                        'sn_product' => $request->sn_product,
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
                    'metode_bayar' => $request->metode_bayar,
                    'status_pembayaran' => $request->status_bayar,
                    'sn_product' => $request->sn_product,
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

    public function index_register($page)
    {
        $tanggal_terakhir_aplikasi = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->first();
        $data = masterEvent_4($page);
        if (strtotime(!empty($tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi) ? $tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi : '') > strtotime(date('Y-m-d H:i:s'))) {
            $masterEvent = M_MasterEvent::select('*')->where('title_url', $page)->where('status', 'A')->get()->toArray();
        }

        if (!empty($masterEvent)) {
            if ($page == $masterEvent[0]['title_url']) {
                return view('visitor_event.register', [
                    'masterEvent' => $masterEvent,
                    'data' => $data,
                    'page' => $page
                ]);
            } else {
                return view('error.error-404');
            }
        } else {
            return view('error.error-404');
        }
    }

    public function generate_pdf($page, $id)
    {
        // $data = $this->query($page);
        $data = visitorEventandMasterEvent($page);
        foreach ($data as $value) {
            if ($value['id'] == $id) {
                $val['id'] = $value['id'];
                $val['product_invoice_no'] = $value['no_invoice'];
                $val['visitor_que_no'] = $value['no_ticket'];
                $val['visitor_fullname'] = $value['full_name'];
                $val['visitor_mobile'] = $value['mobile'];
                $val['visitor_address'] = $value['metode_bayar'];
                $val['visitor_payment_method'] = $value['metode_bayar'];
                $val['product_serial_no'] = $value['sn_product'];
                $val['updated_by'] = $value['updated_by'];
            }
        }

        return view('visitor_event.cetak-invoice', ['val' => $val]);
    }

    public function export_excel($page)
    {
        $query = visitorEventandMasterEvent($page);

        if (!empty($query)) {
            if ($page == "cms") {
                $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'No Invoice', 'SN Product', 'Status Pembayaran', 'Metode Pembayaran', 'Tanggal Registrasi', 'Jenis Event'];
            } else if ($query[0]['jenis_event'] == "A") {
                $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'No Invoice', 'SN Product', 'Status Pembayaran', 'Metode Pembayaran', 'Tanggal Registrasi'];
            } else {
                $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'Tanggal Registrasi'];
            }
        }

        $filename = 'Data Visitor Event - ' . ucfirst($page) . '.xlsx';
        return Excel::download(new ExportExcel($page, $customHeadings), $filename);
    }
}
