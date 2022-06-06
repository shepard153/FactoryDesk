<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Check if user agent is IE. If true, redirect to error page.
     *
     * @return view
     */
    public function index()
    {
        if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT'])){
            return view('errors/ie');
        }
        return view('login');
    }

    /**
     * Login action. If user credentials are true, redirect to dashboard.
     *
     * @param Request $request
     * @return View
     */
    public function loginAction(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        $credentials = array($fieldType => $request->login, 'password' => $request->password);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'error' => 'Podano nieprawidłowy login lub hasło',
        ]);
    }

    /**
     * Logout user and redirect to login page.
     *
     * @param Request $request
     * @return view
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}

