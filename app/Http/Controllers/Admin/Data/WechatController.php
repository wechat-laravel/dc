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
                'pv_num' => 0,  //PV总数
                'pv_today' => 0,  //PV今天总数
                'pv_yesterday' => 0,  //PV昨天总数
                'pv_everyday' => [0, 0, 0, 0, 0, 0, 0], //pv
                'uv_num' => 0,  //UV总数
                'uv_today' => 0,  //UV今天总数
                'uv_yesterday' => 0,  //UV昨天总数
                'uv_everyday' => [0, 0, 0, 0, 0, 0, 0],
                'share_num' => 0,  //分享总数
                'share_today' => 0,  //分享今天总数
                'share_yesterday' => 0,  //分享昨天总数
                'share_everyday' => [0, 0, 0, 0, 0, 0, 0],
                'stay_avg' => 0,  //平均停留时长
                'days' => [],
            ];

            //最多统计7天

            $today = date('Y-m-d', time());

            $today = strtotime($today);

            $days = [$today, $today - 86400, $today - 172800, $today - 259200, $today - 345600, $today - 432000, $today - 518400];

            $top['days'] = [date('m-d', $days[6]), date('m-d', $days[5]), date('m-d', $days[4]), date('m-d', $days[3]), date('m-d', $days[2]), date('m-d', $days[1]), date('m-d', $days[0])];

            $uv = [[], [], [], [], [], [], [], []];

            //总浏览量
            $res = SpreadRecordModel::select('openid', 'action', 'stay', 'level', 'created_at')->orderBy('created_at', 'asc')->get();

            foreach ($res as $re) {

                //PV,UV,分享统计与走势
                if ($re->action === 'browse') {

                    //今天
                    if (strtotime($re->created_at) >= $days[0]) {

                        $top['pv_today'] += 1;

                        $uv[0] = [];

                        if (!in_array($re->openid, $uv[6])) {

                            $uv[6][] = $re->openid;

                            $top['uv_today'] += 1;

                            $top['uv_everyday'][6] += 1;

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

                    }

                    $top['pv_num'] += 1;

                    $top['stay_avg'] += $re->stay;

                } else {

                    //今天
                    if (strtotime($re->created_at) >= $days[0]) {

                        $top['share_today'] += 1;

                        $top['share_everyday'][6] += 1;

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

                }

            }

            $top['stay_avg'] = intval($top['stay_avg'] / $top['pv_num']);

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
