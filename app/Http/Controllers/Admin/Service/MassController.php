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

//        $res = $this->qunfa();

//        return $res;

        if ($request->ajax()){

            $id = md5(Auth::user()->email);

            //获取登录二维码
            if ($request->has('qrcode')){

                $url    = 'http://rzwei.cn:5050/login';

                $data   = ['id'=>$id];

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

                $url    = 'http://rzwei.cn:5050/getstatus?id='.$id;

                $result = $this->curlGet($url,'GET');

                if ($result['success']){

                    return response()->json(['success'=>true,'msg'=>$result['data']]);

                }else{

                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }

            //查询全部微信好友列表
            if($request->has('all_list')){

                $url    = 'http://rzwei.cn:5050/getcontact?id='.$id;

                $result = $this->curlGet($url,'GET');

                if ($result['success']){
                    //如果返回的是false表示已退出登录了。
                    if ($result['data'] === 'false'){

                        return response()->json(['success'=>false,'msg'=>'登录已过期，请重新登录']);

                    }else{
                        //表示获取到了用户的列表 格式为json,需要转一下

                        $data =  json_decode($result['data']);

                        return response()->json(['success'=>true,'id'=>$id,'data'=>$data]);

                    }

                }else{

                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }



            //退出登录
            if ($request->has('logout')){

                $url    = 'http://rzwei.cn:5050/logout?id='.$id;

                $result =  $this->curlGet($url,'GET');

                if ($result['success']){
                    //如果返回的是false表示已退出登录了。
                    if ($result['data'] === 'false'){

                        return response()->json(['success'=>false,'msg'=>'退出失败']);

                    }else{

                        return response()->json(['success'=>true,'msg'=>'退出成功']);

                    }

                }else{

                    return response()->json(['success'=>false,'msg'=>$result['msg']]);

                }

            }


        }

        return view('modules.admin.service.mass');

    }



    /**
     * 设置群发信息
     */
    public function setmessage()
    {
        $url  = 'http://rzwei.cn:5050/setmessage?id='.md5(Auth::user()->email);

        $data = [
            'message' =>[
                1=> [
                        'delay'   => [1,3],
                        'text'    => '发错了，不好意思',
                        'picture' => '~/a.png',
                    ]
            ]

        ];

        $data = json_encode($data);

        $result = $this->curlGet($url,'POST',$data);

        return $result;

    }

    /**
     *  设置群发对象的条件
     *
     */
    public function setcondition()
    {
        $url = 'http://rzwei.cn:5050/setcondition?id='.md5(Auth::user()->email);

        $data = [
            'condition' =>[
                    'NickName' => '张远',
            ]

        ];

        $data = json_encode($data);

        $result = $this->curlGet($url,'POST',$data);

        return $result;


    }

    public  function qunfa()
    {
        $url = 'http://rzwei.cn:5050/qunfa?id='.md5(Auth::user()->email);

        $result = $this->curlGet($url,'GET');

        return $result;
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
