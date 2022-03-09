<?php

    namespace app\Http\Controllers;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Session;
    use Illuminate\Support\Facades\Auth;

    Class LoginController extends Controller
    {
        public function index()
        {
            if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) || preg_match('~Trident/7.0(; Touch)?; rv:11.0~',$_SERVER['HTTP_USER_AGENT'])){
                return view('errors/ie');
            }
            return view('login');
        }

        public function loginAction(Request $request)
        {
            $credentials = $request->validate([
                'login' => ['required'],
                'password' => ['required'],
            ]);
     
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
     
                return redirect()->intended('dashboard');
            }
     
            return back()->withErrors([
                'error' => 'Podano nieprawidłowy login lub hasło',
            ]);
        }

        public function logout(Request $request)
        {
            Auth::logout();
 
            $request->session()->invalidate();
         
            $request->session()->regenerateToken();
         
            return redirect('login');
        }
    }
    
