<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\GrantUserModel;
use App\Models\RedLogModel;
use App\Models\SpendRecordModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class RedRewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input     = $request->only(['send_name','wishing','act_name','money','remark','openid']);

        $validator = Validator::make($input,[
            'send_name'   => 'required|max:10',
            'wishing'     => 'required|max:10',
            'act_name'    => 'required|max:10',
            'money'       => 'required|digits_between:1,200',
            'openid'      => 'required',
            'remark'      => 'max:200',
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>$validator->errors()->all()]);

        }

        $exists = GrantUserModel::where('openid',$input['openid'])->exists();

        if (!$exists)  return response()->json(['success'=>false,'msg'=>'非法请求，该用户不存在！']);

        if (Auth::user()->balance < $input['money']) return response()->json(['success'=>false,'msg'=>'您的账户余额不足，请充值！']);

        try{
            //红包奖励记录
            SpendRecordModel::create([
                'user_id'    => Auth::id(),
                'mark'       => 'reward',
                'openid'     => $input['openid'],
                'money'      => $input['money'],
                'send_name'  => $input['send_name'],
                'wishing'    => $input['wishing'],
                'act_name'   => $input['act_name'],
                'remark'     => $input['remark'],
            ]);
            //红包发送
            $param = [
                "nonce_str"    => str_random(32),//随机字符串 不长于32位
                "mch_billno"   => 'weiwen' . date('YmdHis') . rand(1000, 9999),//订单号
                "mch_id"       => env('MCH_ID'),//商户号
                "wxappid"      => env('WECHAT_APPID'),
                "send_name"    => $input['send_name'],//红包发送者名称 微问数据
                "re_openid"    => $input['openid'],
                "total_amount" => $input['money'] * 100,//付款金额，单位分
                "total_num"    => 1,//红包发放总人数
                "wishing"      => $input['wishing'],//红包祝福语 恭喜发财
                "client_ip"    => env('CLIENT_IP'),//调用接口的机器 Ip 地址
                "act_name"     => $input['act_name'],//活动名称 红包活动
                "remark"       => $input['remark'],//备注信息 快来抢
            ];

            $wxMchPayHelper = new WxMchPayHelper($param);

            $r = $wxMchPayHelper->send_redpack();

            //分析返回的 $r 对红包做记录 对活动红包金额amount减法处理
            if ($r->return_code == 'SUCCESS') {
                RedLogModel::create([
                    'user_id'      => Auth::id(),
                    'open_id'      => $input['openid'],
                    'tasks_id'     => 0,
                    'total_amount' => isset($r->total_amount) ? $r->total_amount/100 : 0,
                    'return_code'  => isset($r->return_code) ? $r->return_code : 0,
                    'return_msg'   => isset($r->return_msg) ? $r->return_msg : 0,
                    'result_code'  => isset($r->result_code) ? $r->result_code : 0,
                    'err_code'     => isset($r->err_code) ? $r->err_code : 0,
                    'err_code_des' => isset($r->err_code_des) ? $r->err_code_des : 0,
                    'mch_billno'   => isset($r->mch_billno) ? $r->mch_billno : 0,
                    'send_listid'  => isset($r->send_listid) ? $r->send_listid : 0,
                    'status'       => $r->result_code == 'SUCCESS' ? 1 : 2,
                ]);
                if($r->result_code == 'SUCCESS'){

                    UserModel::where('id', Auth::id())->decrement('balance', $r->total_amount/100);

                    UserModel::where('id', Auth::id())->increment('consume', $r->total_amount/100);

                }else{

                    return response()->json(['success'=>false,'msg'=>$r->return_msg]);

                }

            }

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return response()->json(['success'=>true,'msg'=>'奖励红包发送成功！']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = e($id);

        $exists = GrantUserModel::where('openid',$id)->exists();

        if (!$exists){

            return response()->json(['success'=>false,'msg'=>'非法的请求，没有该用户!']);

        }else{
            //区分普通用户与管理员
            if (Auth::user()->identity === 'admin'){

                $res = RedLogModel::where('tasks_id',0)->where('open_id',$id)->orderBy('created_at','DESC')->paginate(10);

            }else{

                $res = RedLogModel::where('tasks_id',0)->where('open_id',$id)->where('user_id',Auth::id())->orderBy('created_at','DESC')->paginate(10);

            }

            return response()->json($res);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
