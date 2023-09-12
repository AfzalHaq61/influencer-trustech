<?php

namespace App\Http\Controllers\Freelancer\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Freelancer;
use App\Models\UserLogin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('influencer.guest');
        $this->middleware('registration.status')->except('registrationNotAllowed');
        $this->activeTemplate = activeTemplate();
    }

    public function showRegistrationForm() {
        $pageTitle   = "Sign Up";
        $info        = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'influencer.auth.register', compact('pageTitle', 'mobile_code', 'countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        $general            = gs();
        $passwordValidation = Password::min(6);
        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData  = (array) json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));
        $validate     = Validator::make($data, [
            'email'        => 'required|string|email|unique:freelancers',
            'mobile'       => 'required|regex:/^([0-9]*)$/',
            'password'     => ['required', 'confirmed', $passwordValidation],
            'username'     => 'required|alpha_num|unique:freelancers|min:6',
            'captcha'      => 'sometimes|required',
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'agree'        => $agree,
        ]);
        return $validate;

    }

    public function register(Request $request) {
        // return $request;
        $this->validator($request->all())->validate();

        $request->session()->regenerateToken();

        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $exist = Freelancer::where('mobile', $request->mobile_code . $request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        event(new Registered($freelancer = $this->create($request->all())));

        $this->guard()->login($freelancer);

        return $this->registered($request, $freelancer)
        ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data) {
        $general = gs();

        $referBy = session()->get('reference');
        if ($referBy) {
            $referUser = Freelancer::where('username', $referBy)->first();
        } else {
            $referUser = null;
        }
        //User Create
        $freelancer               = new Freelancer();
        $freelancer->email        = strtolower(trim($data['email']));
        $freelancer->password     = Hash::make($data['password']);
        $freelancer->username     = trim($data['username']);
        $freelancer->ref_by       = $referUser ? $referUser->id : 0;
        $freelancer->country_code = $data['country_code'];
        $freelancer->mobile       = $data['mobile_code'] . $data['mobile'];

        $freelancer->address = [
            'address' => '',
            'state'   => '',
            'zip'     => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city'    => '',
        ];

        $freelancer->status = 1;
        $freelancer->kv     = $general->freelancer_kv ? 0 : 1;
        $freelancer->ev     = $general->ev ? 0 : 1;
        $freelancer->sv     = $general->sv ? 0 : 1;
        $freelancer->ts     = 0;
        $freelancer->tv     = 1;
        $freelancer->save();

        $adminNotification                = new AdminNotification();
        $adminNotification->freelancer_id = $freelancer->id;
        $adminNotification->title         = 'New Freelancer registered';
        $adminNotification->click_url     = null;
        $adminNotification->save();

        //Login Log Create
        $ip        = getRealIP();
        $exist     = UserLogin::where('freelancer_id', $ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude    = $exist->longitude;
            $userLogin->latitude     = $exist->latitude;
            $userLogin->city         = $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country      = $exist->country;
        } else {
            $info                    = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude    = @implode(',', $info['long']);
            $userLogin->latitude     = @implode(',', $info['lat']);
            $userLogin->city         = @implode(',', $info['city']);
            $userLogin->country_code = @implode(',', $info['code']);
            $userLogin->country      = @implode(',', $info['country']);
        }

        $userAgent                = osBrowser();
        $userLogin->freelancer_id = $freelancer->id;
        $userLogin->user_ip       = $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os      = @$userAgent['os_platform'];
        $userLogin->save();

        return $freelancer;
    }

    protected function guard() {
        return auth()->guard('freelancer');
    }

    public function checkUser(Request $request) {
        $exist['data'] = false;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Freelancer::where('email', $request->email)->exists();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = Freelancer::where('mobile', $request->mobile)->exists();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = Freelancer::where('username', $request->username)->exists();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered() {
        return to_route('freelancer.home');
    }

}
