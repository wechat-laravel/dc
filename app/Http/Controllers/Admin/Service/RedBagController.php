<?php

namespace App\Http\Controllers\Admin\Service;

use App\Events\SendRedBagEvent;
use App\Models\CityModel;
use App\Models\ProvinceModel;
use App\Models\RedBagModel;
use App\Models\RedLogModel;
use App\Models\TasksModel;
use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class RedBagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$action = 1;//1，转发给好友/群。2，分享到朋友圈
        $open_id = 'ome0zxCc1qvM_bLzYIOTzy2BVzlg';
        $tasks_id = 20;
        $offer = 2;//1分享后立即发放 2分享的内容被好友查看后再看
        event(new SendRedBagEvent($action,$open_id,$tasks_id,$offer));
        exit;*/
        if (\Input::ajax()) {
            $getType = \Input::get('getType');

            //获取用户的监控的文章
            if($getType == 'article'){
                if(\Auth::user()->identity == 'admin'){
                    $article_base = TasksModel::select(['id', 'title'])->get();
                }else{
                    $article_base = TasksModel::where('user_id', \Auth::id())->select(['id', 'title'])->get();
                }

                $article        = [];
                foreach($article_base as $value){
                    $article[] = ['id'=>$value->id,'title'=>$value->title];
                }

                return response()->json($article);

            }

            //获取用户设置的红包规则
            if($getType == 'redBag'){
                if(\Auth::user()->identity == 'admin'){
                    return RedBagModel::with('title')->paginate(10);
                }
                return RedBagModel::where('user_id', \Auth::id())->with('title')->paginate(10);
            }

            //获取省份列表
            if($getType == 'province'){
                return ProvinceModel::orderBy('id','desc')->paginate(35);
            }

            //通过省份获取城市
            if($getType == 'city'){
                return CityModel::where('prov_id', \Input::get('prov_id'))
                    ->orderBy('id','asc')
                    ->paginate(100);
            }

            //充值金额
            if($getType == 'chongzhiCommit'){
                $modal = RedBagModel::where('id',\Input::get('id'))->select('user_id')->first();
                $balance = UserModel::where('id', $modal->user_id)->pluck('balance');
                if($balance < \Input::get('total')){
                    return response()->json(['success'=>false, 'msg'=>'账户余额不足，联系客户充值！qq：2905582908']);
                }else{
                    RedBagModel::where('id',\Input::get('id'))->increment('total',\Input::get('total'));
                    return response()->json(['success'=>true, 'msg'=>'充值成功']);
                }
            }
        }

        return view('modules.admin.service.red_bag');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = \Validator::make($request->all(), [
            'event' => 'required',
            'article_id' => 'required',
            'amount' => 'required',
            'action' => 'required',
            'begin_at' => 'required',
            'send_name' => 'required',
            'wishing' => 'required',
            'act_name' => 'required',
            'get_limit' => 'required',
        ], [
            'required' => ':attribute 不能为空'
        ]);

        if ($validate->fails()) {
            return response()->json(['success'=>false, 'msg'=>$validate->errors()->first()]);
        }

        //判断该文章是否已经加了红包功能
        $tasks_id_exists = RedBagModel::where('tasks_id',$request->get('article_id'))->exists();

        if($tasks_id_exists){
            return response()->json(['success'=>false,'msg'=>'该文章已经添加过红包功能了，不能重复添加！']);
        }

        //判断账户余额是否足够
        if($request->get('amount') > \Auth::user()->balance){
            return response()->json(['success'=>false,'msg'=>'您的余额不足，请联系客服充值！']);
        }

        UserModel::where('id',\Auth::id())->decrement('balance',$request->get('amount'));

        $money = $request->get('money');
        //判断红包类型 taxonomy 1固定金额 2随机金额
        if($request->get('taxonomy') == 2){
            $money = $request->get('money_suiji_begin') . '-' .$request->get('money_suiji_end');
        }

        //处理开始结束时间
        $time = explode('-',$request->get('begin_at'));

        RedBagModel::create([
            'user_id'=>\Auth::id(),
            'event'=>e($request->get('event')),
            'tasks_id'=>intval($request->get('article_id')),
            'total'=>intval($request->get('amount')),
            'amount'=>intval($request->get('amount')),
            'action'=>$request->get('action'),
            'taxonomy'=>$request->get('taxonomy'),
            'money'=>$money,
            'get_limit'=>intval($request->get('get_limit')),
            'begin_at'=>strtotime($time[0]),
            'end_at'=>strtotime($time[1]),
            'send_name'=>e($request->get('send_name')),
            'wishing'=>e($request->get('wishing')),
            'act_name'=>e($request->get('act_name')),
            'remark'=>e($request->get('remark')),
            'sex'=>intval($request->get('sex')),
            'area'=>intval($request->get('area')),
            'province'=>intval($request->get('area')),
            'city'=>intval($request->get('area')),
        ]);
        return response()->json(['success'=>true, 'msg'=>'执行成功']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //修改配置
        if($id == 'editConfig'){
            $validate = \Validator::make($request->all(), [
                'event' => 'required',
                'action' => 'required',
                'begin_at' => 'required',
                'send_name' => 'required',
                'wishing' => 'required',
                'act_name' => 'required',
                'get_limit' => 'required',
            ], [
                'required' => ':attribute 不能为空'
            ]);

            if ($validate->fails()) {
                return response()->json(['success'=>false, 'msg'=>$validate->errors()->first()]);
            }


            $money = $request->get('money');
            //判断红包类型 taxonomy 1固定金额 2随机金额
            if($request->get('edit_taxonomy') == 2){
                $money = $request->get('edit_money_suiji_begin') . '-' .$request->get('edit_money_suiji_end');
            }

            //处理开始结束时间
            $time = explode('-',$request->get('begin_at'));

            RedBagModel::where('id',$request->get('id'))->update([
                'event'=>e($request->get('event')),
                'action'=>$request->get('action'),
                'taxonomy'=>$request->get('edit_taxonomy'),
                'money'=>$money,
                'get_limit'=>intval($request->get('get_limit')),
                'begin_at'=>strtotime($time[0]),
                'end_at'=>strtotime($time[1]),
                'send_name'=>e($request->get('send_name')),
                'wishing'=>e($request->get('wishing')),
                'act_name'=>e($request->get('act_name')),
                'remark'=>e($request->get('remark')),
                'sex'=>intval($request->get('sex')),
                'area'=>intval($request->get('area')),
                'province'=>$request->get('province') ? $request->get('province') : '',
                'city'=>$request->get('city') ? implode(" ", $request->get('city')) : '',
            ]);
            return response()->json(['success'=>true, 'msg'=>'执行成功']);
        }


        RedBagModel::where('id',$id)->update(['status'=>1]);
        return response()->json(['success'=>true, 'msg'=>'操作成功']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RedBagModel::where('id',$id)->update(['status'=>0]);
        return response()->json(['success'=>true, 'msg'=>'操作成功']);
    }
}
