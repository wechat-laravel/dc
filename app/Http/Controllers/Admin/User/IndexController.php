<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\CaptchaModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller
{

    //个人资料
    public function profile()
    {

        return view('modules.admin.user.profile');

    }

    public  function postProfile(Request $request)
    {
        //图片上传
        if ($request->input('src')){

            $src = base64_decode(str_replace('data:image/png;base64,', '', $request->input('src')));

            $uploadPath = '/upload/'. Auth::id() . '/';

            $name = 'avatar.png';

            if(!is_dir(public_path().$uploadPath)) {

                $res = File::makeDirectory(public_path() . $uploadPath, $mode = 0777, $recursive = true);

            }

            if(file_put_contents(public_path().$uploadPath.$name, $src)){

                $input = ['avatar' => $uploadPath.$name];

                UserModel::where('id',Auth::id())->update($input);

                return response()->json(['success'=>true,'msg'=>'修改头像成功!']);

            }else{

                return response()->json(['success'=>false,'msg'=>'修改头像失败!']);

            }

        }else{

            $user = UserModel::find(Auth::id());

            $input = $request->only(['name','qq','wechat_id','mobile']);

            $validator = Validator::make($input,[
                'name'      => 'max:20',
                'qq'        => 'max:11',
                'wechat_id' => 'max:30',
            ]);

            if ($validator->fails()){

                return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

            }

            if ($input['mobile']){

                 $preg = preg_match('/^1[3|4|5|7|8]\d{9}$/', $input['mobile']);

                 if (empty($preg))    return response()->json(['success'=>false,'msg'=>'手机格式有误！']);

                 $user->mobile = $input['mobile'];

            }

            if ($input['name']) $user->name = $input['name'];

            if ($input['qq']) $user->qq = $input['qq'];

            if ($input['wechat_id']) $user->wechat_id = $input['wechat_id'];

            try{

                $user->update();

            }catch (\Exception $e){

                return response()->json(['success'=>false,'msg'=>'修改失败！']);

            }

            return response()->json(['success'=>true,'msg'=>'修改成功！']);

        }



    }

    //账户安全
    public function account()
    {

        return view('modules.admin.user.account');

    }

    public function postAccount(Request $request)
    {

        $input = $request->only(['password','new_password','confirm','captcha']);

        $validator = Validator::make($input,[
            'captcha'       => 'required',
            'password'      => 'required',
            'new_password'  => 'required',
            'confirm'       => 'required'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }else{

            //新密码两次输入对比
            if($input['new_password'] !== $input['confirm'])   return response()->json(['success'=>false,'msg'=>'新密码两次输入的不一致']);

            //邮箱验证码验证
            $captcha_exists = CaptchaModel::where('email',Auth::user()->email)->where('created_at','>=',time()-3600)->first();

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

                    return response()->json(['success'=>false,'msg'=>'输入的邮箱验证码错误!']);

                }else{

                    //原密码验证
                    if (Hash::check($input['password'],Auth::user()->password)){

                        try{

                            UserModel::where('id',Auth::id())->update([

                                'password' =>Hash::make($input['new_password'])

                            ]);

                            Auth::logout();


                        }catch (\Exception $e){

                            return response()->json(['success'=>false,'msg'=>'修改密码失败！']);

                        }

                    }else{

                        return response()->json(['success'=>false,'msg'=>'原密码错误']);

                    }


                    //注册成功后 删掉之前使用过的验证码
                    $captcha_exists->delete();

                }
            }

            return response()->json(['success'=>true,'msg'=>'密码修改成功！']);

        }

    }


    //账户资产
    public function assets(Request $request)
    {

        return view('modules.admin.user.assets');

    }

    public function send(Request $request)
    {

        $data['vcode'] =  str_random(6);

        $data['email'] =  Auth::user()->email;

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
                    CaptchaModel::create(['email'=>$data['email'],'vcode'=>$data['vcode']]);

                    Mail::send('updateMail',$data,function($message) use($data){

                        $message->to($data['email'])->subject("修改密码(乐其意-DC网站)");

                    });

                });

            }catch (\Exception $e){

                return response()->json(['success'=>false,'msg'=>'邮件发送失败！']);

            }

            return response()->json(['success'=>true,'msg'=>'邮件发送成功!']);

        }

    }

}
