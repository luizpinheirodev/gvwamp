<?php

namespace gv\Http\Controllers\Auth;

use Auth;
use Session;
use gv\User;
use gv\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use gv\Http\Requests\LoginCustomRequest;
//use gv\Http\Requests\Auth\LoginRequest;
//use gv\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller {

    public function login(LoginCustomRequest $request)
    {
        $usuario = User::where('email','=',$request->email)->first(); 
        if($usuario == null){
            return \Redirect::back();

        }else{
            if($usuario->LDAP=="N"){
                $credentials = $request->only('email', 'password');
    
                if (Auth::attempt($credentials)) {
                    return redirect()->intended('home');
                }else{
                    return \Redirect::back();
                }
            }else{
                $ldapusr = $_POST['email'];
                $ldapini = substr($ldapusr,0,1); 
                if (empty($ldaprdn)){
                    $ldaprdn = 'uid='.$ldapusr.',cn='.$ldapini.',cn=users,dc=sicredi,dc=com,dc=br'; 
                    //dd($ldaprdn);
                }
                $ldappass = isset($_POST['password']) ? $_POST['password'] : 'A';
                $ldapconn = ldap_connect("ldap.sicredi.net")
                            or die("Could not connect to LDAP server."); 
                if ($ldapconn) {
    
                    $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass);
                    if ($ldapbind) {
                                $expire=time()+60*60;
                                setcookie("user", $ldapusr, $expire, "/"); 
                                $_SESSION['login_user']=$ldapusr;
                    } else {
                        $errors = [$this->username() => trans('auth.failed')];
                        return \Redirect::back()
                        ->withInput($request->only($this->username())) 
                        ->withErrors($errors);
                    }
                }
    
                $user = User::where('email','=',$request->email)->first(); 
                if($user)  {
                    Auth::login($user);
                    return redirect()->intended('home');
                }else{
                    $errors = [$this->username() => trans('auth.failed')];
                    return \Redirect::back()
                            ->withInput($request->only($this->username())) 
                            ->withErrors($errors);
                }
    
            }




        }
        
    }
    public function username()
    {
        return 'email';
    }
    
    public function logout()
    {
        Session::flush();
        return \Redirect::back();
        //Auth::logout();
    }

    public function telaLogin()
    {
        return view('auth.login');
    }

}
