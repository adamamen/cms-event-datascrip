<?php

use Carbon\Carbon;
use App\Models\M_MasterEvent;
use App\Models\M_CompanyEvent;
use App\Models\M_User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

if (!function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (!function_exists('masterEvent')) {
    function masterEvent($page)
    {
        $q = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();
        return $q;
    }
}

if (!function_exists('masterEvent_1')) {
    function masterEvent_1()
    {
        $q = M_MasterEvent::select('*')->get();
        return $q;
    }
}

if (!function_exists('masterEvent_2')) {
    function masterEvent_2($page)
    {
        $q = M_MasterEvent::select('*')->where('title_url', $page)->get();
        return $q;
    }
}

if (!function_exists('masterEvent_3')) {
    function masterEvent_3()
    {
        $q = M_MasterEvent::select('*')->where('status', 'A')->get();
        return $q;
    }
}

if (!function_exists('masterEvent_4')) {
    function masterEvent_4($page)
    {
        $q = M_MasterEvent::select('*')->where('title_url', $page)->where('status', 'A')->get();
        return $q;
    }
}

if (!function_exists('userAdmin')) {
    function userAdmin()
    {
        $q = M_User::select('*')->where('event_id', '0')->get()->toArray();
        return $q;
    }
}

if (!function_exists('visitorEvent')) {
    function visitorEvent()
    {
        $q = DB::table('tbl_visitor_event')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'event_id', 'registration_date', 'full_name', 'address', 'email', 'mobile', 'created_at', 'ticket_no', 'created_by', 'updated_by', 'updated_at', 'jenis_event', 'no_invoice', 'status_pembayaran', 'metode_bayar', 'sn_product')
            ->get();
        return $q;
    }
}

if (!function_exists('companyEvent')) {
    function companyEvent()
    {
        $q = M_CompanyEvent::select('*')->get();
        return $q;
    }
}

if (!function_exists('tgl_indo')) {
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
}

if (!function_exists('listDivisi')) {
    function listDivisi()
    {
        $q = M_CompanyEvent::select('*')->where('status', 'A')->get();
        return $q;
    }
}

if (!function_exists('validRecaptcaV3')) {
    function validRecaptcaV3()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $data = [
            'token' => $_POST['tokens'],
            'from_ip' => $ip,
            'app_name' => 'CMS EVENT' //diganti sesuai nama aplikasi
        ];

        $headers = array(
            "Content-type: application/x-www-form-urlencoded",
            'Content-Length: ' . strlen(http_build_query($data))
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, "https://intraweb.datascrip.co.id/recaptcha/ValidationRecaptca.php");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $json = curl_exec($ch);
        if (!$json) $json = 'gagal: ' . curl_error($ch);
        curl_close($ch);

        return json_decode($json, true);
    }
}

if (!function_exists('adminEvent')) {
    function adminEvent($page)
    {
        $queryAdminEvent = DB::table('tbl_user')
            ->select(DB::raw('ROW_NUMBER() OVER (Order by id) AS RowNumber'), 'id', 'event_id', 'username', 'password', 'full_name', 'status', 'created_at', 'updated_at', 'password_encrypts')
            // ->where('event_id', '<>', '0')
            ->get();
        if ($page == "cms") {
            $queryMasterEvent = masterEvent_1();
        } else {
            $queryMasterEvent = masterEvent_2($page);
        }

        if (!empty($queryAdminEvent) && !empty($queryMasterEvent)) {
            foreach ($queryAdminEvent as $admin) {
                if ($admin->event_id == '0') {
                    $merge[] =
                        [
                            'RowNumber' => $admin->RowNumber,
                            'admin_id' => $admin->id,
                            'event_id' => $admin->event_id,
                            'username' => $admin->username,
                            'password' => $admin->password,
                            'full_name' => $admin->full_name,
                            'status' => $admin->status,
                            'password_encrypts' => Crypt::decryptString($admin->password_encrypts),
                        ];
                }
                foreach ($queryMasterEvent as $event) {
                    if ($admin->event_id == $event->id_event) {
                        $merge[] = [
                            'RowNumber' => $admin->RowNumber,
                            'admin_id' => $admin->id,
                            'event_id' => $admin->event_id,
                            'username' => $admin->username,
                            'password' => $admin->password,
                            'full_name' => $admin->full_name,
                            'status' => $admin->status,
                            'title' => $event->title,
                            'password_encrypts' => Crypt::decryptString($admin->password_encrypts),
                            'updated_by' => $event->updated_by,
                            'updated_at' => $event->updated_at,
                            'created_by' => $event->created_by,
                            'created_at' => $event->created_at,
                            'title_url' => $event->title_url,
                        ];
                    }
                }
            }
        }
        // dd($merge);
        $merge = !empty($merge) ? $merge : [];

        return $merge;
    }

    if (!function_exists('visitorEventandMasterEvent')) {
        function visitorEventandMasterEvent($page)
        {
            $queryVisitorEvent = visitorEvent();
            if ($page == "cms") {
                $queryMasterEvent = masterEvent_1();
            } else {
                $queryMasterEvent = masterEvent_2($page);
            }

            if (!empty($queryVisitorEvent) && !empty($queryMasterEvent)) {
                foreach ($queryVisitorEvent as $visitor) {
                    foreach ($queryMasterEvent as $event) {
                        if ($visitor->event_id == $event->id_event) {
                            $merge[] = [
                                'id' => $visitor->id,
                                'event_id' => $visitor->event_id,
                                'RowNumber' => $visitor->RowNumber,
                                'title' => $event->title,
                                'full_name' => $visitor->full_name,
                                'mobile' => $visitor->mobile,
                                'ticket_no' => $visitor->ticket_no,
                                'email' => $visitor->email,
                                'registration_date' => $visitor->registration_date,
                                'address' => $visitor->address,
                                'created_by' => $visitor->created_by,
                                'created_at' => $visitor->created_at,
                                'updated_by' => $visitor->updated_by,
                                'updated_at' => $visitor->updated_at,
                                'title_url' => $event->title_url,
                                // 'jenis_event' => $event->jenis_event,
                                'no_invoice' => $visitor->no_invoice,
                                'status_pembayaran' => $visitor->status_pembayaran,
                                'metode_bayar' => $visitor->metode_bayar,
                                'sn_product' => $visitor->sn_product,
                                'jenis_event' => $visitor->jenis_event,
                                'no_ticket' => $visitor->ticket_no
                            ];
                        }
                    }
                }
            }

            $merge = !empty($merge) ? $merge : [];

            return $merge;
        }
    }
}
