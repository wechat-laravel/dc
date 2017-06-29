<?php

namespace App\Http\Controllers\Admin\Supper;

use App\Models\RechargeRecordModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class RechargeController extends Controller
{

    public function index()
    {

        return view('modules.admin.supper.recharge');

    }


    public function store(Request $request)
    {

        $input = $request->only(['user_email','money','remark','confirm']);

        $validator = Validator::make($input,[

            'user_email'    => 'required|email',
            'money'         => 'required|numeric',
            'confirm'       => 'required|numeric',
            'remark'        => 'max:100'

        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }

        if ($input['money'] !== $input['confirm'])   return response()->json(['success'=>false,'msg'=>'充值的数额两次不一致！']);


        $user = UserModel::where('email',$input['user_email'])->first();

        if(!$user)    return response()->json(['success'=>false,'msg'=>'没有该用户！']);

        $input['user_id'] = $user->id;

        $input['auth_id'] = Auth::id();

        unset($input['confirm']);

        try{

            DB::transaction(function() use($input,$user){

                $user->balance = $user->balance + $input['money'];

                $user->update();

                RechargeRecordModel::create($input);

            });

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>'充值失败！']);

        }


        return response()->json(['success'=>true,'msg'=>'操作成功！']);

    }

}
