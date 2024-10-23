<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\M_MasterUser;
use App\Models\M_CompanyEvent;
use App\Models\M_SendEmailCust;
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
        $emailEvent = M_SendEmailCust::select('*')->where('type', 'CMS_Admin')->first();
        $masterUser = M_MasterUser::select('*')->where('id', $id)->first();

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
                        Dear <strong>' . ucwords($masterUser['name']) . '</strong> <br /><br />
                        ' . $emailEvent['content'] . '
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
        $mail->addAddress($masterUser->email, ucwords($masterUser->name));

        $mail->isHTML(true);
        $mail->Subject = 'Menu Master User Testing Email ';
        $mail->Body    = $body;
        $mail->send();

        return response()->json(['emails_sent' => 1, 'message' => 'success']);
    }

    public function sendEmail(Request $request)
    {
        $ids           = $request->input('ids');
        $emailsSent    = 0;
        $totalSelected = count($ids);

        if (!empty($ids)) {
            $masterUser = M_MasterUser::select('*')->whereIn('id', $ids)->get();
            $emailEvent = M_SendEmailCust::select('*')->where('type', 'CMS_Admin')->get();

            foreach ($masterUser as $user) {
                foreach ($emailEvent as $event) {
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
                                    Dear <strong>' . ucwords($user['name']) . '</strong> <br /><br />
                                    ' . $event['content'] . '
                                </body>
                            </html>';

                    try {
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
                        $mail->addAddress($user['email'], $user['name']);

                        $mail->isHTML(true);
                        $mail->Subject = 'Menu Master User Testing Email Selected';
                        $mail->Body = $body;

                        if ($mail->send()) {
                            $emailsSent++;
                        }
                    } catch (Exception $e) {
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
        //
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
