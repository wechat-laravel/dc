<?php

namespace App\Listeners;

use App\Events\SendRedBagEvent;
use App\Models\RedBagModel;
use App\Models\RedLogModel;
use App\Models\TasksModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\Service\WxMchPayHelper;

class SendRedBagListener implements ShouldQueue
{
    protected $send_name;
    protected $wishing;
    protected $act_name;
    protected $remark;
    protected $total_amount;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendRedBagEvent  $event
     * @return void
     */
    public function handle(SendRedBagEvent $event)
    {
        /*
        $action = 1;//1，转发给好友/群。2，分享到朋友圈
        $open_id = 'ome0zxMDVimw_OjyYS2rXikLQIKo';
        $tasks_id = 1;
        event(new SendRedBagEvent($action,$open_id,$tasks_id));
        1，每次发红包，对dc_red_bag表中的amount--
        2,单个用户24小时内领取一次 领取两次 领取五次
        */

        $tasks_id = $event->tasks_id;
        $data = RedBagModel::where('id',100)
            ->select('status','amount', 'taxonomy',
                'money','begin_at','end_at','send_name',
                'wishing', 'act_name', 'remark', 'get_limit')
            ->first();

        //判断这个文章是否有红包功能
        if(!$data){
            $mail['email'] = '810281839@qq.com';
            $mail['notice'] = '该文章并没有配置红包功能，但是在调用接口。文章id为'.$tasks_id;

            Cache::remember('tasks_id-'.$tasks_id.'-not',60 * 24 ,function()use($mail){
                Mail::send('noticeMail',$mail,function($message) use($mail){
                    $message->to($mail['email'])->subject("微问数据--通知消息");
                });
                return '已经发送邮件';
            }) ;
        }

        //判断这个活动停止了没有
        else if($data->status == 0){
            $mail['email'] = '810281839@qq.com';
            $mail['notice'] = '该帐号的红包功能已经关闭，文章仍在传播！文章id为'.$tasks_id;

            Cache::remember('tasks_id-'.$tasks_id.'-status0',60 * 24 ,function()use($mail){
                Mail::send('noticeMail',$mail,function($message) use($mail){
                    $message->to($mail['email'])->subject("微问数据--通知消息");
                });
                return '已经发送邮件';
            }) ;
        }

        //判断账户余额是否充足
        else if($data->amount <= 0){
            $mail['email'] = '810281839@qq.com';
            $mail['notice'] = '该帐号的红包功能余额不足！文章id为'.$tasks_id;

            Cache::remember('tasks_id-'.$tasks_id.'-amount',60 * 24 ,function()use($mail){
                Mail::send('noticeMail',$mail,function($message) use($mail){
                    $message->to($mail['email'])->subject("微问数据--通知消息");
                });
                return '已经发送邮件';
            }) ;
        }

        //判断调用接口的时候是否还在进行
        else if( time() < $data->begin_at || time() > $data->end_at){
            $mail['email'] = '810281839@qq.com';
            $mail['notice'] = '该帐号的红包功能活动时间已经过期，仍在调用接口！文章id为'.$tasks_id;

            Cache::remember('tasks_id-'.$tasks_id.'-beign_at||end_at',60 * 24 ,function()use($mail){
                Mail::send('noticeMail',$mail,function($message) use($mail){
                    $message->to($mail['email'])->subject("微问数据--通知消息");
                });
                return '已经发送邮件';
            }) ;
        }

        //判断红包动作，分享朋友圈/分享朋友  如果满足设置的条件开始发红包
        else if(strstr($data->action, $event->action)){
            //判断用户有没有达到领取上限 没有达到才可以继续领取
            $get_limit = RedLogModel::where('open_id', $event->open_id)
                ->where('tasks_id', $event->tasks_id)
                ->count();

            if($get_limit >= $data->get_limit){
                $mail['email'] = '810281839@qq.com';
                $mail['notice'] = '该用户已经到了领取上限，仍在调用接口！文章id为'.$tasks_id.'用户id为'.$event->open_id;

                Cache::remember('open_id-'.$event->open_id.'-open_id',60 * 24 ,function()use($mail){
                    Mail::send('noticeMail',$mail,function($message) use($mail){
                        $message->to($mail['email'])->subject("微问数据--通知消息");
                    });
                    return '已经发送邮件';
                }) ;
            }

            //没有达到上限再给他发
            else{
                $this->send_name = $data->send_name;
                $this->total_amount = $data->amount;
                $this->wishing = $data->wishing;
                $this->act_name = $data->act_name;
                $this->remark = $data->remark;
                //随机金额
                if($data->taxonomy == 2){
                    $money_base = explode('-',$data->money);
                    $this->send($event->open_id, $money_base[0], $money_base[1]);
                }else{
                    $this->send($event->open_id, $data->money, $data->money);
                }
            }
        }
    }

    /**
     * 发送红包
     * @param $openid
     * @param $money_begin
     * @param $money_end
     */
    public function send($openid, $money_begin, $money_end)
    {
        $param = [
            "nonce_str" => str_random(32),//随机字符串 不长于32位
            "mch_billno" => 'weiwen' . date('YmdHis') . rand(1000, 9999),//订单号
            "mch_id" => env('MCH_ID'),//商户号
            "wxappid" => env('WECHAT_APPID'),
            "send_name" => $this->send_name,//红包发送者名称 微问数据
            "re_openid" => $openid,
            "total_amount" => $this->total_amount*100,//付款金额，单位分
            "min_value" => $money_begin*100,//最小红包金额，单位分
            "max_value" => $money_end*100,//最大红包金额，单位分
            "total_num" => 1,//红包发放总人数
            "wishing" => $this->wishing,//红包祝福语 恭喜发财
            "client_ip" => env('CLIENT_IP'),//调用接口的机器 Ip 地址
            "act_name" => $this->act_name,//活动名称 红包活动
            "remark" => $this->remark,//备注信息 快来抢
        ];
        $wxMchPayHelper = new WxMchPayHelper($param);
        $r = $wxMchPayHelper->send_redpack();
        Log::info($r);
        //simplexml_load_string()

        //分析返回的 $r 对红包做记录 对活动红包金额amount减法处理
        //RedLogModel::create([]);
    }


    /**
     * @param $xml
     * 把xml转化为数组
     * @return mixed
     */
    function xmlToArray($xml){

        //禁止引用外部xml实体

        libxml_disable_entity_loader(true);

        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $val = json_decode(json_encode($xmlstring),true);

        return $val;

    }

}
