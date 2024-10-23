<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\M_MasterEvent;
use App\Models\M_User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\PHPMailer;

class AdminEventController extends Controller
{
    function index($page)
    {
        $type_menu   = 'admin_event';
        $data        = adminEvent($page);
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];

        if (!empty($masterEvent) && $userId == Auth::user()->id || $page == "cms") {
            return view('admin_event.index', [
                'id'          => $userId,
                'masterEvent' => $masterEvent,
                'data'        => $data,
                'type_menu'   => $type_menu,
                'pages'       => $page
            ]);
        } else {
            return view('error.error-404');
        }
    }

    function add_admin_index($page)
    {
        $type_menu   = 'admin_event';
        $masterEvent = masterEvent($page);
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';

        if ($page == "cms") {
            $data = M_MasterEvent::select('*')->where('status', 'A')->get();
        } else {
            $data = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get();
        }

        return view('admin_event.add-admin-event', [
            'id'          => $userId,
            'titleUrl'    => $titleUrl,
            'data'        => $data,
            'masterEvent' => $masterEvent,
            'type_menu'   => $type_menu
        ]);
    }

    public function add(Request $request)
    {
        $checkUser = M_User::select('*')->where('username', $request->username)->where('event_id', $request->event)->first();
        $titleUrl  = M_MasterEvent::select('*')->where('id_event', $request->event)->first();

        if (str_contains($request->username, ' ')) {
            return response()->json(['message' => 'failed_space']);
        } else if (!empty($checkUser)) {
            return response()->json(['message' => 'failed']);
        } else {
            DB::table('tbl_user')->insert([
                'username'          => $request->username,
                'password'          => Hash::make($request->password),
                'full_name'         => trim(ucwords($request->nama_lengkap)),
                'event_id'          => $request->event,
                'status'            => $request->status,
                'created_at'        => now(),
                'updated_at'        => now(),
                'password_encrypts' => Crypt::encryptString($request->password),
                'title_url'         => $titleUrl->title_url,
                'email'             => $request->email
            ]);

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
                        <body>Dear <strong>' . ucwords($request->nama_lengkap) . '</strong> <br />
                        Anda telah ditambahkan sebagai administrator pada <strong>Event ' . ucwords($titleUrl->title) . '</strong>, silahkan login menggunakan<br />
                        Username : ' . $request->username . '<br />
                        Password : ' . $request->password . '<br /><br />
                        Klik <a href="' . route('login_param', ['page' => $titleUrl->title_url]) . '">di sini</a> untuk menuju halaman Event.<br /><br />
                        Terima Kasih
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
            $mail->addAddress($request->email, $request->full_name);

            $mail->isHTML(true);
            $mail->Subject = 'Event ' . ucwords($titleUrl->title);
            $mail->Body    = $body;
            $mail->send();

            return response()->json(['message' => 'success']);
        }
    }

    public function edit()
    {
        $page        = request('page');
        $id          = request('id');
        $type_menu   = 'admin_event';
        $data        = M_User::select('*')->where('id', $id)->get();
        $event       = masterEvent_1();
        $user        = userAdmin();
        $userId      = $user[0]['id'];
        $masterEvent = masterEvent($page);

        foreach ($data as $value) {
            $val[] = [
                'admin_id'          => $value->id,
                'event_id'          => $value->event_id,
                'username'          => $value->username,
                'password'          => $value->password,
                'full_name'         => $value->full_name,
                'status'            => $value->status,
                'created_at'        => $value->created_at,
                'updated_at'        => $value->updated_at,
                'email'             => $value->email,
                'password_encrypts' => Crypt::decryptString($value->password_encrypts),
            ];
        }

        return view('admin_event.edit-admin-event', [
            'titleUrl'    => $page,
            'id'          => $userId,
            'masterEvent' => $masterEvent,
            'data'        => $val,
            'type_menu'   => $type_menu,
            'event'       => $event
        ]);
    }

    public function update(Request $request)
    {
        $titleUrl = M_MasterEvent::select('*')->where('id_event', $request->event == "null" ? '0' : $request->event)->first();

        DB::table('tbl_user')
            ->where('id', $request->admin_id)
            ->update([
                'event_id'          => $request->event == "null" ? '0' : $request->event,
                'username'          => $request->username,
                'password'          => Hash::make($request->password),
                'full_name'         => $request->nama_lengkap,
                'status'            => $request->status,
                'created_at'        => now(),
                'updated_at'        => now(),
                'password_encrypts' => Crypt::encryptString($request->password),
                'title_url'         => $titleUrl == NULL ? '0' : $titleUrl->title_url,
            ]);

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
                    <body>Dear <strong>' . ucwords($request->nama_lengkap) . '</strong> <br />
                    Data anda telah di update pada <strong>Event ' . ucwords($titleUrl->title) . '</strong>, silahkan login menggunakan<br />
                    Username : ' . $request->username . '<br />
                    Password : ' . $request->password . '<br /><br />
                    Klik <a href="' . route('login_param', ['page' => $titleUrl->title_url]) . '">di sini</a> untuk menuju halaman Event.<br /><br />
                    Terima Kasih
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
        $mail->addAddress($request->email, $request->full_name);

        $mail->isHTML(true);
        $mail->Subject = 'Event ' . ucwords($titleUrl->title);
        $mail->Body    = $body;
        $mail->send();

        return response()->json(['message' => 'success']);
    }

    public function delete($id)
    {
        $data = M_User::find($id);

        if ($data) {
            $data->delete();
            return response()->json(['message' => 'success']);
        } else {
            return response()->json(['error' => 'failed'], 404);
        }
    }
}
