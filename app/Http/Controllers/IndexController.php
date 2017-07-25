<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    public function index()
    {
        return view('welcome');
    }

    public function image()
    {
        $url = \Input::get('src');

        if(empty($url))
        {
            return '';
        }

        $headers['HOST'] = parse_url($url,PHP_URL_HOST);

        $headerArr       = [];

        foreach ($headers as $n => $v)
        {
            $headerArr[] = $n . ':' . $v;
        }

        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArr);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_HEADER,0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        header("Content-type: image/jpeg");

        echo $output;

    }

}
