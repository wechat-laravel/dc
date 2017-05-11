<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Models\CaptchaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    public function getLogin()
    {

        return view('modules.auth.login');

    }

    public function getRegister()
    {

        return view('modules.auth.register');

    }

    //发送验证码(邮箱与短信)

    public function send(Request $request)
    {
        if ($request->has('email')){

            $data = [
                'email' => $request->input('email'),
            ];

            $validator = Validator::make($data,[
                'email' => 'required|email',
            ]);

            if ($validator->fails()){
                return response()->json(['success'=>false,'msg'=>'Email格式不正确!']);
            }

            $data['activationcode'] =  str_random(6);

            //判断验证码时间与错误次数

            $exis = CaptchaModel::where('email',$data['email'])->where('created_at','>=',time()-3600)->first();

            if ($exis){

                //如果错误的次数超过10次.看一下还有多少时间 才能继续发送验证码
                if ($exis->count >= 10){

                    $time = strtotime($exis->created_at) + 3600;

                    if ( $time > time()){

                        $cha = $time - time();

                        return response()->json(['success'=>false,'msg'=>'很抱歉，您已输错多次,请于'.$cha.'秒后重试!']);

                    }else{
                        return response()->json(['success'=>false,'msg'=>'请输入您邮箱最近已存在的验证码!']);
                    }

                }else{

                    return response()->json(['success'=>false,'msg'=>'请输入您邮箱最近已存在的验证码!']);
                }

            }else{

                try{

                    DB::transaction(function() use($data){

                        //过期的,和没有的 可以直接发送
                        CaptchaModel::create(['email'=>$data['email'],'vcode'=>$data['activationcode']]);

                        Mail::send('activemail',$data,function($message) use($data){

                            $message->to($data['email'])->subject("欢迎您注册(乐其意-DC网站)");

                        });

                    });

                }catch (\Exception $e){

                    return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

                }

                return response()->json(['success'=>true,'msg'=>'邮件发送成功!']);

            }


        }elseif($request->has('mobile')){

            return response()->json(['success'=>false,'msg'=>'短信验证码暂未开通!']);

        }else{

            return response()->json(['success'=>false,'msg'=>'请输入邮箱地址!']);

        }

    }


}
