<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\SpreadRecordModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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
                'stay_avg' => 0,                                        //平均停留时长
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
                    'day'   => [],
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
                'visit'=>[                                               //访问时间分布
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

            $days = [$today, $today - 86400, $today - 172800, $today - 259200, $today - 345600, $today - 432000, $today - 518400];

            $top['days'] = [date('m-d', $days[6]), date('m-d', $days[5]), date('m-d', $days[4]), date('m-d', $days[3]), date('m-d', $days[2]), date('m-d', $days[1]), date('m-d', $days[0])];

            $uv = [[], [], [], [], [], [], [], []];

            //总浏览量
            $res = SpreadRecordModel::select('openid','source','action', 'stay', 'level', 'created_at')->orderBy('created_at', 'asc')->get();

            foreach ($res as $re) {

                //var_dump();exit;

                //PV,UV,分享统计与走势
                if ($re->action === 'browse') {

                    //今天
                    if (strtotime($re->created_at) >= $days[0]) {

                        $top['pv_today'] += 1;

                        $top['current']['pv'][intval(substr($re->created_at,11,2))] += 1;

                        $uv[0] = [];

                        if (!in_array($re->openid, $uv[6])) {

                            $uv[6][] = $re->openid;

                            $top['uv_today'] += 1;

                            $top['uv_everyday'][6] += 1;

                            $top['current']['uv'][intval(substr($re->created_at,11,2))] += 1;

                        }

                        $top['pv_everyday'][6] += 1;

                    } elseif (strtotime($re->created_at) >= $days[1] && strtotime($re->created_at) < $days[0]) {

                        $top['pv_yesterday'] += 1;

                        if (!in_array($re->openid, $uv[5])) {

                            $uv[5][] = $re->openid;

                            $top['uv_yesterday'] += 1;

                            $top['uv_everyday'][5] += 1;

                        }

                        $top['pv_everyday'][5] += 1;

                    } elseif (strtotime($re->created_at) >= $days[2] && strtotime($re->created_at) < $days[1]) {

                        if (!in_array($re->openid, $uv[4])) {

                            $uv[4][] = $re->openid;

                            $top['uv_everyday'][4] += 1;

                        }

                        $top['pv_everyday'][4] += 1;

                    } elseif (strtotime($re->created_at) >= $days[3] && strtotime($re->created_at) < $days[2]) {

                        if (!in_array($re->openid, $uv[3])) {

                            $uv[3][] = $re->openid;

                            $top['uv_everyday'][3] += 1;

                        }

                        $top['pv_everyday'][3] += 1;

                    } elseif (strtotime($re->created_at) >= $days[4] && strtotime($re->created_at) < $days[3]) {

                        if (!in_array($re->openid, $uv[2])) {

                            $uv[2][] = $re->openid;

                            $top['uv_everyday'][2] += 1;

                        }

                        $top['pv_everyday'][2] += 1;

                    } elseif (strtotime($re->created_at) >= $days[5] && strtotime($re->created_at) < $days[4]) {

                        if (!in_array($re->openid, $uv[1])) {

                            $uv[1][] = $re->openid;

                            $top['uv_everyday'][1] += 1;

                        }

                        $top['pv_everyday'][1] += 1;

                    } elseif (strtotime($re->created_at) >= $days[6] && strtotime($re->created_at) < $days[5]) {

                        if (!in_array($re->openid, $uv[0])) {

                            $uv[0][] = $re->openid;

                            $top['uv_everyday'][0] += 1;

                        }

                        $top['pv_everyday'][0] += 1;

                    }

                    if (!in_array($re->openid, $uv[7])) {

                        $uv[7][] = $re->openid;

                        $top['uv_num'] += 1;

                        $top['level']['uv'][$re->level] += 1;

                    }

                    $top['pv_num'] += 1;

                    $top['level']['pv'][$re->level] += 1;

                    $top['stay_avg'] += $re->stay;

                    //时间段统计
                    if ($re->stay <= 5){

                        $top['stay']['this'][0] += 1;

                    }elseif ($re->stay > 5 && $re->stay <= 10 ){

                        $top['stay']['this'][1] += 1;

                    }elseif ($re->stay > 10 && $re->stay <= 20 ){

                        $top['stay']['this'][2] += 1;

                    }elseif ($re->stay > 20 && $re->stay <= 40 ){

                        $top['stay']['this'][3] += 1;

                    }elseif ($re->stay > 40 && $re->stay <= 80 ){

                        $top['stay']['this'][4] += 1;

                    }elseif ($re->stay > 80 && $re->stay <= 160 ){

                        $top['stay']['this'][5] += 1;

                    }elseif ($re->stay > 160 && $re->stay <= 320 ){

                        $top['stay']['this'][6] += 1;

                    }elseif ($re->stay > 320 && $re->stay <= 640 ){

                        $top['stay']['this'][7] += 1;

                    }elseif ($re->stay > 640 && $re->stay <= 1280 ){

                        $top['stay']['this'][8] += 1;

                    }elseif ($re->stay > 1280 ){

                        $top['stay']['this'][9] += 1;

                    }

                    //统计来源数据
                    switch ($re->source){

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

                    $top['visit']['this'][intval(substr($re->created_at,11,2))] += 1;

                } else {

                    //今天
                    if (strtotime($re->created_at) >= $days[0]) {

                        $top['share_today'] += 1;

                        $top['share_everyday'][6] += 1;

                        $top['current']['share'][intval(substr($re->created_at,11,2))] += 1;

                    } elseif (strtotime($re->created_at) >= $days[1] && strtotime($re->created_at) < $days[0]) {

                        $top['share_yesterday'] += 1;

                        $top['share_everyday'][5] += 1;

                    } elseif (strtotime($re->created_at) >= $days[2] && strtotime($re->created_at) < $days[1]) {

                        $top['share_everyday'][4] += 1;

                    } elseif (strtotime($re->created_at) >= $days[3] && strtotime($re->created_at) < $days[2]) {

                        $top['share_everyday'][3] += 1;

                    } elseif (strtotime($re->created_at) >= $days[4] && strtotime($re->created_at) < $days[3]) {

                        $top['share_everyday'][2] += 1;

                    } elseif (strtotime($re->created_at) >= $days[5] && strtotime($re->created_at) < $days[4]) {

                        $top['share_everyday'][1] += 1;

                    } elseif (strtotime($re->created_at) >= $days[6] && strtotime($re->created_at) < $days[5]) {

                        $top['share_everyday'][0] += 1;

                    }

                    $top['share_num'] += 1;

                    $top['level']['share'][$re->level] += 1;

                    //统计分享去向
                    switch ($re->action){

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

            $top['stay_avg'] = intval($top['stay_avg'] / $top['pv_num']);

            array_shift($top['level']['pv']);

            array_shift($top['level']['uv']);

            array_shift($top['level']['share']);

            //计算各个时间段的占比
            for ($i =0 ;$i <10; $i++){

                $top['stay']['this'][$i] = round(($top['stay']['this'][$i] * 100) / $top['pv_num']);

            }

            //计算访问时间的各时间段的占比

            for ($i =0 ;$i <24; $i++){

                $top['visit']['this'][$i] = round(($top['visit']['this'][$i] * 100) / $top['pv_num']);

            }

            return response()->json(['success'=>true,'top'=>$top]);

        }

        return view('modules.admin.data.wechat_show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
