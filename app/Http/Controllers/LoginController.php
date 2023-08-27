<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\M_User;
use App\Models\M_MasterEvent;
use App\Models\M_AdminEvent;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }

    public function index_parameter($page)
    {
        $page = strtolower($page);
        $masterEvent = M_MasterEvent::select('*')->where('status', 'A')->where('title_url', $page)->get()->toArray();

        if ($page == "dashboard") {
            return view('login.index', [
                'page' => $page,
                'masterEvent' => $masterEvent
            ]);
        } else if (!empty($masterEvent)) {
            return view('login.index', [
                'page' => $page,
                'masterEvent' => $masterEvent
            ]);
        } else if (empty($masterEvent) && !empty($page)) {
            return abort(404);
        }
    }

    public function login_action(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $title = $request->title == null ? 'cms' : $request->title;
        $selectedPage = $request->query('selected_page', $title);
        // $user = M_User::where('username', $credentials['username'])->first();
        $user = '';
        $user_2 = M_AdminEvent::where('username', $credentials['username'])->first();
        // dd($user);
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
        } else if (empty($user) && empty($user_2)) {
            if ($title == "cms") {
                return redirect()->route('login')->withErrors(['message' => 'Username tidak ditemukan, silahkan coba lagi']);
            } else {
                return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Username tidak ditemukan, silahkan coba lagi']);
            }
        } else if ($user && password_verify($credentials['password'], $user->password)) {
            Auth::login($user);
            return redirect()->route('dashboard', ['page' => $selectedPage]);
        } else if ($user_2 && password_verify($credentials['password'], $user_2->password)) {
            Auth::login($user_2);
            return redirect()->route('visitor_event.index', ['page' => $selectedPage]);
        } else {
            if ($title == "cms") {
                return redirect()->route('login')->withErrors(['message' => 'Password anda salah, silahkan coba lagi']);
            } else {
                return redirect()->route('login_param', ['page' => $selectedPage])->withErrors(['message' => 'Password anda salah, silahkan coba lagi']);
            }
        }
    }

    public function register($page)
    {
        if ($page == "cms") {
            return view('register.index');
        } else {
            return abort(404);
        }
    }

    public function register_action(Request $request)
    {
        $checkUser =  M_User::select('*')->get()->toArray();
        $passwordLength = strlen($request->password);

        if (count($checkUser) == 1) {
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
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'full_name' => $request->full_name,
                'created_at' => now(),
                'updated_at' => now(),
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
