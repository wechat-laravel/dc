<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\SpreadRecordModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class WechatPeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){

            $data = [
                    'levels' => [],                                                     //记录当前文章有几个层级
                    'cate'   => [],                                                     //层级格式
                    'user'   => [],                                                     //存储openid对应的用户名
                    'data'   => [['name'=>'起点','symbolSize'=>30,'value'=>0]],         //网状图数据
                    'links'  => [],                                                     //阶层链接
            ];

            $res = SpreadRecordModel::select('openid','upper','source','level')
                                ->where('action','browse')
                                ->orderBy('created_at','asc')
                                ->groupBy('openid')
                                ->with([
                                    'user'=>function($query){
                                        $query->select('openid','name','city');
                                    }
                                ])->get();

            foreach ($res as $re){

                if (!in_array($re->level_name,$data['levels'])){

                    $data['levels'][] = $re->level_name;

                    $data['cate'][] = ['name'=>$re->level_name];

                }

                $data['user'][$re->openid] = $re->user->name;


                $data['data'][] = [

                    'name'     => $re->user->name,

                    'category' => $re->level_name,

                    'value'    => $re->level

                ];

                if ($re->upper){

                    $data['links'][] = [

                        'source' => $data['user'][$re->upper],

                        'target' => $re->user->name,

                    ];

                }else{

                    $data['links'][] = [

                        'source' => '起点',

                        'target' => $re->user->name,

                    ];

                }

            }

            return response()->json(['success'=>true,'data'=>$data]);

        }

        return view('modules.admin.data.wechat_people');

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
