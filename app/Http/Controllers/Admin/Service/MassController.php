<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\CityModel;
use App\Models\ProvinceModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MassController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()){

            $id = md5(Auth::user()->email);

            //获取登录二维码
            if ($request->has('qrcode')){

                $url    = 'http://rzwei.cn:5050/login?id='.$id;
                
                $result = $this->curlGet($url,'GET');

                if ($result['success']){

                    if ($result['data'] === '已经登陆'){

                        return response()->json(['success'=>false,'msg'=>'已登录']);

                    }else{
                        //如果没登陆，检测之前是否有缓存，有的话，删除掉
                        if (Redis::hexists($id,'all_list')){

                            Redis::hdel($id,'all_list');

                        }


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

                //页码
                if ($request->has('page')){

                    $current_page = intval($request->input('page'));

                    if (!$current_page)  $current_page = 1;

                }else{

                    $current_page = 1;
                }

                //如果有的话,直接取出返回（因为都是先查看是否登陆 ，然后才查的这个）
                if (Redis::hexists($id,'all_list')){

                    //因为存储的是json字符串的数据，需要转
                    $data   = json_decode(Redis::hget($id,'all_list'));


                }else{

                    $url    = 'http://rzwei.cn:5050/getcontact?id='.$id;

                    $result = $this->curlGet($url,'GET');

                    if ($result['success']){
                        //如果返回的是false表示已退出登录了。
                        if ($result['data'] === 'false'){

                            return response()->json(['success'=>false,'msg'=>'登录已过期，请重新登录']);

                        }else{

                            //将该数据（JSON格式的字符串）存入缓存
                            Redis::hset($id,'all_list',$result['data']);

                            //表示获取到了用户的列表 格式为json,需要转一下

                            $data =  json_decode($result['data']);

                        }

                    }else{

                        return response()->json(['success'=>false,'msg'=>$result['msg']]);

                    }

                }

                $res = [
                    'success'      => true,
                    'id'           => $id,
                    'current_page' => $current_page,
                    'last_page'    => ceil(count($data)/100),
                    'total'        => count($data),
                    'data'         => array_slice($data,($current_page-1)*100,100)
                ];

                return $res;

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

            //获取城市信息
            if ($request->has('prov_id')){

                $prov_id = intval($request->input('prov_id'));

                $city = CityModel::select('id','city_name')->where('prov_id',$prov_id)->get();

                return response()->json(['success'=>true,'city'=>$city]);

            }

        }

        $province = ProvinceModel::select('prov_id','prov_name')->get();

        return view('modules.admin.service.mass',['province'=>$province]);

    }



    /**
     * 设置群发信息
     */
    public function setMessage(Request $request)
    {
        if ($request->ajax()){

            $status = false;

            //暂时群发设置的人数为50个
            $data = [
                'message' =>[
                    1=> [
                        'delay'   => [3,8],     //随即时间作为延迟
                    ]
                ]

            ];

            $input = $request->only('text','picture');

            if ($input['text']){

                $status = true;

                $data['message'][1]['text'] = e($input['text']);

            }

            if ($request->hasFile('picture')){

                $status = true;
        
                $file = screenFile($request->file('picture'),1);

                if(!$file['success'])  return $file;

                $data['message'][1]['picture'] = 'http://www.maidamaida.com'.$file['path'];

            }

            if (!$status)   return response()->json(['success'=>false,'msg'=>'必须填其中一个选项！']);

            if ($request->has('delay')){

                $delay = $request->input('delay');

                if ($delay === '3-5'){

                    $data['message'][1]['delay'] = [3,5];

                }elseif ($delay === '6-11'){

                    $data['message'][1]['delay'] = [6,11];

                }elseif ($delay === '12-20'){

                    $data['message'][1]['delay'] = [12,20];

                }

            }
            
            $url  = 'http://rzwei.cn:5050/setmessage?id='.md5(Auth::user()->email);

            $data = json_encode($data);

            $result = $this->curlGet($url,'POST',$data);

            if ($result['success'] && $result['data'] === 'false'){

                return response()->json(['success'=>false,'msg'=>'设置内容失败！']);

            }

            return $result;

        }else{

            return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }

    }

    /**
     *  设置群发对象的条件
     *
     */
    public function setCondition(Request $request)
    {
        if ($request->ajax()){

            //暂时群发设置的人数为5000个
            $data = ['condition'=>['Count'=>5000]];

            $input = $request->only('ChatRoom','Sex','Province','City');

            //勾选人式的发送
            if ($request->has('username')){


                $username = $request->input('username');

                $count    = count($username);

                for ($i=0;$i<$count;$i++){

                    $data['condition']['UserName'][$i] = $username[$i];
                }

            }elseif ($request->has('all')){

                $data = ['condition'=>['Count'=>5000]];

            }else{
                //条件式群发

                //如果是群发的话，就不看下面两个条件了。
                if ($input['ChatRoom'] === 'true'){

                    $data['condition']['ChatRoom'] = true;

                }else{

                    if ($input['Sex'] === '1'){

                        $data['condition']['Sex'] = 1;

                    }

                    if($input['Sex'] === '2'){

                        $data['condition']['Sex'] = 2;

                    }

                    if ($input['Province'] !== '0'){

                        $province = ProvinceModel::select('prov_name')->where('prov_id',intval($input['Province']))->first();

                        $data['condition']['Province'] = $province->prov_name;
                    }

                    if ($input['City'] !== '0'){

                        $city  = CityModel::select('city_name')->where('id',intval($input['City']))->first();

                        //因为该处取出来的 有些都带有县、区之类的词 ，有的得去掉 才行
                        $len = mb_strlen($city->city_name);

                        $end = mb_substr($city->city_name, -1,1,'UTF-8');

                        if ($end === '区' || $end === '市' || $end === '县') {

                            $data['condition']['City'] = mb_substr($city->city_name, 0,$len-1,'UTF-8');

                        }else{

                            $data['condition']['City'] = $city->city_name;

                        }

                    }

                }

            }

            $url = 'http://rzwei.cn:5050/setcondition?id='.md5(Auth::user()->email);

            $data = json_encode($data);

            $result = $this->curlGet($url,'POST',$data);

            if ($result['success'] && $result['data'] === 'false'){

                return response()->json(['success'=>false,'msg'=>'请求失败！']);

            }

            return $result;

        }else{

            return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }

    }

    //群发，第一次请求，成功返回true,随后请求返回 群发到第几个，发送完了，转为了false
    public  function toSend()
    {
        $url = 'http://rzwei.cn:5050/qunfa?id='.md5(Auth::user()->email);

        $result = $this->curlGet($url,'GET');

        if ($result['success'] && $result['data'] === 'false'){

            return response()->json(['success'=>false,'msg'=>false]);

        }

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
