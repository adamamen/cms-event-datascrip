<?php

namespace App\Http\Controllers;

use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_VisitorEvent;
use App\Models\M_MetodeBayar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;

class VisitorEventController extends Controller
{
    function index($page)
    {
        $type_menu   = 'visitor_event';
        $data        = visitorEventandMasterEvent($page);
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $title       = str_replace('-', ' ', $titleUrl);
        $output      = ucwords($title);
        $dataArrival = M_VisitorEvent::select('*')
            ->whereNotNull('scan_date')
            ->get();

        if (!empty($masterEvent) || $page == "cms") {
            Log::info('User berada di menu Data Visitor Event ' . strtoupper($page), ['username' => Auth::user()->username]);
            return view('visitor_event.index', [
                'id'           => $userId,
                'masterEvent'  => $masterEvent,
                'data'         => $data,
                'type_menu'    => $type_menu,
                'titleUrl'     => $titleUrl,
                'pages'        => $page,
                'output'       => $output,
                'dataArrival'  => count($dataArrival),
                'jenis_events' => !empty($data[0]['jenis_event']) ? $data[0]['jenis_event'] : ''
            ]);
        } else if ($page == "cetak-invoice") {
            Log::info('User cetak Excel di menu Data Visitor Event ' . strtoupper($page), ['username' => Auth::user()->username]);
            $this->generate_pdf($page, $userId);
        } else if ($page == "landing-page-qr") {
            $this->index_landing_page($page);
        } else {
            return view('error.error-404');
        }
    }

    public function add_visitor_index($page)
    {
        $type_menu   = 'visitor_event';
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        if ($page == "cms") {
            $data = masterEvent_3();
        } else {
            $data = masterEvent_4($page);
        }

        return view('visitor_event.add-visitor-event', [
            'id'          => $userId,
            'titleUrl'    => $titleUrl,
            'masterEvent' => $masterEvent,
            'data'        => $data,
            'type_menu'   => $type_menu
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
                'event_id'          => $request->namaEvent,
                'ticket_no'         => $request->noTiket,
                'registration_date' => $request->tanggalRegistrasi,
                'full_name'         => $request->namaLengkap,
                'address'           => $request->alamat,
                'email'             => $request->email,
                'mobile'            => $request->noHandphone,
                'created_at'        => Carbon::now(),
                'created_by'        => $request->username,
                'updated_at'        => Carbon::now(),
                'updated_by'        => $request->username,
                'jenis_event'       => !empty($masterEvent->jenis_event) ? $masterEvent->jenis_event : '',
                'no_invoice'        => $noInvoice,
                'status_pembayaran' => 'Belum Dibayar',
            ]);

            Log::info('User berhasil add Data di menu Data Visitor Event ', [
                'event_id'          => $request->namaEvent,
                'ticket_no'         => $request->noTiket,
                'registration_date' => $request->tanggalRegistrasi,
                'full_name'         => $request->namaLengkap,
                'address'           => $request->alamat,
                'email'             => $request->email,
                'mobile'            => $request->noHandphone,
                'created_at'        => Carbon::now(),
                'created_by'        => $request->username,
                'updated_at'        => Carbon::now(),
                'updated_by'        => $request->username,
                'jenis_event'       => !empty($masterEvent->jenis_event) ? $masterEvent->jenis_event : '',
                'no_invoice'        => $noInvoice,
                'status_pembayaran' => 'Belum Dibayar',
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function edit()
    {
        $page        = request('page');
        $page_1      = request('page_1');
        $id          = request('id');
        $type_menu   = 'visitor_event';
        $data        = M_VisitorEvent::select('*')->where('id', $id)->get();
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $metodeBayar = M_MetodeBayar::select('*')->get();
        $title       = str_replace('-', ' ', $titleUrl);
        $output      = ucwords($title);

        if ($page == 'cms') {
            $event = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page_1)->get()->toArray();
        } else {
            $event = masterEvent($page);
        }

        if (!empty($masterEvent) || $page == "cms") {
            Log::info('User klik Edit di menu Data Visitor Event', ['username' => Auth::user()->username]);

            return view('visitor_event.edit-visitor-event', [
                'titleUrl'    => $titleUrl,
                'id'          => $userId,
                'masterEvent' => $masterEvent,
                'data'        => $data,
                'type_menu'   => $type_menu,
                'event'       => $event,
                'metodeBayar' => $metodeBayar,
                'event_1'     => $event,
                'event_2'     => $event,
                'output'      => $output,
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function update(Request $request)
    {
        // dd($request->id);
        DB::table('tbl_visitor_event')
            ->where('id', $request->id)
            ->update([
                'full_name'         => $request->name,
                'email'             => $request->email,
                'gender'            => $request->gender,
                'account_instagram' => $request->instagramAccount,
                'mobile'            => $request->phoneNumber,
                'type_invitation'   => $request->invitationType,
                'invitation_name'   => $request->nameOfAgency,
                'updated_at'        => Carbon::now(),
                'updated_by'        => $request->username,
            ]);

        Log::info('User berhasil update di menu Data Visitor Event', [
            'full_name'         => $request->name,
            'email'             => $request->email,
            'gender'            => $request->gender,
            'account_instagram' => $request->instagramAccount,
            'mobile'            => $request->phoneNumber,
            'type_invitation'   => $request->invitationType,
            'invitation_name'   => $request->nameOfAgency,
            'updated_at'        => Carbon::now(),
            'updated_by'        => $request->username,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_VisitorEvent::find($id);

        if ($data) {
            $data->delete();
            Log::info('Data Berhasil di Delete di menu Data Visitor Event', ['username' => Auth::user()->username, 'data' => $data]);
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }

    public function index_register($page)
    {
        $tanggal_terakhir_aplikasi = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->first();
        $data                      = masterEvent_4($page);
        if (strtotime(!empty($tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi) ? $tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi : '') > strtotime(date('Y-m-d H:i:s'))) {
            $masterEvent = M_MasterEvent::select('*')->where('title_url', $page)->where('status', 'A')->get()->toArray();
        }

        if (!empty($masterEvent)) {
            if ($page == $masterEvent[0]['title_url']) {
                return view('visitor_event.register', [
                    'masterEvent' => $masterEvent,
                    'data'        => $data,
                    'page'        => $page
                ]);
            } else {
                return view('error.error-404');
            }
        } else {
            return view('error.error-404');
        }
    }

    public function generate_pdf($id)
    {
        $visitor     = DB::table('tbl_visitor_event')->where('id', $id)->first();
        $masterEvent = DB::table('tbl_visitor_event')
            ->select('*')
            ->join('tbl_master_event', 'tbl_visitor_event.event_id', '=', 'tbl_master_event.id_event')
            ->where('tbl_visitor_event.id', $id)
            ->get()
            ->toArray();

        return view('visitor_event.cetak-pdf-qr', ['visitor' => $visitor, 'masterEvent' => $masterEvent]);

        // $data = visitorEventandMasterEvent($id);

        // foreach ($data as $value) {
        //     if ($value['id'] == $id) {
        //         $val['id']                     = $value['id'];
        //         $val['product_invoice_no']     = $value['no_invoice'];
        //         $val['visitor_que_no']         = $value['no_ticket'];
        //         $val['visitor_fullname']       = $value['full_name'];
        //         $val['visitor_mobile']         = $value['mobile'];
        //         $val['visitor_address']        = $value['metode_bayar'];
        //         $val['visitor_payment_method'] = $value['metode_bayar'];
        //         $val['product_serial_no']      = $value['sn_product'];
        //         $val['updated_by']             = $value['updated_by'];
        //     }
        // }

        //         Log::info('User Cetak Invoice di menu Data Visitor Event ' . strtoupper($page), ['username' => Auth::user()->username]);
        // 
        //         return view('visitor_event.cetak-invoice', ['val' => $val]);
    }

    public function export_excel($page)
    {
        $query = visitorEventandMasterEvent($page);
        $pages = ucwords(str_replace('-', ' ', $page));

        // if (!empty($query)) {
        //     if ($page == "cms") {
        //         $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'No Invoice', 'SN Product', 'Status Pembayaran', 'Metode Pembayaran', 'Tanggal Registrasi', 'Jenis Event'];
        //     } else if ($query[0]['jenis_event'] == "A") {
        //         $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'No Invoice', 'SN Product', 'Status Pembayaran', 'Metode Pembayaran', 'Tanggal Registrasi'];
        //     } else {
        //         $customHeadings = ['No', 'No Tiket', 'Nama Event', 'Nama', 'No Handphone', 'Email', 'Alamat', 'Tanggal Registrasi'];
        //     }
        // }
        if (!empty($query)) {
            $customHeadings = ['No', 'Name', 'Email', 'Gender', 'Instagram Account', 'Phone Number', 'Invitation Type', 'Name Of Agency / Company', 'Barcode No'];
        }

        $filename = 'Data Visitor Event - ' . ucfirst($pages) . '.xlsx';
        return Excel::download(new ExportExcel($page, $customHeadings), $filename);
    }

    public function template_excel()
    {
        $filePath = public_path('library/template-excel/Template Excel Event.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Template Excel Event.xlsx');
        } else {
            return redirect()->back()->with('error', 'Template file not found.');
        }
    }

    //     public function import_excel(Request $request, $page)
    //     {
    //         $validator = Validator::make($request->all(), [
    //             'excel_file' => 'required|mimes:xlsx|max:2048',
    //         ]);
    // 
    //         if ($validator->fails()) {
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }
    // 
    //         $file = $request->file('excel_file');
    //         $rows = Excel::toArray([], $file)[0];
    // 
    //         unset($rows[0]);
    //         $event = M_MasterEvent::where('status', 'A')->where('title_url', $page)->first();
    //         // dd($page);
    // 
    //         if (!empty($event)) {
    //             // dd('1');
    //             foreach ($rows as $row) {
    //                 $qrData   = $row[1] . '-' . $row[2];
    //                 $qrCode   = QrCode::format('png')->size(200)->generate($qrData);
    //                 $filename = $row[1] . '-qrcode.png';
    // 
    //                 Storage::disk('public')->put($filename, $qrCode);
    // 
    //                 $barcodeLink = url('storage/qrcodes/' . $filename);
    //                 $barcodeNo = generateUniqueCode();
    //                 // dd(time());
    // 
    //                 DB::table('tbl_visitor_event')->insert([
    //                     'event_id'          => $event['id_event'],
    //                     'ticket_no'         => 'NULL',
    //                     'full_name'         => $row[1],
    //                     'email'             => $row[2],
    //                     'gender'            => $row[3],
    //                     'account_instagram' => $row[4],
    //                     'mobile'            => $row[5],
    //                     'type_invitation'   => $row[6],
    //                     'invitation_name'   => $row[7],
    //                     'registration_date' => Carbon::now(),
    //                     'address'           => 'NULL',
    //                     'barcode_no'        => strtoupper($barcodeNo),
    //                     'source'            => 'NULL',
    //                     'barcode_link'      => $barcodeLink,
    //                     'noted'             => 'NULL',
    //                     'scan_date'         => Carbon::now(),
    //                     'created_by'        => Auth::user()->username,
    //                     'updated_by'        => Auth::user()->username,
    //                 ]);
    //             }
    // 
    //             return redirect()->back()->with('success', 'Data berhasil diimport');
    //         } else {
    //             // dd('2');
    //             return redirect()->back()->with('error', 'Data gagal diimport, event tidak ditemukan.');
    //         }
    //     }

    public function import_excel(Request $request, $page)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];

        unset($rows[0]);

        $event = M_MasterEvent::where('status', 'A')->where('title_url', $page)->first();

        if (!empty($event)) {
            foreach ($rows as $row) {
                $barcodeNo = strtoupper(generateUniqueCode());
                $filename  = $barcodeNo . '-qrcode.png';
                $qrCode    = new QrCode($barcodeNo);
                $writer    = new PngWriter();
                $path      = 'qrcodes/' . $filename;
                $writer->write($qrCode)->saveToFile(storage_path('app/public/' . $path));

                $barcodeLink = asset('storage/qrcodes/' . $filename);

                DB::table('tbl_visitor_event')->insert([
                    'event_id'          => $event['id_event'],
                    'ticket_no'         => NULL,
                    'full_name'         => $row[1],
                    'email'             => $row[2],
                    'gender'            => $row[3],
                    'account_instagram' => $row[4],
                    'mobile'            => $row[5],
                    'type_invitation'   => $row[6],
                    'invitation_name'   => $row[7],
                    'registration_date' => Carbon::now(),
                    'address'           => NULL,
                    'barcode_no'        => strtoupper($barcodeNo),
                    'source'            => NULL,
                    'barcode_link'      => $barcodeLink,
                    'noted'             => NULL,
                    'scan_date'         => NULL,
                    'created_at'        => Carbon::now(),
                    'created_by'        => Auth::user()->username,
                    'updated_by'        => Auth::user()->username,
                ]);
            }

            return redirect()->back()->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->back()->with('error', 'Data gagal diimport, event tidak ditemukan.');
        }
    }

    public function showQRCode($id)
    {
        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $visitor = DB::table('tbl_visitor_event')->where('id', $id)->first();

        if (!$visitor) {
            return redirect()->back()->with('error', 'Visitor tidak ditemukan.');
        }

        $visitorEvent = DB::table('tbl_visitor_event')
            ->select('*')
            ->join('tbl_master_event', 'tbl_visitor_event.event_id', '=', 'tbl_master_event.id_event')
            ->where('tbl_visitor_event.id', $id)
            ->get()
            ->toArray();

        return view('visitor_event.qrcode', compact('visitor', 'visitorEvent'));
    }

    public function downloadQR($id)
    {
        $visitor = DB::table('tbl_visitor_event')->where('id', $id)->first();

        if (!$visitor) {
            return redirect()->back()->with('error', 'Visitor not found.');
        }

        $qrCode = QrCode::create($visitor->barcode_no)
            ->setSize(200);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrCodePath = 'storage/qrcodes/' . $visitor->full_name . '-qrcode.png';
        Storage::disk('public')->put($qrCodePath, $result->getString());

        $pdf = PDF::loadView('visitor_event.cetak-pdf-qr', compact('visitor', 'qrCodePath'));

        return $pdf->download($visitor->barcode_no . '-' . $visitor->full_name . '.pdf');
    }

    public function deleteMultipleVisitors(Request $request)
    {
        $ids = $request->ids;

        if (!empty($ids)) {
            DB::table('tbl_visitor_event')->whereIn('id', $ids)->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No IDs selected']);
    }

    public function index_landing_page($page)
    {
        $title = "landing-page-qr";
        $page  = masterEvent($page);

        if (!empty($page)) {
            return view('visitor_event.landing-page-qr', [
                'page' => $page,
                'title' => $title
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function verify_qr(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $visitor = M_VisitorEvent::where('barcode_no', $request->qr_code)->first();

        if ($visitor) {
            if ($visitor->flag_qr) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'QR Code sudah pernah digunakan. Silahkan coba lagi.'
                ], 400);
            }

            $visitor->flag_qr   = true;
            $visitor->scan_date = Carbon::now();
            $visitor->save();

            return response()->json([
                'status'      => 'success',
                'visitorName' => $visitor->full_name
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'QR Code tidak ditemukan, silahkan coba lagi'
            ], 404);
        }
    }

    public function sendEmail(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            $visitors    = M_VisitorEvent::whereIn('id', $ids)->get();
            $masterEvent = DB::table('tbl_master_event')
                ->select("*")
                ->get();

            foreach ($visitors as $visitor) {
                foreach ($masterEvent as $event) {
                    if ($event->id_event == $visitor->event_id) {
                        $judul           = ucwords($event->title);
                        $nama            = $visitor->full_name;
                        $tanggalMulai    = tgl_indo(date('Y-m-d', strtotime($event->start_event)));
                        $tanggalAkhir    = tgl_indo(date('Y-m-d', strtotime($event->end_event)));
                        $mulaiRegistrasi = date('H:i', strtotime($event->start_registrasi));
                        $akhirRegistrasi = date('H:i', strtotime($event->end_registrasi));
                        $encryptedId     = encrypt($visitor->id);
                        $email           = $visitor->email;

                        $body = '<html>
                                    <head>
                                    <style type="text/css">
                                        body, td {
                                            font-family: "Times New Roman", Times, serif;
                                            font-size: 16px;
                                        }
                                        table#info {
                                            border: 1px solid #555;
                                            border-collapse: collapse;
                                        }
                                        table#info th,
                                        table#info td {
                                            padding: 3px;
                                            border: 1px solid #555;
                                        }
                                    </style>
                                    </head>
                                    <body>Selamat Pagi Bapak/Ibu, <br />
                                    <strong>' . $nama . '</strong><br />
                                    Terima kasih sudah melakukan Registrasi pada acara ' . $judul . ' <br /><br />
                                    Silahkan gunakan QR Code terlampir untuk diperlihatkan pada saat registrasi. Klik <a href="' . route('visitor.event.qrcode', ['id' => $encryptedId]) . '">di sini</a> untuk melihat QR Code.<br /><br />
                                    
                                    Tanggal Acara &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; : ' . $tanggalMulai . ' & ' . $tanggalAkhir . ' <br />
                                    Mulai Registrasi &nbsp; &nbsp; &nbsp; : ' . $mulaiRegistrasi . ' <br />
                                    Selesai Registrasi &nbsp; &nbsp; : ' . $akhirRegistrasi . ' <br />
                                    Tempat &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; : ' . $event->location . ' <br /><br />

                                    <strong>Syarat & Ketentuan</strong><br />
                                    - QR Code hanya bisa digunakan 1x pada event. <br />
                                    - QR Code Tidak boleh diperjual-belikan. <br />
                                    - Segala tindak kecurangan bukan tanggung jawab penyelenggara event. <br />
                                    </body>
                                </html>';

                        $mail = new PHPMailer(true);

                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer'       => false,
                                'verify_peer_name'  => false,
                                'allow_self_signed' => true
                            )
                        );

                        $mail->isSMTP();
                        $mail->Host       = env('MAIL_HOST');
                        $mail->SMTPAuth   = true;
                        $mail->Username   = env('MAIL_USERNAME');
                        $mail->Password   = env('MAIL_PASSWORD');
                        $mail->SMTPSecure = env('MAIL_ENCRYPTION');
                        $mail->Port       = env('MAIL_PORT');

                        $mail->setFrom('no_reply@datascrip.co.id', 'No Reply');
                        $mail->addAddress($email, $nama);

                        //Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Event ' . $judul;
                        $mail->Body    = $body;
                        $mail->send();
                    }
                }
            }

            return response()->json(['message' => 'Emails sent successfully!'], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }
}
