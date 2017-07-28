<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\SpreadRecordModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class WechatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $id = intval($id);

        if (Auth::user()->identity !== 'admin'){
            //用哪些列取哪些列，不要全部取出来
            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',$id)->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',$id)->first();

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()) {
            //检测是否有缓存
//            if (!Redis::hexists($id.'_top','pv_today')){

//            }

            //数据统计
            $top = [
                'pv_num' => 0,                                          //PV总数
                'pv_today' => 0,                                        //PV今天总数
                'pv_yesterday' => 0,                                    //PV昨天总数
                'pv_everyday' => [0, 0, 0, 0, 0, 0, 0],                 //pv
                'uv_num' => 0,                                          //UV总数
                'uv_today' => 0,                                        //UV今天总数
                'uv_yesterday' => 0,                                    //UV昨天总数
                'uv_everyday' => [0, 0, 0, 0, 0, 0, 0],
                'share_num' => 0,                                       //分享总数
                'share_today' => 0,                                     //分享今天总数
                'share_yesterday' => 0,                                 //分享昨天总数
                'share_everyday' => [0, 0, 0, 0, 0, 0, 0],
                'current_num' => 0,                                        //平均停留时长
                'days' => [],
                'level' => ['pv'=>[0,0,0,0,0,0,0,0,0,0,0],
                            'uv'=>[0,0,0,0,0,0,0,0,0,0,0],
                            'share'=>[0,0,0,0,0,0,0,0,0,0,0]],          //传播层级统计(放了11个单元，方便每一层直接对应，最后去掉头一个单元)
                'stay' => ['this'=>[0,0,0,0,0,0,0,0,0,0],
                            'all'=>[0,0,0,0,0,0,0,0,0,0]],              //停留时长统计
                'browse'=>[
                            ['value'=>0,'name'=>'单人对话'],
                            ['value'=>0,'name'=>'朋友圈'],
                            ['value'=>0,'name'=>'微信群'],
                            ['value'=>0,'name'=>'公众号文章'],
                            ['value'=>0,'name'=>'其他'],                 //来源统计
                ],
                'action'=>[
                    ['value'=>0,'name'=>'微信好友'],
                    ['value'=>0,'name'=>'QQ好友'],
                    ['value'=>0,'name'=>'朋友圈'],
                    ['value'=>0,'name'=>'微信群'],
                    ['value'=>0,'name'=>'QQ空间'],                       //分享统计
                ],
                'current'=>[                                             //当日的每小时走势
                    'day'   => [],                                       //当日整点的时间字符串（如：07-20 0:00..）
                    'pv'    => [
                        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                    ],
                    'uv'    => [
                        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                    ],
                    'share' => [
                        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                    ],
                ],
                'visit'=>[                                               //访问时间分布的每小时走势
                    'this'=>[
                        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                    ],
                    'all'=>[
                        0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0
                    ],
                ]
            ];

            $day = date('m-d',time());

            for($i=0;$i<24;$i++){

                $top['current']['day'][] = $day.' '.$i.':00';

            }

            //最多统计7天
            $today = date('Y-m-d', time());

            $today = strtotime($today);
            //7天的时间戳（如：1501147718）
            $days = [$today, $today - 86400, $today - 172800, $today - 259200, $today - 345600, $today - 432000, $today - 518400];
            //7天的时间（如：7-27）
            $top['days'] = [date('m-d', $days[6]), date('m-d', $days[5]), date('m-d', $days[4]), date('m-d', $days[3]), date('m-d', $days[2]), date('m-d', $days[1]), date('m-d', $days[0])];

            $uv = [[], [], [], [], [], [], [], []];

            //因为不能一次性把数据都取出来，先分批处理。 先查出多少条数据，然后每次1000条的取
            $times = SpreadRecordModel::where('tasks_id',$id)->count('id');

            $times = ceil($times/1000);

            //每次取出数据 就处理数据，避免一次数据过大
            for ($i =0 ; $i < $times;$i++){
                //偏移量
                $offset = $i * 1000;
                //每次取出的条数
                $limit  = 1000;

                $res = SpreadRecordModel::select('openid','source','action', 'stay', 'level', 'created_at')->where('tasks_id',$id)->orderBy('created_at', 'asc')->skip($offset)->take($limit)->get()->toArray();

                foreach ($res as $re) {
                    //PV,UV,分享统计与走势
                    if ($re['action'] === 'browse') {

                        //今天
                        if ($re['created_at'] >= $days[0]) {

                            $top['pv_today'] += 1;

                            $top['current']['pv'][intval(date('H',$re['created_at']))] += 1;

                            $uv[0] = [];

                            if (!in_array($re['openid'], $uv[6])) {

                                $uv[6][] = $re['openid'];

                                $top['uv_today'] += 1;

                                $top['uv_everyday'][6] += 1;

                                $top['current']['uv'][intval(date('H',$re['created_at']))] += 1;

                            }

                            $top['pv_everyday'][6] += 1;

                        } elseif ($re['created_at'] >= $days[1] && $re['created_at'] < $days[0]) {

                            $top['pv_yesterday'] += 1;

                            if (!in_array($re['openid'], $uv[5])) {

                                $uv[5][] = $re['openid'];

                                $top['uv_yesterday'] += 1;

                                $top['uv_everyday'][5] += 1;

                            }

                            $top['pv_everyday'][5] += 1;

                        } elseif ($re['created_at'] >= $days[2] && $re['created_at'] < $days[1]) {

                            if (!in_array($re['openid'], $uv[4])) {

                                $uv[4][] = $re['openid'];

                                $top['uv_everyday'][4] += 1;

                            }

                            $top['pv_everyday'][4] += 1;

                        } elseif ($re['created_at'] >= $days[3] && $re['created_at'] < $days[2]) {

                            if (!in_array($re['openid'], $uv[3])) {

                                $uv[3][] = $re['openid'];

                                $top['uv_everyday'][3] += 1;

                            }

                            $top['pv_everyday'][3] += 1;

                        } elseif ($re['created_at'] >= $days[4] && $re['created_at'] < $days[3]) {

                            if (!in_array($re['openid'], $uv[2])) {

                                $uv[2][] = $re['openid'];

                                $top['uv_everyday'][2] += 1;

                            }

                            $top['pv_everyday'][2] += 1;

                        } elseif ($re['created_at'] >= $days[5] && $re['created_at'] < $days[4]) {

                            if (!in_array($re['openid'], $uv[1])) {

                                $uv[1][] = $re['openid'];

                                $top['uv_everyday'][1] += 1;

                            }

                            $top['pv_everyday'][1] += 1;

                        } elseif ($re['created_at'] >= $days[6] && $re['created_at'] < $days[5]) {

                            if (!in_array($re['openid'], $uv[0])) {

                                $uv[0][] = $re['openid'];

                                $top['uv_everyday'][0] += 1;

                            }

                            $top['pv_everyday'][0] += 1;

                        }

                        if (!in_array($re['openid'], $uv[7])) {

                            $uv[7][] = $re['openid'];

                            $top['uv_num'] += 1;

                            $top['level']['uv'][$re['level']] += 1;

                        }

                        $top['pv_num'] += 1;

                        $top['level']['pv'][$re['level']] += 1;

                        //时间段统计
                        if ($re['stay'] <= 5){

                            $top['stay']['this'][0] += 1;

                        }elseif ($re['stay'] > 5 && $re['stay'] <= 10 ){

                            $top['stay']['this'][1] += 1;

                        }elseif ($re['stay'] > 10 && $re['stay'] <= 20 ){

                            $top['stay']['this'][2] += 1;

                        }elseif ($re['stay'] > 20 && $re['stay'] <= 40 ){

                            $top['stay']['this'][3] += 1;

                        }elseif ($re['stay'] > 40 && $re['stay'] <= 80 ){

                            $top['stay']['this'][4] += 1;

                        }elseif ($re['stay'] > 80 && $re['stay'] <= 160 ){

                            $top['stay']['this'][5] += 1;

                        }elseif ($re['stay'] > 160 && $re['stay'] <= 320 ){

                            $top['stay']['this'][6] += 1;

                        }elseif ($re['stay'] > 320 && $re['stay'] <= 640 ){

                            $top['stay']['this'][7] += 1;

                        }elseif ($re['stay'] > 640 && $re['stay'] <= 1280 ){

                            $top['stay']['this'][8] += 1;

                        }elseif ($re['stay'] > 1280 ){

                            $top['stay']['this'][9] += 1;

                        }

                        //统计来源数据
                        switch ($re['source']){

                            //单人对话
                            case 'wechat':

                                $top['browse'][0]['value'] += 1 ;

                                break;

                            //朋友圈
                            case 'timeline':

                                $top['browse'][1]['value'] += 1 ;

                                break;

                            //微信群
                            case 'wechat_group':

                                $top['browse'][2]['value'] += 1 ;

                                break;

                            case 'article':

                                $top['browse'][3]['value'] += 1 ;

                                break;

                            //其他
                            default :

                                $top['browse'][4]['value'] += 1 ;

                                break;
                        }

                        $top['visit']['this'][intval(date('H',$re['created_at']))] += 1;

                    } else {

                        //今天
                        if ($re['created_at'] >= $days[0]) {

                            $top['share_today'] += 1;

                            $top['share_everyday'][6] += 1;

                            $top['current']['share'][intval(date('H',$re['created_at']))] += 1;

                        } elseif ($re['created_at'] >= $days[1] && $re['created_at'] < $days[0]) {

                            $top['share_yesterday'] += 1;

                            $top['share_everyday'][5] += 1;

                        } elseif ($re['created_at'] >= $days[2] && $re['created_at'] < $days[1]) {

                            $top['share_everyday'][4] += 1;

                        } elseif ($re['created_at'] >= $days[3] && $re['created_at'] < $days[2]) {

                            $top['share_everyday'][3] += 1;

                        } elseif ($re['created_at'] >= $days[4] && $re['created_at'] < $days[3]) {

                            $top['share_everyday'][2] += 1;

                        } elseif ($re['created_at'] >= $days[5] && $re['created_at'] < $days[4]) {

                            $top['share_everyday'][1] += 1;

                        } elseif ($re['created_at'] >= $days[6] && $re['created_at'] < $days[5]) {

                            $top['share_everyday'][0] += 1;

                        }

                        $top['share_num'] += 1;

                        $top['level']['share'][$re['level']] += 1;

                        //统计分享去向
                        switch ($re['action']){

                            //QQ好友
                            case 'qq':

                                $top['action'][1]['value'] += 1 ;

                                break;

                            //朋友圈
                            case 'timeline':

                                $top['action'][2]['value'] += 1 ;

                                break;

                            //微信群
                            case 'wechat_group':

                                $top['action'][3]['value'] += 1 ;

                                break;

                            case 'qzone':

                                $top['action'][4]['value'] += 1 ;

                                break;

                            //默认分享给微信好友
                            default :

                                $top['action'][0]['value'] += 1 ;

                                break;
                        }

                    }

                }

            }

            //当前浏览人数
            if ($top['pv_num'] === 0){

                $top['current_num'] = 0;

            }else{

                //过期时间标准，允许有5秒的时差
                $time = time()-60;
                //列出所有浏览记录的id索引
                $list  = Redis::smembers('record_id_list');

                if (empty($list)){

                    $top['current_num'] = 0;

                }else{

                    foreach ($list as $id){
                        //根据ID找对应的HASH表数据
                        $res = Redis::hgetall($id);

                        if ($res){

                            //小于这个时间，说明页面浏览已经停止了
                            if ($res['time'] > $time  && $res['tasks_id'] == $id){

                                $top['current_num'] += 1;

                            }

                        }else{

                            continue;

                        }

                    }

                }

            }

            //传播层级统计(放了11个单元，每一层直接对应，没有0层的，所以最后去掉头一个单元)
            array_shift($top['level']['pv']);

            array_shift($top['level']['uv']);

            array_shift($top['level']['share']);

            //计算各个时间段的占比
            for ($i =0 ;$i <10; $i++){

                if ($top['pv_num'] === 0 ) {

                    $top['stay']['this'][$i] = 0;

                }else{

                    $top['stay']['this'][$i] = round(($top['stay']['this'][$i] * 100) / $top['pv_num']);

                }

            }

            //计算访问时间的各时间段的占比
            for ($i =0 ;$i <24; $i++){

                if ($top['pv_num'] === 0){

                    $top['visit']['this'][$i] = 0;

                }else{

                    $top['visit']['this'][$i] = round(($top['visit']['this'][$i] * 100) / $top['pv_num']);

                }

            }

//            //数据图头部4个模块的缓存存放
//            Redis::hmset($id.'_top',
//                [
//                    'pv_today'=>$top['pv_today'],'pv_yesterday'=>$top['pv_yesterday'],'pv_num'=>$top['pv_num'],
//                    'uv_today'=>$top['uv_today'],'uv_yesterday'=>$top['uv_yesterday'],'uv_num'=>$top['uv_num'],
//                    'share_today'=>$top['share_today'],'share_yesterday'=>$top['share_yesterday'],'share_num'=>$top['share_num'],
//                    'current_num'=>$top['current_num']
//                ]);
//            //设置过期时间为每天晚上的零点（因为这样可以免去再计算处理昨天的数据量，直接缓存的时候做好）
//            Redis::expire($id.'_top',259200);



            return response()->json(['success'=>true,'top'=>$top]);

        }

        return view('modules.admin.data.wechat_show',['task_id'=>$task->id,'title'=>$task->title]);

    }

    //统计所有任务
    public  function avg($id)
    {
        //
//        SpreadRecordModel::select('');

        return 1;

    }



}
