<?php

namespace App\Http\Controllers;


class WechatController extends Controller
{
    protected $wechat;


    public function __construct()
    {
        $wechat = app('wechat');

        $this->wechat = $wechat;
    }


    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $this->wechat->server->setMessageHandler(function($message){

            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

        });

        return $this->wechat->server->serve();
    }

    public function addMenu()
    {
        try{

            $buttons = [
                [
                    "type" => "click",
                    "name" => "今日歌曲",
                    "key"  => "V1001_TODAY_MUSIC"
                ],
                [
                    "name"       => "菜单",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "搜索",
                            "url"  => "http://www.soso.com/"
                        ],
                        [
                            "type" => "view",
                            "name" => "视频",
                            "url"  => "http://v.qq.com/"
                        ],
                        [
                            "type" => "click",
                            "name" => "赞一下我们",
                            "key" => "V1001_GOOD"
                        ],
                    ],
                ],
            ];

            $menu = $this->wechat->menu;

            $menus = $menu->all();

//            $menu->add($buttons);

        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

          return response($menus);
//        return response()->json(['success'=>true,'msg'=>'创建成功']);

    }



}
