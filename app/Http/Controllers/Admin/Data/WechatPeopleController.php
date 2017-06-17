<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\SpreadPeopleModel;
use App\Models\SpreadRecordModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WechatPeopleController extends Controller
{

    protected  $info_data = [];

    public function index(Request $request,$id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::find(intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $data = [
                    'levels'  => [],                                                     //记录当前文章有几个层级
                    'cate'    => [],                                                     //层级格式
                    'user'    => [],                                                     //存储openid对应的用户名
                    'data'    => [['name'=>'起点','symbolSize'=>20,'value'=>0]],         //网状图数据
                    'links'   => [],                                                     //阶层链接
            ];

            $res = SpreadPeopleModel::select('openid','upper','level','name')
                                ->where('tasks_id',intval($id))
                                ->orderBy('created_at','asc')
                                ->get();

            foreach ($res as $re){

                if (!in_array($re->level_name,$data['levels'])){

                    $data['levels'][] = $re->level_name;

                    $data['cate'][] = ['name'=>$re->level_name];

                }

                $data['user'][$re->openid] = $re->name;


                $data['data'][] = [

                    'name'     => $re->name,

                    'category' => $re->level_name,

                    'value'    => $re->level

                ];

                if ($re->upper){

                    $data['links'][] = [

                        'source' => $data['user'][$re->upper],

                        'target' => $re->name,

                    ];

                }else{

                    $data['links'][] = [

                        'source' => '起点',

                        'target' => $re->name,

                    ];

                }

            }

            return response()->json(['success'=>true,'data'=>$data]);

        }

        return view('modules.admin.data.wechat_people',['task_id'=>$task->id]);

    }

    public function peoples($id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::find(intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        $people = SpreadPeopleModel::where('level',1)->where('tasks_id',intval($id))->paginate(10);

        return response($people);

    }

    public function onDown(Request $request,$task_id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($task_id))->first();

        }else{

            $task = TasksModel::find(intval($task_id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        $id = $request->input('id');

        $id = intval(ltrim($id,'s'));

        $current = SpreadPeopleModel::find($id);

        if ($current){

            $res = SpreadPeopleModel::where('upper',$current->openid)->where('tasks_id',intval($task_id))->orderBy('created_at','asc')->get();

            $str = "";

            foreach ($res as $re){

                $margin = ($re->level-1)*10;

                if ($re->level_num){

                    $str .= "<tr><td style='text-align:left' id=s".$re->id."><i style='margin-left:".$margin."px;' class='glyphicon glyphicon-triangle-right'></i>$re->name</td><td>$re->level_name</td><td>".$re->level_num." / ".$re->people_num."</td><td>$re->read_num</td><td>$re->read_at</td><td>$re->sex_name</td><td>".$re->province.'-'.$re->city."</td></tr>";
                    
                }else{

                    $str .= "<tr><td style='text-align:left' id=s".$re->id."><i style='margin-left:".$margin."px;'></i>$re->name</td><td>$re->level_name</td><td>".$re->level_num." / ".$re->people_num."</td><td>$re->read_num</td><td>$re->read_at</td><td>$re->sex_name</td><td>".$re->province.'-'.$re->city."</td></tr>";
                    
                }

            }

            return response()->json(['success'=>true,'html'=>$str]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);
        }

    }


    public function onForward(Request $request,$id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::find(intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $res = SpreadPeopleModel::where('tasks_id',intval($id))->orderBy('created_at','asc')
                ->with([

                    'user'=>function($query){
                        $query->select('openid','name','avatar');
                    },
                    'upp' =>function($query){
                        $query->select('openid','name');
                    },
                    'record' => function($query){
                        $query->select('openid','upper')->where('action','browse')->groupBy('openid');
                    },
                    'records' => function($query){
                        $query->select('openid','upper')->where('action','browse');
                    },
                    'single' => function($query){
                        $query->select('openid')->where('action','wechat');
                    },
                    'double' => function($query){
                        $query->select('openid')->where('action','wechat_group');
                    },
                    'qqs' => function($query){
                        $query->select('openid')->where('action','qq');
                    },
                    'qqzone' => function($query){
                        $query->select('openid')->where('action','qzone');
                    },
                ])->paginate(10);

            return response($res);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    public function onLayer(Request $request,$id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::find(intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $layer = intval($request->input('layer'));

            $res   = SpreadPeopleModel::where('level',$layer)
                    ->where('tasks_id',intval($id))
                    ->with([
                        'user'=>function($query){
                            $query->select('openid','avatar');
                        },
                        'single'=>function($query){
                            //TODO 这里first取得时候有问题
                            $query->select('id','openid','stay')->where('action','browse')->orderBy('created_at','desc');
                        }
                    ])->paginate(10);

            return response($res);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    public function onInfo(Request $request,$id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::find(intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $id = intval($request->input('id'));

            $this->info_data = [];

            $res = SpreadPeopleModel::where('id',$id)
                            ->with([
                                'user'=>function($query){
                                    $query->select('openid','avatar');
                            }])->first();

            $this->info_data[] = ['name'=>$res->name,'avatar'=>$res->user->avatar,'created_at'=>$res->created_at];

            if ($res->upper){

                $this->infos($res->upper,$task->id);

            }

            $new = array_reverse($this->info_data);

            return response()->json(['success'=>true,'data'=>$new]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    public function infos($openid,$id){

        $res = SpreadPeopleModel::where('openid',$openid)
                    ->where('tasks_id',$id)
                    ->with([
                        'user'=>function($query){
                            $query->select('openid','avatar');
                    }])->first();

        $this->info_data[] = ['name'=>$res->name,'avatar'=>$res->user->avatar,'created_at'=>$res->created_at];

        if ($res->upper){

            $this->infos($res->upper,$id);

        }else{

            return true;

        }

    }

}
