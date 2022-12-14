<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Password;
class GeneralController extends Controller
{
    public function index()
    {
        return view('backend.index');
    }

    public function dashboard()
    {
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        $numberOfCustomers = [];

        foreach ($months as $key => $value) {
            $numberOfCustomers[] = Customer::where(\DB::raw("DATE_FORMAT(created_at, '%m')"), $value)->count();
        }

        $monthlyAmount = [];

   

        // function to get mounth wise amount
        foreach ($months as $key => $value) {
            $monthlyAmount[]=Customer::where(\DB::raw("DATE_FORMAT(created_at, '%m')"), $value)->get()->sum('amount');
        };

        $customers = Customer::orderBy('id', 'desc')->take(5)->get();
        return view('backend.dashboard.dashboard')
        ->with('months', json_encode($months, JSON_NUMERIC_CHECK))
        ->with('numberOfCustomers', json_encode($numberOfCustomers, JSON_NUMERIC_CHECK))
        ->with('monthlyAmount', json_encode($monthlyAmount, JSON_NUMERIC_CHECK))
        ->withCustomers($customers);
    }

    public function siteSettings()
    {
        if (! auth()->user()->hasPermissionTo('Update Settings')) {
            abort(403);
        }

        return view('backend.general.settings');
    }

    public function save_general_settings(Request $request)
    {
        if (! auth()->user()->hasPermissionTo('Update Settings')) {
            abort(403);
        }
        if ($request->site_title) {
            setSettings('site_title', request('site_title'));
        }
        if ($request->short_title) {
            setSettings('short_title', request('short_title'));
        }
        if ($request->copyrights) {
            setSettings('copyrights', request('copyrights'));
        }
        

        if ($request->hasFile('logo')) {

            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg|max:1024',
            ]);
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = getRandomString().'-'.time() . '.' . $extension;
            $file->move('uploads/logo', $filename);
            setSettings('logo', 'uploads/logo/'.$filename);
        }
        if ($request->hasFile('favicon')) {

            $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg|max:1024',
            ]);

            $file = $request->file('favicon');
            $extension = $file->getClientOriginalExtension();
            $filename = getRandomString().'-'.time() . '.' . $extension;
            $file->move('uploads/favicon', $filename);
            setSettings('favicon', 'uploads/favicon/'.$filename);
        }


    


        if ($request->email) {
            setSettings('email', request('email'));
        }
        if ($request->phone) {
            setSettings('phone', request('phone'));
        }
        if ($request->address) {
            setSettings('address', request('address'));
        }
        if ($request->allow_register) {
            setSettings('allow_register', request('allow_register'));
        }

        if ($request->min_value) {
            setSettings('min_value', request('min_value'));
        }
        if ($request->max_value) {
            setSettings('max_value', request('max_value'));
        }
        if ($request->stripe_secret) {
            setSettings('stripe_secret', request('stripe_secret'));
        }
        if ($request->stripe_key) {
            setSettings('stripe_key', request('stripe_key'));
        }
        if ($request->redirect_url) {
            setSettings('redirect_url', request('redirect_url'));
        }
        
        
        return redirect()->back();
    }

    public function myProfile()
    {
        return view('backend.profile.index');
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|unique:users,username,'.$user->id,
            //'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);



        if($request->password){
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user->password = Hash::make($request->password);
        }

        // if(Auth::user()->id!=$user->id){
        //     return redirect()->back()->with('error', 'You are not authorized to perform this action.');
        // }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        //code to upload picture to server
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $name = time() . '.' . $image->getClientOriginalExtension();
        //     $destinationPath = public_path('/images/users');
        //     $image->move($destinationPath, $name);
        //     $user->image = $name;
        // }
        $user->save();
        alert()->success('Profile Updated Successfully');
        return redirect()->back();
    }    


    
    
}
