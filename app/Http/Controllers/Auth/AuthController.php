<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\CaptchaModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Validator;

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


    public function getLogin()
    {

        return view('modules.auth.login');

    }

    public function postLogin(Request $request)
    {

        $input = $request->only(['email','password','remember']);

        $validator = Validator::make($input,[

            'email' => 'required',

            'password' => 'required',

        ]);

        if($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'缺少必要的字段！']);

        }

        try{

            $user = UserModel::where('email',$input['email'])->first();

            if (!$user) return  response()->json(['success'=>false,'msg'=>'该邮箱未注册使用！']);

            if(!Auth::attempt(['email'=>$input['email'],'password'=>$input['password']],$input['remember'])){

                return  response()->json(['success'=>false,'msg'=>'用户密码错误! ']);

            }


        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>'登录失败!']);
        }

        return response()->json(['success'=>true,'msg'=>'登录成功！']);

    }

    public function getRegister()
    {

        return view('modules.auth.register');

    }

    public function postRegister(Request $request)
    {
        $input = $request->only(['email','password','captcha']);

        $validator = Validator::make($input,[
            'email'         => 'required|email',
            'captcha'       => 'required',
            'password'      => 'required'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }else{

            $email_exists    = UserModel::where('email',$input['email'])->exists();

            if ($email_exists){

                return response()->json(['success'=>false,'msg'=>'该邮箱已注册!']);

            }else{

                $captcha_exists = CaptchaModel::where('email',$input['email'])->where('created_at','>=',time()-3600)->first();

                if (!$captcha_exists)    return response()->json(['success'=>false,'msg'=>'请点击发送验证码!']);

                //如果错误次数超过10次,限制一小时内不得再发送
                if ($captcha_exists->count >= 10){

                    $time = strtotime($captcha_exists->created_at) + 3600;

                    //如果超过10次的时候 过了一个小时的时间的间隔,可以继续走下去.  没有的话,牵扯手机验证的 都用不了!
                    if ($time > time()){

                        $cha  = $time- time();

                        return response()->json(['success'=>false,'msg'=>'抱歉,您已多次输错,请于'.$cha.'秒后再尝试!']);

                    }

                }else{

                    if (strtolower($captcha_exists->vcode) !== strtolower($input['captcha'])){
                        //验证码输错,计数+1
                        $captcha_exists->count +=1;

                        $captcha_exists->save();

                        return response()->json(['success'=>false,'msg'=>'输入的验证码错误!']);

                    }else{

                        try{

                            //默认注册的身份为游客身份，可以免费体验5天

                            $overdue_at = time() + 432000;

                            $user = UserModel::create(['email'=>$input['email'],'password' => Hash::make($input['password']),'overdue_at'=>$overdue_at]);

                            Auth::loginUsingId($user->id);


                        }catch (\Exception $e){

                            return response()->json(['success'=>false,'msg'=>'注册失败！']);

                        }
                        //注册成功后 删掉之前使用过的验证码
                        $captcha_exists->delete();

                    }
                }

                return response()->json(['success'=>true,'msg'=>'注册成功!']);
            }
        }

    }

    public function getLogout()
    {
        Auth::Logout();

        return redirect('/auth/login');
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
                    if ($request->has('method')){

                        DB::transaction(function() use($data){


                            //过期的,和没有的 可以直接发送
                            CaptchaModel::create(['email'=>$data['email'],'vcode'=>$data['activationcode']]);

                            Mail::send('forgetemail',$data,function($message) use($data){

                                $message->to($data['email'])->subject("重置密码");

                            });

                        });

                    }else{

                        DB::transaction(function() use($data){

                            //过期的,和没有的 可以直接发送
                            CaptchaModel::create(['email'=>$data['email'],'vcode'=>$data['activationcode']]);

                            Mail::send('activemail',$data,function($message) use($data){

                                $message->to($data['email'])->subject("账号注册");

                            });

                        });

                    }

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

    public  function getForget()
    {

        return view('modules.auth.forget');

    }

    public  function postForget(Request $request)
    {

        $input = $request->only(['email','password','captcha','confirm']);

        $validator = Validator::make($input,[
            'email'         => 'required|email',
            'captcha'       => 'required',
            'password'      => 'required',
            'confirm'       => 'required'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }else{

            $email_exists    = UserModel::where('email',$input['email'])->first();

            if (!$email_exists){

                return response()->json(['success'=>false,'msg'=>'该邮箱未注册使用过!']);

            }else{

                $captcha_exists = CaptchaModel::where('email',$input['email'])->where('created_at','>=',time()-3600)->first();

                if (!$captcha_exists)    return response()->json(['success'=>false,'msg'=>'请点击发送验证码!']);

                //如果错误次数超过10次,限制一小时内不得再发送
                if ($captcha_exists->count >= 10){

                    $time = strtotime($captcha_exists->created_at) + 3600;

                    //如果超过10次的时候 过了一个小时的时间的间隔,可以继续走下去.  没有的话,牵扯手机验证的 都用不了!
                    if ($time > time()){

                        $cha  = $time- time();

                        return response()->json(['success'=>false,'msg'=>'抱歉,您已多次输错,请于'.$cha.'秒后再尝试!']);

                    }

                }else{

                    if (strtolower($captcha_exists->vcode) !== strtolower($input['captcha'])){
                        //验证码输错,计数+1
                        $captcha_exists->count +=1;

                        $captcha_exists->save();

                        return response()->json(['success'=>false,'msg'=>'输入的验证码错误!']);

                    }else{

                        try{

                            $email_exists->password = Hash::make($input['password']);

                            $email_exists->update();

                        }catch (\Exception $e){

                            return response()->json(['success'=>false,'msg'=>'注册失败！']);

                        }

                        $captcha_exists->delete();

                    }
                }

                return response()->json(['success'=>true,'msg'=>'密码重置成功!']);
            }
        }

    }

}
