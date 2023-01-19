<?php

namespace App\Http\Controllers\Api\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ForgotPasswordRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\PhoneVerifyRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\UserInfoResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use App\Http\Requests\Api\ResetRequest;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class AuthController extends Controller
{
    use GeneralTrait;

    //Login
    public function playerLogin(LoginRequest $request)
    {
        $player = User::where('mobile', $request->mobile)->first();
        if (Hash::check($request->password, $player->password))
        {
            if (!auth()->attempt($request->only('mobile', 'password'))) {
                if (!auth()->attempt($request->only('mobile', 'password'))) {
                    throw new AuthenticationException();
                }
            }
            if(auth()->user()->roles()->first()->name != "player"){
                throw new AuthenticationException();
            }
            $token = auth()->user()->createToken($request->deviceId)->plainTextToken;
            $player -> token = $token;

            /*** Start Notification ****/
            Carbon::setLocale('ar');
            $player->notification()->create([
                'type'              => "تسجيل دخول",
                'notifiable_type'   => "User",
                'notifiable_id'     => $player->id,
                'content'           => 'لقد تم تسجيل الدخول الي حسابك في يوم '. Carbon::parse(now())->translatedFormat('l j F Y H:i:s'),
                'icon'              => 'images/icon/login.png',
                'read_at'           => null,
            ]);
            /*** End Notification ****/

            return $this->successMessage($player, 'Login Successfully');

        } else {
            return $this->returnErrorMessage('Sorry! Password Mismatch, Please Try again', '422');
        }
    }

    //Register
    public function createPlayerAccount(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            // OTP Code
            do{
                $otp_code = mt_rand(10000,99999);
                $is_code = User::where('otp_code', $otp_code)->get();
            }
            while(!$is_code->isEmpty());

            $input['username']      = $request->username;
            $input['email']         = $request->email ?? null ;
            $input['email_verified_at']  = $request->email != null ? \Illuminate\Support\Carbon::now() : null;
            $input['mobile']        = $request->mobile;
            $input['otp_code']      = $otp_code;
            $input['password']      = bcrypt($request->password);
            $input['account_status']= 0;
            $input['active']        = 0;

            if ($image = $request->file('user_image')) {
                $filename = time().Str::slug($request->username).'.'.$image->getClientOriginalExtension();
                $path = ('images/player/' . $filename);
                Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $input['user_image']  = $path;
            }

            $player = User::create($input);
            $player->markEmailAsVerified();
            $player->attachRole(Role::whereName('player')->first()->id);

            ### ِAddress
            $address['user_id']       = $player->id;
            $address['address']       = $request->address ?? '';
            $address['country_id']    = $request->country_id ?? null;
            $address['state_id']      = $request->state_id ?? null;
            $address['city_id']       = $request->city_id ?? null;
            $address['zip_code']      = $request->zip_code ?? '';
            $address['po_box']        = $request->po_box ?? '';
            UserAddress::create($address);

            DB::commit(); // insert data
            $data =[
                'otp_code' => $otp_code,
            ];
            return $this->successMessage( $data,'Player Account Created Successfully, Please Check Your Phone To Verify Your Phone Number');

        }catch (\Exception $e){
            DB::rollback();
            return $this->returnErrorMessage('Sorry! Invalid Data, Please Try again', '422');
        }
    }

    //Phone Verify
    public function phoneVerify(PhoneVerifyRequest $request)
    {
        try {
            $player = User::whereMobile($request->mobile)->whereOtpCode($request->otp_code)->first();
            if(!$player){
                return $this->returnErrorMessage('Sorry! Invalid Data, Please Try again', '422');
            }else{
                $player->update([
                    "mobile_verify" => 1 ,
                    "otp_code" => null ,
                    'account_status' => 1,
                    'active' => 1
                ]);
                $token = $player->createToken($request->deviceId)->plainTextToken;
                $player->token = $token;

                /*** Start Notification ****/
                Carbon::setLocale('ar');
                $player->notification()->create([
                    'type'              => "إنشاء حساب جديد",
                    'notifiable_type'   => "User",
                    'notifiable_id'     => $player->id,
                    'content'           => 'تهانينا, لقد تم إنشاء حساب جديد بنجاح',
                    'icon'              => 'images/icon/hi.png',
                    'read_at'           => null,
                ]);
                /*** End Notification ****/

                return $this->successMessage(new PlayerResource($player), 'Phone has been verified');
            }
        } catch (Throwable $e) {
            return $this->responseJsonFailed();
        }
    }

    //User Data
    public function playerLoginData(Request $request)
    {
        $player = $request->user();
        $token = $request->bearerToken();
        $player->token = $token;
        return $this->successMessage(new UserResource($player), 'Data for Login Player');
    }



    //Logout
    public function destroy(Request $request)
    {
        auth()->user()->tokens()->where('name', $request->deviceId)->delete();
        return $this->successMessage('', 'Player Logout Successfully');
    }



    /////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////
    public  function forgotPassword(ForgotPasswordRequest $request)
    {
        // OTP Code
        do{
            $code = mt_rand(10000,99999);
            $is_code = User::where('otp_code', $code)->get();
        }
        while(!$is_code->isEmpty());

        if($request->email != null){
            $created_at = Carbon::now();
            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $code,
                    'created_at' => $created_at
                ]);

            $reset_code =  DB::table('password_resets')->where("token", $code)->first();
            Mail::to($reset_code->email)->send(new ResetPasswordMail($reset_code));
            if (Mail::failures()) {
                return $this->returnErrorMessage('Sorry! Please Try Again Latter');
            }else{
                return $this->returnSuccessMessage('Great! Code Successfully Send To Your Mail');
            }
        }elseif($request->mobile != null){
            $player = User::where("mobile", $request->mobile)->first();
            $player->update([
                "otp_code"  => $code,
            ]);
            return $this->returnSuccessMessage('Great! Code Successfully Send To Your Phone Number');
        }
    }


    public function resetPassword(ResetRequest $request){
        if ($request->email != null){
            $Token = DB::table('password_resets')->where("token", $request->token)
                ->where("token", "!=", null)->first();

            if(empty($Token->email)){
                return $this->returnErrorMessage('Sorry! Invalid Code', '422');
            }

            $user= User::where("email",$Token->email)->first();

        }elseif ($request->mobile != null){
            $user= User::where("mobile",$request->mobile)->first();
        }
        if ($user) :
            $user->password = bcrypt($request->password);
            if ($user->save()) :
                return $this->returnSuccessMessage("Password Changed Successfully");

            else :
                return $this->returnErrorMessage('Sorry! Please Try Again');
            endif;
        else:
            return $this->returnErrorMessage('Sorry! Invalid Code', '422');
        endif;
    }


    public function updateImage(Request $request)
    {
        $this->validate($request, [
            "user_image" => "required|file|mimes:png,jpg,svg,gif",
        ]);

        $player = \auth()->user();

        if ($player->user_image != null && File::exists( $player->user_image )) {
            unlink( $player->user_image );
        }
        $image = $request->file('user_image');
        $filename = time().Str::slug($request->name).'.'.$image->getClientOriginalExtension();
        $path = ('images/player/' . $filename);
        Image::make($image->getRealPath())->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path, 100);
        $input['user_image']  = $path;

        $player->update($input);
        return $this->successMessage(new UserResource($player), 'Profile Image Updated Successfully');
    }

    //Update Profile
    public function updatePlayerInfo(Request $request)
    {
        $this->validate($request, [
            'first_name'    => 'nullable|min:3',
            'last_name'     => 'nullable|min:3',
            'username'      => 'nullable|min:3|max:50|unique:users,username,' . \auth()->id(),
            'email'         => 'nullable|email|max:255|unique:users,email,' . \auth()->id(),
            'mobile'        => 'nullable|numeric|unique:users,mobile,' . \auth()->id(),
            'password'      => 'nullable|min:8',

            'address'       => 'nullable|min:3',
            'country_id'    => 'nullable|exists:countries,id',
            'state_id'      => 'nullable|exists:states,id',
            'city_id'       => 'nullable|exists:cities,id',
            'zip_code'      => 'nullable|min:3',
            'po_box'        => 'nullable|min:3',
        ]);
        $merchant = \auth()->user();

        DB::beginTransaction();
        try {
            $input['first_name']    = $request->first_name != '' ? $request->first_name : $merchant->first_name;
            $input['last_name']     = $request->last_name != '' ? $request->last_name : $merchant->last_name;
            $input['username']      = $request->username != '' ? $request->username : $merchant->username;
            $input['email']         = $request->email != '' ? $request->email : $merchant->email;
            $input['mobile']        = $request->mobile != '' ? $request->mobile : $merchant->mobile;
            $input['password']      = $request->password != '' ? bcrypt($request->password) : bcrypt($merchant->password);
            $merchant->update($input);

            ### ِAddress
            $merchant_address = UserAddress::whereUserId(\auth()->id())->first();
            if (!empty($merchant_address)){
                $address['address']       = $request->address != '' ? $request->address : $merchant_address->address;
                $address['country_id']    = $request->country_id != '' ? $request->country_id : $merchant_address->country_id;
                $address['state_id']      = $request->state_id != '' ? $request->state_id : $merchant_address->state_id;
                $address['city_id']       = $request->city_id != '' ? $request->city_id : $merchant_address->city_id;
                $address['zip_code']      = $request->zip_code != '' ? $request->zip_code : $merchant_address->zip_code;
                $address['po_box']        = $request->po_box != '' ? $request->po_box : $merchant_address->po_box;
                $merchant_address->update($address);
            }else{
                $address['user_id']       = $merchant->id;
                $address['address']       = $request->address;
                $address['country_id']    = $request->country_id;
                $address['state_id']      = $request->state_id;
                $address['city_id']       = $request->city_id;
                $address['zip_code']      = $request->zip_code;
                $address['po_box']        = $request->po_box;
                UserAddress::create($address);
            }
            DB::commit(); // insert data
            return $this->successMessage(new UserInfoResource($merchant), 'Profile Information Updated Successfully');

        }catch (\Exception $e){
            DB::rollback();
            return $this->returnErrorMessage('Sorry! Invalid Data, Please Try again', '422');
        }
    }

}
