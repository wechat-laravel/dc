<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Service\WxMchPayHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RechargeController extends Controller
{

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
            'appid'            =>  env('WECHAT_APPID'),                      //公众账号ID
            'mch_id'           =>  env('WECHAT_PAYMENT_MERCHANT_ID'),        //商户号
            'nonce_str'        =>  str_random(32),                           //随机字符串
            'body'             =>  '脉达传播-会员充值',                        //商品描述
            'out_trade_no'     =>  'weiwen'.date('YmdHis').rand(1000, 9999), //商户订单号
            'total_fee'        =>  1000,                                      //订单总金额，单位为分
            'spbill_create_ip' =>  env('CLIENT_IP'),                         //APP和网页支付提交用户端ip，Native支付(JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付)填调用微信支付API的机器IP
            'notify_url'       =>  'http://www.maidamaida.com/wechat/pay',   //异步接收微信支付结果通知的回调地址，通知url必须为外网可访问的url，不能携带参数
            'trade_type'       =>  'NATIVE',                                 //交易类型（取值如下：JSAPI，NATIVE，APP）
        ];

        $wxMchPayHelper = new WxMchPayHelper($options);

        $response = $wxMchPayHelper->qrcode_two();


        //请求结果判断
        if ($response->return_code == "SUCCESS"){

            //业务结果判断
            if ($response->result_code == "SUCCESS"){

                //请求结果与业务结果都为SUCCESS的时候 才返回一个二维码链接(code_url),还有一个预支付交易会话标识(prepay_id)可用于后续接口调用中使用，该值有效期为2小时
                QrCode::format('png')->size(200)->generate($response->code_url, public_path('assets/images/recharge/1.png'));


            }else{

                return response()->json(['success'=>false,'code'=>$response->err_code,'msg'=>$response->err_code_des]);

            }

        }else{

            return response()->json(['success'=>false,'msg'=>$response->return_msg]);

        }

    }

    public  function pay()
    {
        return 2;
    }

}
