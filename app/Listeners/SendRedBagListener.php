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
        */

        $tasks_id = $event->tasks_id;
        $data = RedBagModel::where('id',$tasks_id)
            ->select('status','amount', 'taxonomy', 'money','begin_at','end_at')
            ->first();

        //判断这个活动停止了没有
        if($data->status == 0){
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

        //红包没有停止，并且账户有余额，判断红包类型
        else if($data->taxonomy == 1){
            //固定金额 调用接口直接发固定金额红包
            $this->send($event->open_id);
        }
        else if($data->taxonomy == 2){
            //随机金额
            $money_base = explode('-',$data->money);
            $money = mt_rand($money_base[0],$money_base[1])/5;
            $this->send($event->open_id);
        }
    }

    public function send($openid)
    {
        $param = [
            "nonce_str" => str_random(32),//随机字符串 不长于32位
            "mch_billno" => 'weiwen' . date('YmdHis') . rand(1000, 9999),//订单号
            "mch_id" => env('MCH_ID'),//商户号
            "wxappid" => env('WECHAT_APPID'),
            "send_name" => '微问数据',//红包发送者名称
            "re_openid" => $openid,
            "total_amount" => 100,//付款金额，单位分
            "min_value" => 100,//最小红包金额，单位分
            "max_value" => 100,//最大红包金额，单位分
            "total_num" => 1,//红包发放总人数
            "wishing" => '恭喜发财',//红包祝福语
            "client_ip" => env('CLIENT_IP'),//调用接口的机器 Ip 地址
            "act_name" => '红包活动',//活动名称
            "remark" => '快来抢！',//备注信息
        ];
        $wxMchPayHelper = new WxMchPayHelper($param);
        $r = $wxMchPayHelper->send_redpack();
        Log::info($r);

        //分析返回的 $r 对红包做记录
        //RedLogModel::create([]);
    }
}
