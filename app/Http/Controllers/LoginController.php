<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\M_User;
use App\Models\M_MasterEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $checkId = M_User::select('*')->where('id', $user->id)->first();

            if ($checkId && $checkId->event_id == 0) {
                return redirect()->route('dashboard', ['page' => 'cms']);
            }
        }

        return view('login.index');
    }

    public function index_parameter($page)
    {
        $page                      = strtolower($page);
        $tanggal_terakhir_aplikasi = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->first();
        $tgl_1                     = !empty($tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi) ? strtotime(date('Y-m-d', strtotime($tanggal_terakhir_aplikasi->tanggal_terakhir_aplikasi . "+1 days"))) : '';
        $tgl_2                     = strtotime(date('Y-m-d'));

        if ($tgl_1 > $tgl_2) {
            $masterEvent = M_MasterEvent::select('*')
                ->where('status', 'A')
                ->where('title_url', $page)
                ->get()->toArray();
        }

        if (Auth::check()) {
            return redirect()->route('visitor_event.index', ['page' => $page]);
        }

        if ($page == "dashboard") {
            return view('login.index', [
                'page'        => $page,
                'masterEvent' => $masterEvent
            ]);
        } else if (!empty($masterEvent)) {
            return view('login.index', [
                'page'        => $page,
                'masterEvent' => $masterEvent
            ]);
        } else if (empty($masterEvent) && !empty($page)) {
            return view('error.error-404');
        } else {
            return view('error.error-404');
        }
    }

    public function login_action(Request $request)
    {
        $credentials  = $request->only('username', 'password');
        $title        = $request->title == null ? 'cms' : $request->title;
        $selectedPage = $request->query('selected_page', $title);
        $masterEvent  = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $selectedPage)->get();
        $idEvent      = M_User::select('*')->where('username', $credentials['username'])->where('status', 'A')->first();
        $response     = validRecaptcaV3();

        if ($idEvent != null) {
            if ($idEvent->event_id == '0') {
                $user = M_User::select('*')->where('username', $credentials['username'])->where('status', 'A')->get();
            } else {
                $user = M_User::select('*')->where('username', $credentials['username'])->where('title_url', $selectedPage)->where('status', 'A')->get();
            }
        } else {
            $user = '';
        }

        if ($user == "") {
            if ($response['success']) {
                if (empty($credentials['username']) && empty($credentials['password'])) {
                    if ($title == "cms") {
                        return redirect()->route('login')->withErrors(['message' => 'Username dan Password tidak boleh kosong, silahkan coba lagi']);
                    } else {
                        return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username dan Password tidak boleh kosong, silahkan coba lagi']);
                    }
                } else if (empty($credentials['username'])) {
                    if ($title == "cms") {
                        return redirect()->route('login')->withErrors(['message' => 'Username tidak boleh kosong, silahkan coba lagi']);
                    } else {
                        return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username tidak boleh kosong, silahkan coba lagi']);
                    }
                } else if (empty($credentials['password'])) {
                    if ($title == "cms") {
                        return redirect()->route('login')->withErrors(['message' => 'Password tidak boleh kosong, silahkan coba lagi']);
                    } else {
                        return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Password tidak boleh kosong, silahkan coba lagi']);
                    }
                } else {
                    if ($title == "cms") {
                        return redirect()->route('login')->withErrors(['message' => 'Username tidak ditemukan, silahkan coba lagi']);
                    } else {
                        return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username tidak ditemukan, silahkan coba lagi']);
                    }
                }
            }
        } else {
            if (!empty($user->toArray())) {
                foreach ($user as $user) {
                    if ($user && password_verify($credentials['password'], $user->password)) {
                        if ($user->event_id == 0 && $title == "cms") {
                            Auth::login($user);
                            return redirect()->route('dashboard', ['page' => $selectedPage]);
                        } else if ($user->event_id > 0 && $title == "cms") {
                            return redirect()->route('login')->withErrors(['message' => 'Hanya user Admin yang berhak untuk login']);
                        } else if ($user->event_id > 0 && $title != "cms") {
                            foreach ($masterEvent as $value) {
                                if ($value->id_event == $user->event_id) {
                                    Auth::login($user);
                                    return redirect()->route('visitor_event.index', ['page' => $selectedPage]);
                                } else {
                                    return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username tidak ditemukan, silahkan coba lagi']);
                                }
                            }
                        } else {
                            $q = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $selectedPage)->first();
                            if ($user->event_id == 0 && empty($user->divisi)) {
                                Auth::login($user);
                                return redirect()->route('dashboard', ['page' => $selectedPage]);
                            } else if ($user->event_id == 0 && ($q->company != $user->divisi)) {
                                return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'User anda tidak berhak untuk login di event ini']);
                            } else {
                                Auth::login($user);
                                return redirect()->route('dashboard', ['page' => $selectedPage]);
                            }
                        }
                    } else {
                        if ($title == "cms") {
                            return redirect()->route('login')->withErrors(['message' => 'Password anda salah, silahkan coba lagi']);
                        } else {
                            return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Password anda salah, silahkan coba lagi']);
                        }
                    }
                }
            } else {
                if ($selectedPage == "cms") {
                    return redirect()->route('login')->withErrors(['message' => 'Username anda tidak berhak untuk login, silahkan coba lagi']);
                } else {
                    return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username anda tidak berhak untuk login, silahkan coba lagi']);
                }
            }
        }
    }

    public function register($page)
    {
        if ($page == "cms") {
            return view('register.index');
        } else {
            return view('error.error-404');
        }
    }

    public function register_action(Request $request)
    {
        $checkUser      = M_User::where('event_id', '=', '0')->whereNull('divisi')->first();
        $checkUsers     = $checkUser == null ? '' : $checkUser->username;
        $passwordLength = strlen($request->password);

        if ($checkUsers != "") {
            return response()->json(['message' => 'user']);
        } else if (empty($request->username)) {
            return response()->json(['message' => 'username']);
        } else if (empty($request->password)) {
            return response()->json(['message' => 'passwordEmpty']);
        } else if (empty($request->full_name)) {
            return response()->json(['message' => 'full_name']);
        } else if (empty($request->no_handphone)) {
            return response()->json(['message' => 'no_handphone']);
        } else if ($passwordLength < 8) {
            return response()->json(['message' => 'password']);
        } else if ($request->password != $request->password_confirmation) {
            return response()->json(['message' => 'password doesnt same']);
        } else {
            DB::table('tbl_user')->insert([
                'event_id'          => '0',
                'username'          => $request->username,
                'password'          => Hash::make($request->password),
                'full_name'         => $request->full_name,
                'status'            => 'A',
                'created_at'        => now(),
                'updated_at'        => now(),
                'password_encrypts' => Crypt::encryptString($request->password),
                'title_url'         => 'cms',
            ]);

            return response()->json(['message' => 'success']);
        }
    }

    public function logout(Request $request, $page)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($page == "cms") {
            return redirect('/');
        } else {
            return redirect()->route('login_param', ['page' => $page]);
        }
    }
}
