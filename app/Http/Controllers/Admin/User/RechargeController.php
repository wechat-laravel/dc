<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\PayWechatModel;
use App\Models\SpendRecordModel;
use App\Models\UserModel;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RechargeController extends Controller
{
    protected $wechat;

    public function __construct()
    {
        $wechat = app('wechat');

        $this->wechat = $wechat;
    }


    public function index()
    {

        return view('modules.admin.user.recharge');

    }

    /**
     *  扫码支付(使用的是模式二支付)
     *  先生成订单，然后调用 微信支付的统一下单接口，然后把返回的支付交易连接生成二维码就可以了！
     */
    public function qrcode()
    {


        $options = [
            'body'             =>  '脉达传播-会员充值',                        //商品描述
            'detail'           =>  '上海一问科技信息有限公司',                  //商品详情
            'out_trade_no'     =>  'weiwen'.date('YmdHis').rand(1000, 9999),  //商户订单号(必须保存下来)
            'total_fee'        =>  100,                                       //订单总金额，单位为分
            'notify_url'       =>  'http://www.maidamaida.com/wechat/pay',    //异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
            'trade_type'       =>  'NATIVE',                                  //交易类型（取值如下：JSAPI，NATIVE，APP）
        ];

        //保证订单号是唯一的！
        $exists = PayWechatModel::where('out_trade_no',$options['out_trade_no'])->exists();

        if ($exists){
            //TODO 这样写还是有很小的概率会出现的
            $options['out_trade_no'] = 'weiwen'.date('YmdHis').rand(1000, 9999);

        }

        $payment = $this->wechat->payment;

        //创建订单
        $order = new Order($options);

        try{

            //统一下单
            $result = $payment->prepare($order);

            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){

                $data = [
                    'user_id'      => Auth::id(),
                    'total_fee'    => $options['total_fee'],
                    'out_trade_no' => $options['out_trade_no'],
                    'prepay_id'    => $result->prepay_id,
                    'code_url'     => $result->code_url,
                ];

                $res = PayWechatModel::create($data);

                //表的out_trade_no唯一，所以如果创建成功，则可以生产二维码
                if ($res){

                    QrCode::format('png')->size(200)->generate($result->code_url, public_path('assets/images/recharge/'.$data['out_trade_no'].'.png'));

                }else{

                    return response()->json(['success'=>false,'msg'=>'服务器超时，请刷新重试！']);

                }

            }else{

                return response()->json(['success'=>false,'code'=>$result->err_code,'msg'=>$result->err_code_des]);

            }

            return response()->json(['success'=>true,'data'=>$options['out_trade_no']]);


        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }


//        原生写法
//        try{
//
//            $wxMchPayHelper = new WxMchPayHelper($options);
//
//            $response = $wxMchPayHelper->qrcode_two();
//
//            //xml字符串转成的对象 很难操作，这里转成数组
//            $response = (array)$response;
//
//            //请求结果判断
//            if ($response['return_code'] === "SUCCESS"){
//
//                //业务结果判断
//                if ($response['result_code'] === "SUCCESS"){
//
//                    //请求结果与业务结果都为SUCCESS的时候 才返回一个二维码链接(code_url),还有一个预支付交易会话标识(prepay_id)可用于后续接口调用中使用，该值有效期为2小时
//                    QrCode::format('png')->size(200)->generate($response['code_url'], public_path('assets/images/recharge/1.png'));
//
//                    return response()->json(['success'=>true,'msg'=>'二维码生成成功！']);
//
//
//                }else{
//
//                    return response()->json(['success'=>false,'code'=>$response['err_code'],'msg'=>$response['err_code_des']]);
//
//                }
//
//            }else{
//
//                return response()->json(['success'=>false,'msg'=>$response['return_msg']]);
//
//            }
//
//        }catch (Exception $e){
//
//            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
//
//        }


    }

    public  function pay()
    {
        //$notify     这个参数为微信扫码支付后返回通知的对象，可以以对象或数组形式来读取通知内容。
        //$successful 这个参数其实就是判断 用户是否付款成功了（result_code == ‘SUCCESS’）
        $response = $this->wechat->payment->handleNotify(function($notify, $successful){

            //查看返回的商户订单号，在表里是否存在

            $pays = PayWechatModel::where('out_trade_no',$notify->ut_trade_no)->first();

            if (!$pays){

                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了

            }
            // 检查订单是否已经更新过支付状态
            if($pays->status === 1){

                return true;   // 已经支付成功了就不再更新了

            }

            //用户是否支付成功
            if ($successful){

                $pays->status = 1;

                $pays->pay_time = time();

                //事务，如果支付成功的话，对应的账号余额要加上去
                DB::transaction(function() use($pays){

                    $user = UserModel::where('user_id',$pays->user_id)->first();

                    $money = $pays->total_fee /100 ;

                    $user->balance = $user->balance + $money;

                    $user->update();

                    $pays->update();

                    SpendRecordModel::create([
                        'user_id' =>  $pays->user_id,
                        'mark'    =>  'recharge',
                        'money'   =>  $money
                    ]);

                });

            }else{

                $pays->status = 2;

                $pays->err_code_des = $notify->err_code_des;

                $pays->upadte();

            }



            return true; // 或者错误消息

        });

        return $response;

    }

}
