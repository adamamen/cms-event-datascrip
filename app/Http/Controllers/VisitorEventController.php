<?php

namespace App\Http\Controllers;

use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\M_MasterEvent;
use App\Models\M_VisitorEvent;
use App\Models\M_SendEmailCust;
use App\Models\M_MetodeBayar;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class VisitorEventController extends Controller
{
    function index($page)
    {
        $type_menu     = 'visitor_event';
        $data          = visitorEventandMasterEvent($page);
        $masterEvent   = masterEvent($page);
        $user          = userAdmin();
        $userId        = $user[0]['id'];
        $userIdSession = Auth::user()->event_id;
        $titleUrl      = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $title         = str_replace('-', ' ', $titleUrl);
        $output        = ucwords($title);

        if ($page == 'cms') {
            $totalApproval = M_VisitorEvent::select('*')
                ->where('flag_approval', '=', '1')
                ->get();
            $dataArrival = M_VisitorEvent::select('*')
                ->whereNotNull('scan_date')
                ->get();
        } else {
            $totalApproval = M_VisitorEvent::select('*')
                ->where('event_id', $data[0]['event_id'])
                ->where('flag_approval', '=', '1')
                ->get();
            $dataArrival = M_VisitorEvent::select('*')
                ->where('event_id', $data[0]['event_id'])
                ->whereNotNull('scan_date')
                ->get();
        }

        if ($userIdSession != 0 && $page == "cms") {
            return view('error.error-403');
        }

        if (!empty($masterEvent) || $page == "cms") {
            return view('visitor_event.index', [
                'id'            => $userId,
                'masterEvent'   => $masterEvent,
                'data'          => $data,
                'type_menu'     => $type_menu,
                'titleUrl'      => $titleUrl,
                'pages'         => $page,
                'output'        => $output,
                'dataArrival'   => count($dataArrival),
                'totalApproval' => count($totalApproval),
                'jenis_events'  => !empty($data[0]['jenis_event']) ? $data[0]['jenis_event'] : ''
            ]);
        } else if ($page == "cetak-invoice") {
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

    public function add(Request $request, $page)
    {
        $getIdEvent = M_MasterEvent::select('*')->where('title_url', trim($page))->first();
        $barcodeNo = strtoupper(generateUniqueCode());
        $filename  = $barcodeNo . '-qrcode.png';
        $qrCode    = new QrCode($barcodeNo);
        $writer    = new PngWriter();
        $path1     = 'qrcodes/' . $filename;
        $path2     = 'storage/qrcodes/' . $filename;

        $writer->write($qrCode)->saveToFile(storage_path('app/public/' . $path1));
        $writer->write($qrCode)->saveToFile(public_path($path2));

        $barcodeLink = asset('storage/qrcodes/' . $filename);

        DB::table('tbl_visitor_event')->insert([
            'event_id'          => $getIdEvent->id_event,
            'full_name'         => $request->name,
            'gender'            => $request->gender,
            'email'             => $request->email,
            'account_instagram' => $request->instagram_account,
            'mobile'            => $request->phone_number,
            'type_invitation'   => $request->invitation_type,
            'invitation_name'   => $request->name_of_agency,
            'created_at'        => Carbon::now(),
            'created_by'        => $request->name,
            'updated_by'        => $request->name,
            'registration_date' => Carbon::now(),
            'barcode_no'        => strtoupper($barcodeNo),
            'source'            => NULL,
            'barcode_link'      => $barcodeLink,
            'scan_date'         => NULL,
            'flag_qr'           => '0',
            'flag_email'        => '0',
            'id_cust'           => Crypt::decryptString($request->id_cust),
            'flag_approval'     => '0',                      // flag 1 approval, flag 0 blm di approval
            'approve_by'        => NULL,
            'approve_date'      => NULL,
            'source_visitor'    => 'Register'
        ]);

        return response()->json(['message' => 'success']);
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

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_VisitorEvent::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed']);
        }
    }

    public function index_register($page, $id)
    {
        $tanggal_terakhir_aplikasi = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->first();
        $data                      = masterEvent_4($page);
        $type_menu                 = 'register_visitor';
        if (strtotime(!empty($tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi) ? $tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi : '') > strtotime(date('Y-m-d H:i:s'))) {
            $masterEvent = M_MasterEvent::select('*')->where('title_url', $page)->where('status', 'A')->get()->toArray();
        }

        if ($id) {
            try {
                $id = Crypt::decryptString($id);
            } catch (\Exception $e) {
                return view('error.error-404');
            }
        }

        if (!empty($masterEvent)) {
            if ($page == $masterEvent[0]['title_url']) {
                return view('visitor_event.register', [
                    'masterEvent' => $masterEvent,
                    'data'        => $data,
                    'page'        => $page,
                    'type_menu'   => $type_menu
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
    }

    public function export_excel($page)
    {
        $query = visitorEventandMasterEvent($page);
        $pages = ucwords(str_replace('-', ' ', $page));

        if (!empty($query)) {
            $customHeadings = ['No', 'Name', 'Email', 'Gender', 'Instagram Account', 'Phone Number', 'Invitation Type', 'Name Of Agency / Company', 'Barcode No', 'Date Arrival', 'Email Status', 'Source Visitor', 'Status Approval', 'Approve By', 'Approve Date'];
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

    public function import_excel(Request $request, $page)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Gagal validasi file.'], 400);
        }

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];
        unset($rows[0]);

        $event = M_MasterEvent::where('status', 'A')->where('title_url', $page)->first();
        $countImported = 0;

        if (!empty($event)) {
            foreach ($rows as $row) {
                $barcodeNo = strtoupper(generateUniqueCode());
                $filename  = $barcodeNo . '-qrcode.png';
                $qrCode    = new QrCode($barcodeNo);
                $writer    = new PngWriter();
                $path1     = 'qrcodes/' . $filename;
                $path2     = 'storage/qrcodes/' . $filename;

                $writer->write($qrCode)->saveToFile(storage_path('app/public/' . $path1));
                $writer->write($qrCode)->saveToFile(public_path($path2));

                $barcodeLink = asset('storage/qrcodes/' . $filename);

                DB::table('tbl_visitor_event')->insert([
                    'event_id'          => $event->id_event,
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
                    'flag_qr'           => '0',
                    'flag_email'        => '0',
                    'flag_approval'     => '1',                      // flag 1 approval, flag 0 blm di approval
                    'approve_by'        => Auth::user()->username,
                    'approve_date'      => Carbon::now(),
                    'source_visitor'    => 'Upload'
                ]);

                $countImported++;
            }

            return response()->json(['message' => 'Data berhasil diimpor', 'count' => $countImported]);
        } else {
            return response()->json(['message' => 'Event tidak ditemukan.'], 400);
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

        $writer     = new PngWriter();
        $result     = $writer->write($qrCode);
        $qrCodePath = 'storage/qrcodes/' . $visitor->barcode_no . '-qrcode.png';

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

    public function approvalMultipleVisitors(Request $request)
    {
        $ids = $request->ids;

        if (!empty($ids)) {
            DB::table('tbl_visitor_event')
                ->whereIn('id', $ids)
                ->update([
                    'flag_approval' => '1',
                    'approve_by'    => Auth::user()->username,
                    'approve_date'  => Carbon::now(),
                ]);
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

        if ($visitor->flag_approval == 0) {
            return response()->json([
                'status' => 'flag_approval',
                'message' => '<strong>' . $visitor->full_name  . '</strong> has not yet approved. Please complete the approval first.'
            ]);
        } else {
            if ($visitor) {
                $tanggalDatang = tgl_indo(date('Y-m-d', strtotime($visitor->scan_date)));
                $waktuDatang   = date('H:i:s', strtotime($visitor->scan_date));

                if ($visitor->flag_qr == '0') {
                    $visitor->flag_qr   = true;
                    $visitor->scan_date = Carbon::now();
                    $visitor->save();

                    return response()->json([
                        'status'         => 'success',
                        'visitorName'    => $visitor->full_name,
                    ]);
                } else {
                    return response()->json([
                        'status'  => 'error_arrival',
                        'message' => 'Time Arrival = ' . $tanggalDatang . " " . $waktuDatang,
                    ]);
                }
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'QR Code not found, please try again.'
                ], 404);
            }
        }
    }

    public function sendEmailId($id)
    {
        $ids        = explode(',', $id);
        $emailsSent = 0;

        if (!empty($ids)) {
            $visitors    = M_VisitorEvent::whereIn('id', $ids)->get();
            $masterEvent = DB::table('tbl_master_event')->select("*")->get();

            foreach ($visitors as $visitor) {
                foreach ($masterEvent as $event) {
                    if ($visitor->flag_approval == '1') {
                        if ($event->id_event == $visitor->event_id) {
                            $ipAddress  = gethostbyname(explode("@", $visitor->email)[1]);
                            $emailEvent = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title_url', 'tbl_master_event.title')
                                ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                                ->where('tbl_send_email_cust.type', 'Event_Admin')
                                ->where('tbl_send_email_cust.id_event', $visitor->event_id)
                                ->first();
                            $bodyContent = $emailEvent['content'];
                            $bodyContent = str_replace(
                                [
                                    '#NamaUser',
                                    '#NamaEvent',
                                    '#LinkBarcode',
                                    '#StartEvent',
                                    '#EndEvent',
                                    '#StartRegistrasi',
                                    '#EndRegistrasi',
                                    '#AlamatEvent',
                                ],
                                [
                                    ucwords($visitor->full_name),
                                    ucwords($event->title),
                                    '<a href="' . route('visitor.event.qrcode', ['id' => encrypt($visitor->id)]) . '">di sini</a>',
                                    tgl_indo(date('Y-m-d', strtotime($event->start_event))),
                                    tgl_indo(date('Y-m-d', strtotime($event->end_event))),
                                    date('H:i', strtotime($event->start_registrasi)),
                                    date('H:i', strtotime($event->end_registrasi)),
                                    $event->location
                                ],
                                $bodyContent
                            );

                            if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                                $body = '<html>
                                        <head>
                                            <style type="text/css">
                                                body, td {
                                                    font-family: "Aptos", sans-serif;
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
                                        <body>
                                        ' . $bodyContent . '
                                        </body>
                                    </html>';

                                $mail = new PHPMailer(true);
                                try {
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
                                    $mail->addAddress($visitor->email, ucwords($visitor->full_name));

                                    $mail->isHTML(true);
                                    $mail->Subject = 'Event ' . ucwords($event->title);
                                    $mail->Body = $body;

                                    // $mail->SMTPDebug = 2;

                                    if ($mail->send()) {
                                        $visitor->flag_email = 1;
                                        $emailsSent++;
                                    } else {
                                        $visitor->flag_email = 0;
                                    }
                                } catch (Exception $e) {
                                    $visitor->flag_email = 0;
                                }

                                $visitor->save();
                            } else {
                                $visitor->flag_email = 0;
                                $visitor->save();
                            }
                        }
                    } else {
                        return response()->json(['message' => 'not_approved']);
                    }
                }
            }

            return response()->json([
                'message'        => 'Emails processed!',
                'emails_sent'    => $emailsSent
            ], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }

    public function sendWhatsappId($id)
    {
        $ids          = explode(',', $id);
        $whatsappSent = 0;

        if (!empty($ids)) {
            $visitors    = M_VisitorEvent::whereIn('id', $ids)->get();
            $masterEvent = DB::table('tbl_master_event')->select("*")->get();

            foreach ($visitors as $visitor) {
                foreach ($masterEvent as $event) {
                    if ($visitor->flag_approval == '1' && $event->id_event == $visitor->event_id) {
                        if (substr($visitor->mobile, 0, 1) === '0') {
                            $phoneNo = '+62' . substr($visitor->mobile, 1);
                        } elseif (substr($visitor->mobile, 0, 2) === '62') {
                            $phoneNo = '+' . $visitor->mobile;
                        } elseif (substr($visitor->mobile, 0, 1) !== '+') {
                            $phoneNo = '+62' . $visitor->mobile;
                        }

                        $dataContent = [
                            "data" => [
                                [
                                    "phone_no"        => $phoneNo,
                                    "customer_name"   => ucwords($visitor->full_name),
                                    "NamaEvent"       => ucwords($event->title),
                                    "Link"            => route('visitor.event.qrcode', ['id' => encrypt($visitor->id)]),
                                    "StartEvent"      => tgl_indo(date('Y-m-d', strtotime($event->start_event))),
                                    "EndEvent"        => tgl_indo(date('Y-m-d', strtotime($event->end_event))),
                                    "StartRegistrasi" => date('H:i', strtotime($event->start_registrasi)),
                                    "EndRegistrasi"   => date('H:i', strtotime($event->end_registrasi)),
                                    "AlamatEvent"     => $event->location,
                                ]
                            ]
                        ];

                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL            => 'https://prod-28.southeastasia.logic.azure.com:443/workflows/2b5e1f3507f041e9a49683569b5e14d1/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=dlJW7OOlZ3WBK5u1ZsVX9Vf2GI1edGcWVRnfL10kJ28',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING       => '',
                            CURLOPT_MAXREDIRS      => 10,
                            CURLOPT_TIMEOUT        => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST  => 'POST',
                            CURLOPT_POSTFIELDS     => json_encode($dataContent),
                            CURLOPT_HTTPHEADER     => [
                                'Content-Type: application/json'
                            ],
                        ]);

                        $response = curl_exec($curl);
                        curl_close($curl);

                        if (json_decode($response)->status == true) {
                            $visitor->flag_whatsapp = 1;
                            $whatsappSent++;
                        } else {
                            $visitor->flag_whatsapp = 0;
                            $whatsappSent++;
                        }
                        $visitor->save();
                    }
                }
            }

            return response()->json([
                'message'       => 'WhatsApp processed!',
                'whatsapp_sent' => $whatsappSent
            ], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }

    public function checkApproval(Request $request)
    {
        $ids = $request->input('ids');

        $unapprovedCount = M_VisitorEvent::whereIn('id', $ids)
            ->where(function ($query) {
                $query->where('flag_approval', '=', NULL)
                    ->orWhere('flag_approval', '=', 0);
            })
            ->count();

        if ($unapprovedCount > 0) {
            return response()->json(['status' => 'not_approved']);
        }

        return response()->json(['status' => 'approved']);
    }

    public function checkApprovalId($id)
    {
        $visitor = M_VisitorEvent::find($id);

        if (!$visitor) {
            return response()->json(['message' => 'Visitor not found'], 404);
        }

        if ($visitor->flag_approval != '1') {
            return response()->json(['message' => 'not_approved'], 200);
        }

        return response()->json(['message' => 'approved'], 200);
    }

    public function sendEmail(Request $request)
    {
        $ids           = $request->input('ids');
        $emailsSent    = 0;
        $totalSelected = count($ids);

        if (!empty($ids)) {
            $visitors    = M_VisitorEvent::whereIn('id', $ids)->get();
            $masterEvent = DB::table('tbl_master_event')->select("*")->get();

            foreach ($visitors as $visitor) {
                foreach ($masterEvent as $event) {
                    if ($event->id_event == $visitor->event_id) {
                        $ipAddress  = gethostbyname(explode("@", $visitor->email)[1]);
                        $emailEvent = M_SendEmailCust::select('tbl_send_email_cust.id', 'tbl_send_email_cust.content', 'tbl_send_email_cust.type', 'tbl_send_email_cust.id_event', 'tbl_master_event.title_url', 'tbl_master_event.title')
                            ->join('tbl_master_event', 'tbl_send_email_cust.id_event', '=', 'tbl_master_event.id_event')
                            ->where('tbl_send_email_cust.type', 'Event_Admin')
                            ->where('tbl_send_email_cust.id_event', $visitor->event_id)
                            ->first();
                        $bodyContent = $emailEvent['content'];
                        $bodyContent = str_replace(
                            [
                                '#NamaUser',
                                '#NamaEvent',
                                '#LinkBarcode',
                                '#StartEvent',
                                '#EndEvent',
                                '#StartRegistrasi',
                                '#EndRegistrasi',
                                '#AlamatEvent',
                            ],
                            [
                                ucwords($visitor->full_name),
                                ucwords($event->title),
                                '<a href="' . route('visitor.event.qrcode', ['id' => encrypt($visitor->id)]) . '">di sini</a>',
                                tgl_indo(date('Y-m-d', strtotime($event->start_event))),
                                tgl_indo(date('Y-m-d', strtotime($event->end_event))),
                                date('H:i', strtotime($event->start_registrasi)),
                                date('H:i', strtotime($event->end_registrasi)),
                                $event->location
                            ],
                            $bodyContent
                        );

                        if (filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                            $body = '<html>
                                        <head>
                                            <style type="text/css">
                                                body, td {
                                                    font-family: "Aptos", sans-serif;
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
                                        <body>
                                        ' . $bodyContent . '
                                        </body>
                                    </html>';

                            $mail = new PHPMailer(true);
                            try {
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
                                $mail->addAddress($visitor->email, ucwords($visitor->full_name));

                                $mail->isHTML(true);
                                $mail->Subject = 'Event ' . ucwords($event->title);
                                $mail->Body = $body;

                                // $mail->SMTPDebug = 2;

                                if ($mail->send()) {
                                    $visitor->flag_email = 1;
                                    $emailsSent++;
                                } else {
                                    $visitor->flag_email = 0;
                                }
                            } catch (Exception $e) {
                                $visitor->flag_email = 0;
                            }

                            $visitor->save();
                        } else {
                            $visitor->flag_email = 0;
                            $visitor->save();
                        }
                    }
                }
            }

            return response()->json([
                'message'        => 'Emails processed!',
                'emails_sent'    => $emailsSent,
                'total_selected' => $totalSelected
            ], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }

    public function sendWhatsapp(Request $request)
    {
        $ids           = $request->input('ids');
        $whatsappSent  = 0;
        $totalSelected = count($ids);

        if (!empty($ids)) {
            $visitors    = M_VisitorEvent::whereIn('id', $ids)->get();
            $masterEvent = DB::table('tbl_master_event')->select("*")->get();

            foreach ($visitors as $visitor) {
                foreach ($masterEvent as $event) {
                    if ($event->id_event == $visitor->event_id) {
                        if (substr($visitor->mobile, 0, 1) === '0') {
                            $phoneNo = '+62' . substr($visitor->mobile, 1);
                        } elseif (substr($visitor->mobile, 0, 2) === '62') {
                            $phoneNo = '+' . $visitor->mobile;
                        } elseif (substr($visitor->mobile, 0, 1) !== '+') {
                            $phoneNo = '+62' . $visitor->mobile;
                        }

                        $dataContent = [
                            "data" => [
                                [
                                    "phone_no"        => $phoneNo,
                                    "customer_name"   => ucwords($visitor->full_name),
                                    "NamaEvent"       => ucwords($event->title),
                                    "Link"            => route('visitor.event.qrcode', ['id' => encrypt($visitor->id)]),
                                    "StartEvent"      => tgl_indo(date('Y-m-d', strtotime($event->start_event))),
                                    "EndEvent"        => tgl_indo(date('Y-m-d', strtotime($event->end_event))),
                                    "StartRegistrasi" => date('H:i', strtotime($event->start_registrasi)),
                                    "EndRegistrasi"   => date('H:i', strtotime($event->end_registrasi)),
                                    "AlamatEvent"     => $event->location,
                                ]
                            ]
                        ];

                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL            => 'https://prod-28.southeastasia.logic.azure.com:443/workflows/2b5e1f3507f041e9a49683569b5e14d1/triggers/manual/paths/invoke?api-version=2016-06-01&sp=%2Ftriggers%2Fmanual%2Frun&sv=1.0&sig=dlJW7OOlZ3WBK5u1ZsVX9Vf2GI1edGcWVRnfL10kJ28',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING       => '',
                            CURLOPT_MAXREDIRS      => 10,
                            CURLOPT_TIMEOUT        => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST  => 'POST',
                            CURLOPT_POSTFIELDS     => json_encode($dataContent),
                            CURLOPT_HTTPHEADER     => [
                                'Content-Type: application/json'
                            ],
                        ]);

                        $response = curl_exec($curl);
                        curl_close($curl);

                        if (json_decode($response)->status == true) {
                            $visitor->flag_whatsapp = 1;
                            $whatsappSent++;
                        } else {
                            $visitor->flag_whatsapp = 0;
                            $whatsappSent++;
                        }
                        $visitor->save();
                    }
                }
            }

            return response()->json([
                'message'        => 'WhatsApp processed!',
                'whatsapp_sent'  => $whatsappSent,
                'total_selected' => $totalSelected
            ], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }

    public function storeArrival(Request $request)
    {
        if ($request->flagApproval == 0) {
            return response()->json([
                'success' => false,
                'message' => '<strong>' . $request->userName  . '</strong> has not yet approved. Please complete the approval first.'
            ]);
        } else {
            $request->validate([
                'visitorId'   => 'required|exists:tbl_visitor_event,id',
                'dateArrival' => 'required|date',
            ]);

            try {
                $visitor = M_VisitorEvent::findOrFail($request->visitorId);

                $visitor->scan_date = Carbon::now();
                $visitor->flag_qr   = 1;

                $visitor->save();

                return response()->json(['success' => true, 'message' => 'Arrival details saved successfully!']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function approval(Request $request)
    {
        try {
            DB::table('tbl_visitor_event')
                ->where('id', $request->approvalId)
                ->update([
                    'flag_approval' => '1',
                    'approve_by'    => Auth::user()->username,
                    'approve_date'  => Carbon::now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => '<strong>' . $request->userName  . '</strong> has been approved'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
