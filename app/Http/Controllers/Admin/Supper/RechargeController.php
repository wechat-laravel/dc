<?php

namespace App\Http\Controllers\Admin\Supper;

use App\Models\PayWechatModel;
use App\Models\RechargeRecordModel;
use App\Models\SpendRecordModel;
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

        $input['auth_email'] = Auth::user()->email;

        unset($input['confirm']);

        try{

            DB::transaction(function() use($input,$user){

                $user->balance = $user->balance + $input['money'];

                $user->update();

                SpendRecordModel::create([
                    'user_id' => $input['user_id'],
                    'mark'    => 'recharge',
                    'money'   => $input['money']
                ]);

                RechargeRecordModel::create($input);

            });

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>'充值失败！']);

        }


        return response()->json(['success'=>true,'msg'=>'操作成功！']);

    }

    public function record(Request $request)
    {

        if ($request->ajax()){

            //当前要查看的类别
            if ($request->has('current')){

                $current = $request->input('current');

                if ($current === 'admin'){

                    $records = RechargeRecordModel::select('id','user_id','user_email','auth_id','auth_email','money','remark','created_at')
                        ->with([
                            'user'=>function($query){
                                $query->select('id','avatar','balance');
                            }
                        ])->orderBy('created_at','desc')->paginate(10);

                    return response()->json($records);

                }else{

                    $records = PayWechatModel::select('id','user_id','out_trade_no','total_fee','status','pay_time','created_at')
                        ->where('status',1)
                        ->with([
                            'user'=>function($query){
                                $query->select('id','balance','email');
                            }
                        ])->orderBy('created_at','desc')->paginate(10);

                    return response()->json($records);

                }

            }else{

                return response()->json(['success'=>false,'msg'=>'非法请求！']);

            }
        }

        return view('modules.admin.supper.record');

    }

}
