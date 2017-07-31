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
                'current_num' => 0,                                     //当前浏览人数
                'days' => [],
                'level' => ['pv'=>[0,0,0,0,0,0,0,0,0,0,0],
                            'uv'=>[0,0,0,0,0,0,0,0,0,0,0],
                            'share'=>[0,0,0,0,0,0,0,0,0,0,0]],          //传播层级统计(放了11个单元，方便每一层直接对应，最后去掉头一个单元)
                'stay' => ['this'=>[0,0,0,0,0,0,0,0,0,0],
                            'all'=>[0,0,0,0,0,0,0,0,0,0]],              //停留时长统计
                'browse'=>[
                            ['value'=>0,'name'=>'微信好友'],
                            ['value'=>0,'name'=>'QQ好友'],
                            ['value'=>0,'name'=>'朋友圈'],
                            ['value'=>0,'name'=>'微信群'],
                            ['value'=>0,'name'=>'QQ空间'],                 //来源统计
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

            //检测是否有缓存
            if (Redis::hexists($id.'_top','pv_today')){

                $result = $this->returnRedis($top,$task->id);

                if($result['success']){

                    return response()->json($result);

                }

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

                            //微信来源
                            case 'wechat':

                                $top['browse'][0]['value'] += 1 ;

                                break;

                            //QQ好友
                            case 'qq':

                                $top['browse'][1]['value'] += 1 ;

                                break;

                            //朋友圈
                            case 'timeline':

                                $top['browse'][2]['value'] += 1 ;

                                break;
                            //微信群
                            case 'wechat_group':

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
                $time = time()-5;
                //列出所有浏览记录的id索引
                $list  = Redis::smembers('record_id_list');

                if (empty($list)){

                    $top['current_num'] = 0;

                }else{

                    foreach ($list as $list_id){
                        //根据ID找对应的HASH表数据
                        $res = Redis::hgetall($list_id);

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

            //该任务的停留时长统计 （未算占比，方便计数）
            //键为10个秒段的起始位置，0-5S对应的键名0 6-10s对应的键名6
            Redis::hmset($id.'_stay_this',
                [
                    0=>$top['stay']['this'][0],6=>$top['stay']['this'][1],11=>$top['stay']['this'][2],21=>$top['stay']['this'][3],41=>$top['stay']['this'][4],
                    81=>$top['stay']['this'][5],161=>$top['stay']['this'][6],321=>$top['stay']['this'][7],641=>$top['stay']['this'][8],1281=>$top['stay']['this'][9]
                ]);
            //计算各个时间段的占比
            for ($i =0 ;$i <10; $i++){

                if ($top['pv_num'] === 0 ) {

                    $top['stay']['this'][$i] = 0;

                }else{

                    $top['stay']['this'][$i] = round(($top['stay']['this'][$i] * 100) / $top['pv_num']);

                }

            }
            //该任务访问时间分布统计 （未算占比，方便计数）
            Redis::hmset($id.'_visit_this',
                [
                    0=>$top['visit']['this'][0],1=>$top['visit']['this'][1],2=>$top['visit']['this'][2],3=>$top['visit']['this'][3],
                    4=>$top['visit']['this'][4],5=>$top['visit']['this'][5],6=>$top['visit']['this'][6],7=>$top['visit']['this'][7],
                    8=>$top['visit']['this'][8],9=>$top['visit']['this'][9],10=>$top['visit']['this'][10],11=>$top['visit']['this'][11],
                    12=>$top['visit']['this'][12],13=>$top['visit']['this'][13],14=>$top['visit']['this'][14],15=>$top['visit']['this'][15],
                    16=>$top['visit']['this'][16],17=>$top['visit']['this'][17],18=>$top['visit']['this'][18],19=>$top['visit']['this'][19],
                    20=>$top['visit']['this'][20],21=>$top['visit']['this'][21],22=>$top['visit']['this'][22],23=>$top['visit']['this'][23]
                ]);

            //计算访问时间的各时间段的占比
            for ($i =0 ;$i <24; $i++){

                if ($top['pv_num'] === 0){

                    $top['visit']['this'][$i] = 0;

                }else{

                    $top['visit']['this'][$i] = round(($top['visit']['this'][$i] * 100) / $top['pv_num']);

                }

            }
            //数据统计
            //TOP 数据图头部4个模块的缓存存放
            Redis::hmset($id.'_top',
                [
                    'pv_today'=>$top['pv_today'],'pv_yesterday'=>$top['pv_yesterday'],'pv_num'=>$top['pv_num'],
                    'uv_today'=>$top['uv_today'],'uv_yesterday'=>$top['uv_yesterday'],'uv_num'=>$top['uv_num'],
                    'share_today'=>$top['share_today'],'share_yesterday'=>$top['share_yesterday'],'share_num'=>$top['share_num'],
                    'current_num'=>$top['current_num']
                ]);
            //来源统计
            Redis::hmset($id.'_browse',
                [
                    'wechat'=>$top['browse'][0]['value'],'qq'=>$top['browse'][1]['value'],
                    'timeline'=>$top['browse'][2]['value'],'wechat_group'=>$top['browse'][3]['value'],
                    'qzone'=>$top['browse'][4]['value']
                ]);
            //分享统计
            Redis::hmset($id.'_action',
                [
                    'wechat'=>$top['action'][0]['value'],'qq'=>$top['action'][1]['value'],
                    'timeline'=>$top['action'][2]['value'],'wechat_group'=>$top['action'][3]['value'],
                    'qzone'=>$top['action'][4]['value']
                ]);
            //PU/UV/SHARE 每日统计
            Redis::hmset($id.'_pv_day',
                [
                    $top['days'][0]=>$top['pv_everyday'][0],$top['days'][1]=>$top['pv_everyday'][1],$top['days'][2]=>$top['pv_everyday'][2],
                    $top['days'][3]=>$top['pv_everyday'][3],$top['days'][4]=>$top['pv_everyday'][4],$top['days'][5]=>$top['pv_everyday'][5],
                    $top['days'][6]=>$top['pv_everyday'][6]
                ]);
            Redis::hmset($id.'_uv_day',
                [
                    $top['days'][0]=>$top['uv_everyday'][0],$top['days'][1]=>$top['uv_everyday'][1],$top['days'][2]=>$top['uv_everyday'][2],
                    $top['days'][3]=>$top['uv_everyday'][3],$top['days'][4]=>$top['uv_everyday'][4],$top['days'][5]=>$top['uv_everyday'][5],
                    $top['days'][6]=>$top['uv_everyday'][6]
                ]);
            Redis::hmset($id.'_share_day',
                [
                    $top['days'][0]=>$top['share_everyday'][0],$top['days'][1]=>$top['share_everyday'][1],$top['days'][2]=>$top['share_everyday'][2],
                    $top['days'][3]=>$top['share_everyday'][3],$top['days'][4]=>$top['share_everyday'][4],$top['days'][5]=>$top['share_everyday'][5],
                    $top['days'][6]=>$top['share_everyday'][6]
                ]);
            //PU/UV/SHARE 每时统计
            Redis::hmset($id.'_pv_hour',
                [
                    0=>$top['current']['pv'][0],1=>$top['current']['pv'][1],2=>$top['current']['pv'][2],3=>$top['current']['pv'][3],
                    4=>$top['current']['pv'][4],5=>$top['current']['pv'][5],6=>$top['current']['pv'][6],7=>$top['current']['pv'][7],
                    8=>$top['current']['pv'][8],9=>$top['current']['pv'][9],10=>$top['current']['pv'][10],11=>$top['current']['pv'][11],
                    12=>$top['current']['pv'][12],13=>$top['current']['pv'][13],14=>$top['current']['pv'][14],15=>$top['current']['pv'][15],
                    16=>$top['current']['pv'][16],17=>$top['current']['pv'][17],18=>$top['current']['pv'][18],19=>$top['current']['pv'][19],
                    20=>$top['current']['pv'][20],21=>$top['current']['pv'][21],22=>$top['current']['pv'][22],23=>$top['current']['pv'][23]
                ]);
            Redis::hmset($id.'_uv_hour',
                [
                    0=>$top['current']['uv'][0],1=>$top['current']['uv'][1],2=>$top['current']['uv'][2],3=>$top['current']['uv'][3],
                    4=>$top['current']['uv'][4],5=>$top['current']['uv'][5],6=>$top['current']['uv'][6],7=>$top['current']['uv'][7],
                    8=>$top['current']['uv'][8],9=>$top['current']['uv'][9],10=>$top['current']['uv'][10],11=>$top['current']['uv'][11],
                    12=>$top['current']['uv'][12],13=>$top['current']['uv'][13],14=>$top['current']['uv'][14],15=>$top['current']['uv'][15],
                    16=>$top['current']['uv'][16],17=>$top['current']['uv'][17],18=>$top['current']['uv'][18],19=>$top['current']['uv'][19],
                    20=>$top['current']['uv'][20],21=>$top['current']['uv'][21],22=>$top['current']['uv'][22],23=>$top['current']['uv'][23]
                ]);
            Redis::hmset($id.'_share_hour',
                [
                    0=>$top['current']['share'][0],1=>$top['current']['share'][1],2=>$top['current']['share'][2],3=>$top['current']['share'][3],
                    4=>$top['current']['share'][4],5=>$top['current']['share'][5],6=>$top['current']['share'][6],7=>$top['current']['share'][7],
                    8=>$top['current']['share'][8],9=>$top['current']['share'][9],10=>$top['current']['share'][10],11=>$top['current']['share'][11],
                    12=>$top['current']['share'][12],13=>$top['current']['share'][13],14=>$top['current']['share'][14],15=>$top['current']['share'][15],
                    16=>$top['current']['share'][16],17=>$top['current']['share'][17],18=>$top['current']['share'][18],19=>$top['current']['share'][19],
                    20=>$top['current']['share'][20],21=>$top['current']['share'][21],22=>$top['current']['share'][22],23=>$top['current']['share'][23]
                ]);
            //PU/UV/SHARE 层级统计
            Redis::hmset($id.'_level_pv',
                [
                    1=>$top['level']['pv'][0],2=>$top['level']['pv'][1],3=>$top['level']['pv'][2],4=>$top['level']['pv'][3],
                    5=>$top['level']['pv'][4],6=>$top['level']['pv'][5],7=>$top['level']['pv'][6],8=>$top['level']['pv'][7],
                    9=>$top['level']['pv'][8],10=>$top['level']['pv'][9]
                ]);
            Redis::hmset($id.'_level_uv',
                [
                    1=>$top['level']['uv'][0],2=>$top['level']['uv'][1],3=>$top['level']['uv'][2],4=>$top['level']['uv'][3],
                    5=>$top['level']['uv'][4],6=>$top['level']['uv'][5],7=>$top['level']['uv'][6],8=>$top['level']['uv'][7],
                    9=>$top['level']['uv'][8],10=>$top['level']['uv'][9]
                ]);
            Redis::hmset($id.'_level_share',
                [
                    1=>$top['level']['share'][0],2=>$top['level']['share'][1],3=>$top['level']['share'][2],4=>$top['level']['share'][3],
                    5=>$top['level']['share'][4],6=>$top['level']['share'][5],7=>$top['level']['share'][6],8=>$top['level']['share'][7],
                    9=>$top['level']['share'][8],10=>$top['level']['share'][9]
                ]);


            //设置过期时间为每天晚上的23点59分（因为这样可以免去再计算处理昨天的数据量，直接缓存的时候做好）
            $expire = strtotime(date('Y-m-d',time()).' 23:59');

            //如果是在59分到零点的这60秒内，这样生成的缓存，设置时间后其实是不存在的。还是过期的。不影响
            Redis::expireat($id.'_top',$expire);

            Redis::expireat($id.'_browse',$expire);

            Redis::expireat($id.'_action',$expire);

            Redis::expireat($id.'_pv_day',$expire);

            Redis::expireat($id.'_uv_day',$expire);

            Redis::expireat($id.'_share_day',$expire);

            Redis::expireat($id.'_pv_hour',$expire);

            Redis::expireat($id.'_uv_hour',$expire);

            Redis::expireat($id.'_share_hour',$expire);

            Redis::expireat($id.'_level_pv',$expire);

            Redis::expireat($id.'_level_uv',$expire);

            Redis::expireat($id.'_level_share',$expire);

            Redis::expireat($id.'_visit_this',$expire);

            Redis::expireat($id.'_stay_this',$expire);

            return response()->json(['success'=>true,'top'=>$top]);

        }

        return view('modules.admin.data.wechat_show',['task_id'=>$task->id,'title'=>$task->title]);

    }

    /**
     * 如果有缓存的话，把缓存的数据装到数组里
     * @param $top          //初始化的数组
     * @param  $tasks_id    //文章ID
     * @return array
     */
    public function returnRedis($top,$tasks_id){

        //数据页面的所有数据缓存都是到每天的23:59分过期，过期这时间段不使用缓存
        //expire表示的当前请求天的23:59分的时间戳，如果此时的时间戳大于这个表示 处于这一分钟内。
        $expire = strtotime(date('Y-m-d',time()).' 23:59');

        if (time() > $expire){

            $result = ['success'=>false,'msg'=>'该时间段不使用缓存'];

            return  $result;

        }

        //数据统计
        //***************头部4个*****************************
        $top['pv_num'] = Redis::hget($tasks_id.'_top','pv_num');
        $top['pv_today'] = Redis::hget($tasks_id.'_top','pv_today');
        $top['pv_yesterday'] = Redis::hget($tasks_id.'_top','pv_yesterday');
        $top['uv_num'] = Redis::hget($tasks_id.'_top','uv_num');
        $top['uv_today'] = Redis::hget($tasks_id.'_top','uv_today');
        $top['uv_yesterday'] = Redis::hget($tasks_id.'_top','uv_yesterday');
        $top['share_num'] = Redis::hget($tasks_id.'_top','share_num');
        $top['share_today'] = Redis::hget($tasks_id.'_top','share_today');
        $top['share_yesterday'] = Redis::hget($tasks_id.'_top','share_yesterday');


        //过期时间标准，允许有2秒的时差
        $time = time()-2;
        //列出所有浏览记录的id索引
        $list  = Redis::smembers('record_id_list');

        if (empty($list)){

            $top['current_num'] = 0;

        }else{

            foreach ($list as $list_id){
                //根据ID找对应的HASH表数据
                $res = Redis::hgetall($list_id);

                if ($res){

                    //小于这个时间，说明页面浏览已经停止了
                    if ($res['time'] > $time  && $res['tasks_id'] == $tasks_id){

                        $top['current_num'] += 1;

                    }

                }else{

                    continue;

                }

            }

        }

        //**************PU/UV/SHARE 每日走势***********************
        $top['days'] = Redis::hkeys($tasks_id.'_pv_day');
        $top['pv_everyday'] = Redis::hvals($tasks_id.'_pv_day');
        $top['uv_everyday'] = Redis::hvals($tasks_id.'_uv_day');
        $top['share_everyday'] = Redis::hvals($tasks_id.'_share_day');

        //$top['current']['day']  这个top里已经计算好了
        $top['current']['pv'] = Redis::hvals($tasks_id.'_pv_hour');
        $top['current']['uv'] = Redis::hvals($tasks_id.'_uv_hour');
        $top['current']['share'] = Redis::hvals($tasks_id.'_share_hour');

        //*************层级分布*************************
        $top['level']['pv'] = Redis::hvals($tasks_id.'_level_pv');
        $top['level']['uv'] = Redis::hvals($tasks_id.'_level_uv');
        $top['level']['share'] = Redis::hvals($tasks_id.'_level_share');

        //*************停留时长分布**********************
        //因为停留时长记录的是数，而不是占比 所以这里需要计算一下
        $top['stay']['this'] = Redis::hvals($tasks_id.'_stay_this');

        //计算各个时间段的占比
        for ($i =0 ;$i <10; $i++){

            if ($top['pv_num'] === 0 ) {

                $top['stay']['this'][$i] = 0;

            }else{

                $top['stay']['this'][$i] = round(($top['stay']['this'][$i] * 100) / $top['pv_num']);

            }

        }
        //**************访问时间分布************************
        $top['visit']['this'] = Redis::hvals($tasks_id.'_visit_this');

        //计算访问时间的各时间段的占比
        for ($i =0 ;$i <24; $i++){

            if ($top['pv_num'] === 0){

                $top['visit']['this'][$i] = 0;

            }else{

                $top['visit']['this'][$i] = round(($top['visit']['this'][$i] * 100) / $top['pv_num']);

            }

        }

        //************访问来源************************
        $browse  = Redis::hvals($tasks_id.'_browse');

        for($i=0; $i<5; $i++){

            $top['browse'][$i]['value'] = $browse[$i];

        }

        //************分享去向************************
        $action  = Redis::hvals($tasks_id.'_action');

        for($i=0; $i<5; $i++){

            $top['action'][$i]['value'] = $action[$i];

        }

        $result = ['success'=>true,'top'=>$top];

        return $result;

    }

    //统计所有任务
    public  function avg($id)
    {
        //
//        SpreadRecordModel::select('');

        return 1;

    }



}
