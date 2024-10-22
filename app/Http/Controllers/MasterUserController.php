<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\M_MasterUser;
use App\Models\M_CompanyEvent;
use App\Models\M_VisitorEvent;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MasterUserController extends Controller
{
    function index($page)
    {
        $type_menu   = 'master_user';
        $data        = M_MasterUser::select('*')->get();
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        return view('master_user.index', [
            'type_menu' => $type_menu,
            'page'      => $page,
            'data'      => $data,
            'titleUrl'  => $titleUrl,
        ]);
    }

    public function edit()
    {
        $data        = M_MasterUser::select('*')->where('id', request('id'))->get();
        $masterEvent = masterEvent(request('page'));
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $title       = str_replace('-', ' ', $titleUrl);
        $type_menu   = 'master_user';
        $listDivisi  = listDivisi();

        if (!empty($masterEvent) || request('page') == "cms") {
            return view('master_user.edit-master-user', [
                'data'       => $data,
                'titleUrl'   => $titleUrl,
                'title'      => $title,
                'type_menu'  => $type_menu,
                'listDivisi' => $listDivisi,
            ]);
        } else {
            return view('error.error-404');
        }
    }

    public function update(Request $request)
    {
        $name_divisi = M_CompanyEvent::select('*')->where('id', $request->division)->first();

        DB::table('mst_cust')
            ->where('id', $request->id)
            ->update([
                'name'             => $request->name,
                'gender'           => $request->gender,
                'email'            => $request->email,
                'institution'      => $request->institution,
                'name_institution' => $request->institution_name,
                'phone_no'         => $request->whatsapp_number,
                'city'             => $request->city,
                'id_divisi'        => $request->division,
                'name_divisi'      => $name_divisi->name,
                'update_by'        => $request->username,
                'update_date'      => Carbon::now(),
            ]);

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_MasterUser::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed']);
        }
    }

    public function deleteMultipleVisitors(Request $request)
    {
        $ids = $request->ids;

        if (!empty($ids)) {
            DB::table('mst_cust')->whereIn('id', $ids)->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No IDs selected']);
    }

    public function sendEmailId($id)
    {
        $ids        = explode(',', $id);
        $emailsSent = 0;

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
                        $domain          = explode("@", $email)[1];
                        $ipAddress       = gethostbyname($domain);

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
                                        <body>Bapak/Ibu, <br />
                                        <strong>' . $nama . '</strong><br />
                                        Terima kasih sudah melakukan Registrasi pada acara ' . $judul . ' <br /><br />
                                        Silahkan gunakan QR Code terlampir untuk diperlihatkan pada saat registrasi. Klik <a href="' . route('visitor.event.qrcode', ['id' => $encryptedId]) . '">di sini</a> untuk melihat QR Code.<br /><br />
                                        
                                        <strong>Tanggal Acara</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $tanggalMulai . ' s/d ' . $tanggalAkhir . ' <br />
                                        <strong>Mulai Registrasi</strong>&nbsp; &nbsp; &nbsp; : ' . $mulaiRegistrasi . ' <br />
                                        <strong>Selesai Registrasi</strong> &nbsp;: ' . $akhirRegistrasi . ' <br />
                                        <strong>Tempat</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $event->location . ' <br /><br />
    
                                        <strong>Syarat & Ketentuan</strong><br />
                                        - QR Code hanya bisa digunakan 1x pada event. <br />
                                        - QR Code Tidak boleh diperjual-belikan. <br />
                                        - Segala tindak kecurangan bukan tanggung jawab penyelenggara event. <br />
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
                                $mail->addAddress($email, $nama);

                                $mail->isHTML(true);
                                $mail->Subject = 'Event ' . $judul;
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
                'emails_sent'    => $emailsSent
            ], 200);
        }

        return response()->json(['message' => 'No visitors selected'], 400);
    }

    public function sendEmail(Request $request)
    {
        $ids           = $request->input('ids');
        $emailsSent    = 0;
        $totalSelected = count($ids);

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
                        $domain          = explode("@", $email)[1];
                        $ipAddress       = gethostbyname($domain);

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
                                        <body>Bapak/Ibu, <br />
                                        <strong>' . $nama . '</strong><br />
                                        Terima kasih sudah melakukan Registrasi pada acara ' . $judul . ' <br /><br />
                                        Silahkan gunakan QR Code terlampir untuk diperlihatkan pada saat registrasi. Klik <a href="' . route('visitor.event.qrcode', ['id' => $encryptedId]) . '">di sini</a> untuk melihat QR Code.<br /><br />
                                        
                                        <strong>Tanggal Acara</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $tanggalMulai . ' s/d ' . $tanggalAkhir . ' <br />
                                        <strong>Mulai Registrasi</strong>&nbsp; &nbsp; &nbsp; : ' . $mulaiRegistrasi . ' <br />
                                        <strong>Selesai Registrasi</strong> &nbsp;: ' . $akhirRegistrasi . ' <br />
                                        <strong>Tempat</strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : ' . $event->location . ' <br /><br />
    
                                        <strong>Syarat & Ketentuan</strong><br />
                                        - QR Code hanya bisa digunakan 1x pada event. <br />
                                        - QR Code Tidak boleh diperjual-belikan. <br />
                                        - Segala tindak kecurangan bukan tanggung jawab penyelenggara event. <br />
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
                                $mail->addAddress($email, $nama);

                                $mail->isHTML(true);
                                $mail->Subject = 'Event ' . $judul;
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

    public function template_excel_master_user()
    {
        $filePath = public_path('library/template-excel/Template Excel Master User.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Template Excel Event Master User.xlsx');
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
            return response()->json(['message' => 'File validation failed.'], 400);
        }

        $file = $request->file('excel_file');
        $rows = Excel::toArray([], $file)[0];
        unset($rows[0]);

        $countImported = 0;
        $divisiNames   = [];

        foreach ($rows as $row) {
            $divisiName    = trim($row[6]);
            $divisiNames[] = $divisiName;
        }

        $validDivisi   = M_CompanyEvent::pluck('name')->toArray();
        $missingDivisi = array_diff($divisiNames, $validDivisi);

        if (!empty($missingDivisi)) {
            return response()->json(['message' => 'The following Division Names are not available: ' . implode(', ', $missingDivisi)], 400);
        }

        $divisiData = M_CompanyEvent::whereIn('name', $divisiNames)
            ->pluck('id', 'name')->toArray();

        foreach ($rows as $row) {
            if (strlen(trim($row[2])) !== 1) {
                return response()->json(['message' => 'Gender must be a single character.'], 400);
            }

            $id_divisi = $divisiData[trim($row[6])];

            DB::table('mst_cust')->insert([
                'name'             => $row[1],
                'gender'           => $row[2],
                'email'            => $row[3],
                'phone_no'         => $row[4],
                'city'             => $row[5],
                'name_divisi'      => $row[6],
                'institution'      => $row[7],
                'name_institution' => $row[8],
                'participant_type' => NULL,
                'id_divisi'        => $id_divisi,
                'upload_by'        => Auth::user()->username,
                'upload_date'      => Carbon::now(),
                'update_by'        => NULL,
                'update_date'      => NULL,
            ]);

            $countImported++;
        }

        return response()->json(['message' => 'Data successfully imported', 'count' => $countImported]);
    }
}
