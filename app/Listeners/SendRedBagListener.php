<?php

namespace App\Listeners;

use App\Events\SendRedBagEvent;
use App\Models\RedBagModel;
use App\Models\TasksModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

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
        }
        else if($data->taxonomy == 2){
            //随机金额
            $money_base = explode('-',$data->money);
            $money = mt_rand($money_base[0],$money_base[1])/5;
        }
    }
}
