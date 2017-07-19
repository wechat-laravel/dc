<?php

namespace App\Http\Controllers\Admin\User;

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




    public function qrcode()
    {

        $options = [

            'appid'       =>  env('WECHAT_APPID'),  //公众账号ID
            'mch_id'      =>  env('MCH_ID'),        //商户号
            'nonce_str'   =>  str_random(32),       //随机字符串
            'product_id'  =>  str_random(32),       //商户定义的商品id 或者订单号
            'time_stamp'  =>  time(),               //时间戳
        ];

        ksort($options);

        $str_a = '';

        foreach ($options as $k => $v){

            $str_a .= $k.'='.$v.'&';

        }

        $temp = $str_a.'key='.env('WECHAT_SECRET');


        $options['sign'] = strtoupper(md5($temp));

        $qrcode_url  = "weixin: //wxpay/bizpayurl?appid={$options['appid']}&mch_id={$options['mch_id']}&nonce_str={$options['nonce_str']}&product_id={$options['product_id']}&time_stamp={$options['time_stamp']}&sign={$options['sign']}";

        QrCode::format('png')->size(200)->generate($qrcode_url, public_path('assets/images/recharge/1.png'));

        return response($options);


    }

}
