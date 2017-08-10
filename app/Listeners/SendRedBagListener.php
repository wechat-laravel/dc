<?php

namespace App\Listeners;

use App\Events\SendRedBagEvent;
use App\Models\ProvinceModel;
use App\Models\RedBagModel;
use App\Models\RedLogModel;
use App\Models\TasksModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Admin\Service\WxMchPayHelper;
use Latrell\QQWry\Facades\QQWry;

class SendRedBagListener implements ShouldQueue
{
    protected $send_name;
    protected $wishing;
    protected $act_name;
    protected $remark;
    protected $total_amount;
    protected $tasks_id;
    protected $ip;

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
     * @param  SendRedBagEvent $event
     * @return void
     */
    public function handle(SendRedBagEvent $event)
    {
        /*
        $action = 1;//1，转发给好友/群。2，分享到朋友圈
        $open_id = 'ome0zxMDVimw_OjyYS2rXikLQIKo';
        $tasks_id = 1;
        $offer = 1;//1分享后立即发放 2分享的内容被好友查看后再看
        $ip = '';//通过ip来判定是否为指定区域
        $sex = 1;//性别 0未知 1男 2女
        event(new SendRedBagEvent($action,$open_id,$tasks_id,$offer,$ip, $sex));
        1，每次发红包，对dc_red_bag表中的amount--
        2,单个用户24小时内领取一次 领取两次 领取五次
        */

        $this->tasks_id = $event->tasks_id;
        //先给一个值，方便后续判断
        $place = true;

        $data = RedBagModel::where('tasks_id', $this->tasks_id)
            ->select('status', 'amount', 'taxonomy','money', 'begin_at', 'end_at', 'send_name','offer','wishing',
                'act_name', 'remark', 'get_limit', 'action','sex','area','province','city','total')->first();


        if (!$event->ip) {

            $event->ip = '127.0.0.1';

        }

        //如果有指定地区，先看下是否符合

        if(isset($data) ){

            if($data->area == 1){

                $prov_name = ProvinceModel::select('prov_name')->where('prov_id',intval($data->province))->first();

                if ($prov_name){

                    //根据IP获取地址
                    $record = QQWry::query($event->ip);

                    //如果检测到返回值有 success 那就表示异常了。正常的应该直接返回的是 'country' 'area'两个字段
                    if (isset($record['success'])){

                        $place = false;

                    }else{
                        //返回的只取country  该格式为：云南省昆明市... 省市都带的有  已有的部分不会说少个市 省 县的字眼
                        //先判断第一层（省名）是否相同
                        if(!strstr($record['country'], $prov_name->prov_name)){

                            $place = false;

                        }else{
                            //city如果有的话，也判断
                            if(trim($data->city)){

                                if (!strstr($record['country'],trim($data->city))){

                                    $place = false;

                                }

                            }

                        }

                    }

                }else{

                    $place = false;
                }
            }

        }

        //判断这个文章是否有红包功能
        if (!$data) {

            $mail['email'] = 'hackqy@qq.com';
            $mail['notice'] = '该文章并没有配置红包功能，但是在调用接口。文章id为' . $this->tasks_id;

            Cache::remember('tasks_id-' . $this->tasks_id . '-not', 60 * 24, function () use ($mail) {
//                Mail::send('noticeMail', $mail, function ($message) use ($mail) {
//                    $message->to($mail['email'])->subject("微问数据--通知消息");
//                });
                return '该文章并没有配置红包功能';
            });
        }

        //判断这个活动停止了没有
        else if ($data->status == 0) {

            $mail['email'] = 'hackqy@qq.com';
            $mail['notice'] = '该帐号的红包功能已经关闭，文章仍在传播！文章id为' . $this->tasks_id;

            Cache::remember('tasks_id-' . $this->tasks_id . '-status0', 60 * 24, function () use ($mail) {
//                Mail::send('noticeMail', $mail, function ($message) use ($mail) {
//                    $message->to($mail['email'])->subject("微问数据--通知消息");
//                });
                return '该文章任务的红包功能已经关闭';
            });

        }

        //判断账户余额是否充足
        else if ($data->amount <= 0) {

            $mail['email'] = 'hackqy@qq.com';
            $mail['notice'] = '该帐号的红包功能余额不足！文章id为' . $this->tasks_id;

            Cache::remember('tasks_id-' . $this->tasks_id . '-amount', 60 * 24, function () use ($mail) {
//                Mail::send('noticeMail', $mail, function ($message) use ($mail) {
//                    $message->to($mail['email'])->subject("微问数据--通知消息");
//                });
                return '该帐号的红包功能余额不足';
            });
        }

        //判断调用接口的时候是否还在进行
        else if (time() < strtotime($data->begin_at) || time() > strtotime($data->end_at)) {
            $mail['email'] = 'hackqy@qq.com';
            $mail['notice'] = '该帐号的红包功能活动时间已经过期，仍在调用接口！文章id为' . $this->tasks_id;

            Cache::remember('tasks_id-' . $this->tasks_id . '-beign_at||end_at', 60 * 24, function () use ($mail) {
//                Mail::send('noticeMail', $mail, function ($message) use ($mail) {
//                    $message->to($mail['email'])->subject("微问数据--通知消息");
//                });
                return '该帐号的红包功能活动时间已经过期';
            });
        }

        //判断是否指定性别
        else if(!($data->sex == 3 || $data->sex == $event->sex)){
            //停止
        }

        //判断是否指定城市
        else if(!($data->area === 0 || $place)){
            //停止
        }
        //判断红包动作，分享朋友圈/分享朋友  如果满足设置的条件开始发红包
        else if (preg_match("/$event->action/", $data->action)) {

            //判断用户有没有达到领取上限 没有达到才可以继续领取
            $get_limit = RedLogModel::where('open_id', $event->open_id)
                ->where('tasks_id', $event->tasks_id)
                ->where('status', 1)
                ->count();

            if ($get_limit >= $data->get_limit) {
                $mail['email'] = 'hackqy@qq.com';
                $mail['notice'] = '该用户已经到了领取上限，仍在调用接口！文章id为' . $this->tasks_id . '用户id为' . $event->open_id;

                Cache::remember('open_id-' . $event->open_id . '-open_id', 60 * 24, function () use ($mail) {
//                    Mail::send('noticeMail', $mail, function ($message) use ($mail) {
//                        $message->to($mail['email'])->subject("微问数据--通知消息");
//                    });
                    return '该用户已经到了领取上限';
                });
            }

            //没有达到上限再给他发
            else {
                //判断传入的offer是否和规则一致。一致再发
                if($data->offer == $event->offer){
                    $this->send_name = $data->send_name;
                    $this->wishing = $data->wishing;
                    $this->act_name = $data->act_name;
                    $this->remark = $data->remark;
                    //随机金额
                    if ($data->taxonomy == 2) {
                        $money_base = explode('-', $data->money);
                        $total_amount = mt_rand($money_base[0], $money_base[1]);
                        while($total_amount > $data->amount){
                            $total_amount = mt_rand($money_base[0], $money_base[1]);
                        }
                        $this->total_amount = $total_amount;
                        $this->send($event->open_id);
                    } else {
                        $this->total_amount = $data->money;
                        $this->send($event->open_id);
                    }
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
    public function send($openid)
    {
        $param = [
            "nonce_str" => str_random(32),//随机字符串 不长于32位
            "mch_billno" => 'weiwen' . date('YmdHis') . rand(1000, 9999),//订单号
            "mch_id" => env('MCH_ID'),//商户号
            "wxappid" => env('WECHAT_APPID'),
            "send_name" => $this->send_name,//红包发送者名称 微问数据
            "re_openid" => $openid,
            "total_amount" => $this->total_amount * 100,//付款金额，单位分
            "total_num" => 1,//红包发放总人数
            "wishing" => $this->wishing,//红包祝福语 恭喜发财
            "client_ip" => env('CLIENT_IP'),//调用接口的机器 Ip 地址
            "act_name" => $this->act_name,//活动名称 红包活动
            "remark" => $this->remark,//备注信息 快来抢
        ];
        $wxMchPayHelper = new WxMchPayHelper($param);
        $r = $wxMchPayHelper->send_redpack();
        //分析返回的 $r 对红包做记录 对活动红包金额amount减法处理
        if ($r->return_code == 'SUCCESS') {
            RedLogModel::create([
                'open_id' => $openid,
                'tasks_id' => $this->tasks_id,
                'total_amount' => isset($r->total_amount) ? $r->total_amount/100 : 0,
                'return_code' => isset($r->return_code) ? $r->return_code : 0,
                'return_msg' => isset($r->return_msg) ? $r->return_msg : 0,
                'result_code' => isset($r->result_code) ? $r->result_code : 0,
                'err_code' => isset($r->err_code) ? $r->err_code : 0,
                'err_code_des' => isset($r->err_code_des) ? $r->err_code_des : 0,
                'mch_billno' => isset($r->mch_billno) ? $r->mch_billno : 0,
                'send_listid' => isset($r->send_listid) ? $r->send_listid : 0,
                'status' => $r->result_code == 'SUCCESS' ? 1 : 2,
            ]);
            if($r->result_code == 'SUCCESS'){

                RedBagModel::where('tasks_id', $this->tasks_id)->decrement('amount', $r->total_amount/100);
            }

        }

        return 'success';
    }


    /**
     * @param $xml
     * 把xml转化为数组
     * @return mixed
     */
    function xmlToArray($xml)
    {

        //禁止引用外部xml实体

        libxml_disable_entity_loader(true);

        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $val = json_decode(json_encode($xmlstring), true);

        return $val;

    }

}
