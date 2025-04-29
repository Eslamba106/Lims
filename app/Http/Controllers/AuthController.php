<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Config;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            Config::set('database.default', 'mysql');
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::setDefaultConnection('mysql');
               
            $request->validate([
                'tenant_id' => 'required',
                'domain'     => 'required',
                'user_name'  => 'required',
                'password'   => 'required',
            ]);
            
            $tenant = (new Tenant()) 
                ->where('tenant_id', $request->tenant_id)
                ->where('domain', $request->domain)
                ->first();     
                
            if (! $tenant) {
                return redirect()->back()->with('error', __('login.tenant_not_found'));
            }

            $dbOptions = json_decode($tenant->database_options, true);   
            if (! isset($dbOptions['dbname'])) {
                return redirect()->back()->with('error', __('login.database_not_found'));
            }
 
            $db = $tenant->database_options['dbname'] ?? 'lims_' . $tenant->id;
            Config::set('database.connections.tenant.database', $db);
            DB::purge('tenant');  
            DB::reconnect('tenant');  
            DB::setDefaultConnection('tenant');
           
            $user = (new User()) 
                ->where('user_name', $request['user_name'])
                ->first();        

            if ($user && Hash::check($request['password'], $user->password)) { 
                auth()->login($user, true);  
                return redirect()->away('http://' . $tenant->tenant_id . '.' . $request->getHost().'/'.env('APP_NAME').'/dashboard');
            } else {
                return redirect()->back()->with('error', __('login.user_not_found'));
            }

        } catch (\Throwable $th) {
            return redirect()->back()->with("error", $th->getMessage());
        }
    }
  
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken(); 
        Config::set('database.connections.mysql');
        DB::purge('mysql');
        // DB::reconnect('mysql');
        DB::setDefaultConnection('mysql'); 
        return redirect()->to( env('APP_URL'));
    }
    public function admin_login(Request $request)
    { 
        if (isset($request['user_name']) && Auth::guard('admins')->attempt(['user_name' => $request->input('user_name'), 'password' => $request->input('password')])) {
           
            return redirect()->route('admin.dashboard');
        } 
        // elseif (isset($request['email']) && Auth::guard('admins')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
        //     return redirect()->route('admin.dashboard');
        // }
    
        return redirect()->back()->with('error', __('login.user_not_found'));
    }
    


    public function admin_logout()
    { 
        Auth::guard('admins')->logout(); 
        return redirect()->route('login-page'); ;
    }
 


}
