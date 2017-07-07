<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\GrantUserModel;
use App\Models\SpreadPeopleModel;
use App\Models\SpreadRecordModel;
use App\Models\TasksModel;
use App\Models\UsersRemarkModel;
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

            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',intval($id))->first();

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $data = [
                    'levels'  => [],                                                     //记录当前文章有几个层级
                    'cate'    => [],                                                     //层级格式
                    'user'    => [],                                                     //存储openid对应的用户名
                    'data'    => [['name'=>'起点','symbolSize'=>30,'value'=>0]],         //网状图数据
                    'links'   => [],                                                     //阶层链接
            ];

            $res = SpreadPeopleModel::select('id','openid','upper','level','name','read_num')
                                ->where('tasks_id',intval($id))
                                ->orderBy('created_at','asc')
                                ->get();

            foreach ($res as $re){

                if (!in_array($re->level_name,$data['levels'])){

                    $data['levels'][] = $re->level_name;

                    $data['cate'][] = ['name'=>$re->level_name];

                }

                $data['user'][$re->openid] = $re->name.'：ID'.$re->id;
                
                $data['data'][] = [

                    'name'     => $re->name.'：ID'.$re->id,

                    'category' => $re->level_name,

                    'value'    => $re->read_num

                ];

                if ($re->upper){

                    $data['links'][] = [

                        'source' => $data['user'][$re->upper],

                        'target' => $re->name.'：ID'.$re->id,

                    ];

                }else{

                    $data['links'][] = [

                        'source' => '起点',

                        'target' => $re->name.'：ID'.$re->id,

                    ];

                }

            }

            return response()->json(['success'=>true,'data'=>$data]);

        }

        return view('modules.admin.data.wechat_people',['task_id'=>$task->id,'title'=>$task->title]);

    }

    public function peoples($id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',intval($id))->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',intval($id));

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        $people = SpreadPeopleModel::where('level',1)->where('tasks_id',intval($id))->orderBy('people_num','desc')->paginate(10);

        return response()->json($people);

    }

    public function onDown(Request $request,$task_id)
    {
        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',intval($task_id))->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',intval($task_id))->first();

        }

        $color = ['null','null','#FF0000','#228B22','#FF7F00','#000080','#996600','#0099CC','#9933CC','#339999','#FF33CC','#336633','#CCC00'];

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

                    $str .= "<tr><td style='text-align:left;' id=s".$re->id."><i style='margin-left:".$margin."px;' class='glyphicon glyphicon-triangle-right'></i>$re->name</td><td style='color:".$color[$re->level]."'>$re->level_name</td><td>".$re->level_num." / ".$re->people_num."</td><td>$re->read_num</td><td>$re->read_at</td><td>$re->sex_name</td><td>".$re->province.'-'.$re->city."</td></tr>";
                    
                }else{

                    $str .= "<tr><td style='text-align:left;' id=s".$re->id."><i style='margin-left:".$margin."px;'></i>$re->name</td><td style='color:".$color[$re->level]."'>$re->level_name</td><td>".$re->level_num." / ".$re->people_num."</td><td>$re->read_num</td><td>$re->read_at</td><td>$re->sex_name</td><td>".$re->province.'-'.$re->city."</td></tr>";
                    
                }

            }

            return response()->json(['success'=>true,'html'=>$str]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);
        }

    }


    public function onForward(Request $request,$id)
    {
        $id = intval($id);

        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',$id)->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',$id)->first();

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        //选出来转发过的
        $ids = SpreadRecordModel::select('openid')->where('tasks_id',$id)->whereIn('action',['wechat','wechat_group','timeline','qq','qzone'])->groupBy('openid')->get();

        $openids = [];

        foreach ($ids as $ds ){

            $openids[] = $ds->openid;

        }

        if ($request->ajax()){

            $res = SpreadPeopleModel::where('tasks_id',$id)->whereIn('openid',$openids)->orderBy('people_num','desc')
                ->with([

                    'user'=>function($query){
                        $query->select('openid','name','avatar');
                    },
                    'upp' =>function($query){
                        $query->select('openid','name');
                    },
                    'record' => function($query) use($id){
                        $query->select('openid','upper')->where('action','browse')->where('tasks_id',$id)->groupBy('openid');
                    },
                    'records' => function($query) use($id){
                        $query->select('openid','upper')->where('action','browse')->where('tasks_id',$id);
                    },
                    'single' => function($query) use($id){
                        $query->select('openid')->where('action','wechat')->where('tasks_id',$id);
                    },
                    'double' => function($query) use($id){
                        $query->select('openid')->where('action','wechat_group')->where('tasks_id',$id);
                    },
                    'timeline' => function($query) use($id){
                        $query->select('openid')->where('action','timeline')->where('tasks_id',$id);
                    },
                    'qqs' => function($query) use($id){
                        $query->select('openid')->where('action','qq')->where('tasks_id',$id);
                    },
                    'qqzone' => function($query) use($id){
                        $query->select('openid')->where('action','qzone')->where('tasks_id',$id);
                    },
                ])->paginate(10);

            return response()->json($res);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    public function onLayer(Request $request,$id)
    {
        if ($request->ajax()){

            if (Auth::user()->identity !== 'admin'){

                $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',intval($id))->first();

            }else{

                $task = TasksModel::select('id','title')->where('id',intval($id))->first();

            }

            if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

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
                    ])->orderBy('people_num','desc')->paginate(10);

            return response()->json($res);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    //用户来源路径
    public function onInfo(Request $request,$id)
    {
        $id = intval($id);

        $people_id = intval($request->input('people_id'));

        $openid    = e($request->input('openid'));

        $user_info = GrantUserModel::where('openid',$openid)->first();

        //用户备注信息
        $user_remark = UsersRemarkModel::where('openid',$openid)->where('user_id',Auth::id())->first();

        if (!$user_info) return response()->json(['success'=>false,'msg'=>'没有该用户，非法的请求！']);

        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::select('id','title')->where('user_id',Auth::id())->where('id',$id)->first();

        }else{

            $task = TasksModel::select('id','title')->where('id',$id)->first();

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $this->info_data = [];

            $res = SpreadPeopleModel::where('id',$people_id)
                            ->with([
                                'user'=>function($query){
                                    $query->select('openid','avatar');
                            }])->first();

            $this->info_data[] = ['id'=>$res->id,'openid'=>$res->openid,'name'=>$res->name,'avatar'=>$res->user->avatar,'created_at'=>$res->created_at];

            if ($res->upper){

                $this->infos($res->upper,$task->id);

            }

            $new = array_reverse($this->info_data);

            return response()->json(['success'=>true,'data'=>$new]);

        }else{

            return view('modules.admin.data.wechat_info',['task'=>$task,'people_id'=>$people_id,'openid'=>$openid,'user_info'=>$user_info,'user_remark'=>$user_remark]);

        }

    }

    public function infos($openid,$task_id)
    {
        $res = SpreadPeopleModel::where('openid',$openid)
                    ->where('tasks_id',$task_id)
                    ->with([
                        'user'=>function($query){
                            $query->select('openid','avatar');
                    }])->first();

        $this->info_data[] = ['id'=>$res->id,'openid'=>$res->openid,'name'=>$res->name,'avatar'=>$res->user->avatar,'created_at'=>$res->created_at];

        if ($res->upper){

            $this->infos($res->upper,$task_id);

        }else{

            return true;

        }

    }

    //用户足迹
    public function onMore(Request $request,$openid)
    {
        if($request->ajax()){

            if(Auth::user()->identity === 'admin'){

                $more = SpreadPeopleModel::select('id','openid','tasks_id','name','level','people_num','read_num','read_at')->where('openid',$openid)
                    ->with([
                        'task'=>function($query){
                            $query->select('id','title');
                        }
                    ])->orderBy('created_at','DESC')->paginate(10);

            }else{
                //先找出当前用户创建的所有任务ID，任务标题保存起来
                $tasks  = TasksModel::select('id')->where('user_id',Auth::id())->get();

                $ids    = [];

                foreach ($tasks as $task){

                    $ids[] = $task->id;

                }

                //然后在people表里找到 该Openid是否存在在这些任务中

                $more =  SpreadPeopleModel::select('id','openid','tasks_id','level','people_num','read_num','read_at')->where('openid',$openid)->whereIn('tasks_id',$ids)
                    ->with([
                        'task'=>function($query){
                            $query->select('id','title');
                        }
                    ])->orderBy('created_at','DESC')->paginate(10);

            }

            return response()->json($more);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求操作！']);

        }

    }

}
