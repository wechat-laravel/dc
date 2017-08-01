<?php

namespace App\Http\Controllers\Admin\Service;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MassController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()){

            //获取登录二维码
            if ($request->has('qrcode')){

                $url    = 'http://rzwei.cn:5050/login';

                $data   = ['id'=>md5(Auth::user()->email)];

                $result = $this->curlGet($url,'POST',$data);

                if ($result['success']){

                    if ($result['data'] === '已经登陆'){

                        return response()->json(['success'=>false,'msg'=>'已登录']);

                    }else{

                        //登录的二维码
                        $msg    = base64_encode($result['data']);

                        return response()->json(['success'=>true,'msg'=>$msg]);

                    }

                }else{

                    //错误信息
                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }

            //查询登陆状态
            if ($request->has('status')){

                $url    = 'http://rzwei.cn:5050/getstatus?id='.md5(Auth::user()->email);

                $result = $this->curlGet($url,'GET');

                if ($result['success']){

                    return response()->json(['success'=>true,'msg'=>$result['data']]);

                }else{

                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }

            //查询全部微信好友列表
            if($request->has('all_list')){

                $url    = 'http://rzwei.cn:5050/getcontact?id='.md5(Auth::user()->email);

                $result = $this->curlGet($url,'GET');

                if ($result['success']){
                    //如果返回的是false表示已退出登录了。
                    if ($result['data'] === 'false'){

                        return response()->json(['success'=>false,'msg'=>$result['msg']]);

                    }else{
                        //表示获取到了用户的列表 格式为json,需要转一下

                        $data =  json_decode($result['data']);

                        return response()->json(['success'=>true,'data'=>$data]);

                    }

                }else{

                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }

            //设置群发信息


        }

        return view('modules.admin.service.mass');

    }

    /**
     *  获取扫码登录的二维码
     */
    public function getImg()
    {



    }


    /**
     * 检查登录的状态
     */
    public function getStatus()
    {


    }

    /**
     * curl get post
     * @param   $url      //请求的地址
     * @param   $method   //GET/POST
     * @param   $data     //参数
     * @return  mixed
     */
    public function curlGet($url,$method,$data=[])
    {

        $ch = curl_init();

        if ($method === 'GET'){

            //GET请求
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",         //HTTP请求头中"Accept-Encoding: "的值。 这使得能够解码响应的内容。 支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，会发送所有支持的编码类型。
                CURLOPT_TIMEOUT => 10,          //允许 cURL 函数执行的最长秒数。
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,      //让 cURL 判断使用哪个HTTP版本
                CURLOPT_CUSTOMREQUEST => "GET",
            ));

        }else{

            //POST请求
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_HEADER=>0,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ));

        }

        $response = curl_exec($ch);

        $err = curl_error($ch);

        curl_close($ch);


        if ($err) {

            $result = ['success'=>false,'msg'=>$err];

            return $result;

        } else {

            $result = ['success'=>true,'data'=>$response];

            return $result;
        }

    }


}
