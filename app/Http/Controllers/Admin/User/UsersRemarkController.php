<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\GrantUserModel;
use App\Models\UsersRemarkModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class UsersRemarkController extends Controller
{

    //查看一个openid的备注信息
    public function show(Request $request, $openid)
    {
        $openid  = e($openid);

        $exists  = GrantUserModel::where('openid',$openid)->exists();

        if (!$exists)    return response()->json(['success'=>false,'msg'=>'非法的请求，没有该用户存在！']);

        $user = UsersRemarkModel::where('openid',$openid)->where('user_id',Auth::id())->first();

        if ($user){

            return response()->json(['success'=>true,'data'=>$user]);

        }else{

            return response()->json(['success'=>false,'msg'=>'还没有备注信息！']);

        }


    }

    //给授权的用户备注信息
    public function remark(Request $request)
    {
        $input = $request->only(['openid','name','age','sex','wechat_id','mobile','remark']);

        $validator = Validator::make($input,[
            'openid'        => 'required|max:100',
            'name'          => 'required|max:50',
            'sex'           => 'required|integer',
            'wechat_id'     => 'max:50',
            'mobile'        => 'max:20',
            'remark'        => 'max:200'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>$validator->errors()->all()]);

        }

        //微信号或者手机号必须有其中一项
        if($input['wechat_id'] || $input['mobile']){

            $exists  = GrantUserModel::where('openid',$input['openid'])->exists();

            if (!$exists)    return response()->json(['success'=>false,'msg'=>'非法的请求，没有该用户存在！']);

            $input['user_id'] = Auth::id();

            try{
                //保证 user_id 与 openid 的组合是唯一的。
                $user = UsersRemarkModel::where('openid',$input['openid'])->where('user_id',Auth::id())->first();

                if ($user){

                    $user->update($input);

                }else{

                    UsersRemarkModel::create($input);

                }

            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

            }

            return response()->json(['success'=>true,'msg'=>'备注成功！']);

        }else{

            return response()->json(['success'=>false,'msg'=>'微信号与手机号必须填一个！']);

        }

    }


}
