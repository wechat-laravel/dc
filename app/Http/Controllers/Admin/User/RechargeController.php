<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Service\WxMchPayHelper;
use EasyWeChat\Payment\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
            'out_trade_no'     =>  'weiwen'.date('YmdHis').rand(1000, 9999),  //商户订单号
            'total_fee'        =>  1000,                                      //订单总金额，单位为分
            'notify_url'       =>  'http://www.maidamaida.com/wechat/pay',    //异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
            'trade_type'       =>  'NATIVE',                                  //交易类型（取值如下：JSAPI，NATIVE，APP）
        ];

        $payment = $this->wechat->payment;

        //创建订单
        $order = new Order($options);

        try{

            //统一下单
            $result = $payment->prepare($order);

//            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
//
                return response($result);
//
//            }


        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }


//
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
        return 2;
    }

}
