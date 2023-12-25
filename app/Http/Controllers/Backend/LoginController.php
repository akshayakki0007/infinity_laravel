<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use App\Models\User;


class LoginController extends Controller
{
    public function __construct()
    {
        $this->ViewData = [];
        $this->JsonData = [];

        $this->ModuleTitle = 'Login';
        $this->ModuleView  = 'Backend/auth.';
        $this->ModulePath  = 'admin/login';
    }

    public function index()
    {
        $this->ViewData['modulePath'] = $this->ModulePath;
        $this->ViewData['moduleTitle'] = $this->ModuleTitle;
        $this->ViewData['moduleAction'] = 'Login '.$this->ModuleTitle;
        
        return view($this->ModuleView.'login', $this->ViewData);
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
           'email'        => 'required',
           'password'     => 'required',
        ],[
           'email.required'        => 'Email Id Or Username field is required.',
           'password.required'     => 'Password field is required.',
        ]);

        $credentials['status']   = '0';
        $credentials['role']     = array('super_admin','admin','sales','seo','data','research');
        $credentials['email']    = $request->email;
        $credentials['password'] = $request->password;
        
        if(Auth::attempt($credentials,$request->filled('remember_me')))
        {
            $this->JsonData['status'] = 'success';
            $this->JsonData['url']    = url('/admin/dashboard');
            $this->JsonData['msg']    = 'Login successfully.';

            return response()->json($this->JsonData);
        }
        else
        {
            $userData = User::where('email',$request->email)->first();
            
            if(!empty($userData))
            {
                if($userData->status != '0')
                {
                    $this->JsonData['status'] = 'error';
                    $this->JsonData['msg']    = 'This user has not been activated yet.';
                }
                else
                {
                    $this->JsonData['status'] = 'error';
                    $this->JsonData['msg']    = 'Invalid Credentials';
                }
            }
            else
            {
                $this->JsonData['status'] = 'error';
                $this->JsonData['msg']    = 'User does not exist.';
            }
        }
        
        return response()->json($this->JsonData);        
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}
