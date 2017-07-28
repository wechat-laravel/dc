<?php

namespace App\Http\Controllers;


use App\Events\SendRedBagEvent;
use App\Models\EnteredModel;
use App\Models\GrantUserModel;
use App\Models\SpreadPeopleModel;
use App\Models\SpreadRecordModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;
use Monolog\Handler\IFTTTHandler;
use phpDocumentor\Reflection\DocBlock\Tags\See;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WechatController extends Controller
{
    protected $wechat;

    protected $openid = '';

    protected $mark   = '';

    protected $source = 'wechat';

    public function __construct()
    {
        $wechat = app('wechat');

        $this->wechat = $wechat;
    }


    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $this->wechat->server->setMessageHandler(function($message){

            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

        });

        return $this->wechat->server->serve();
    }


    //每个任务的首页
    public function task(Request $request,$id){
        //每次新的浏览都要清楚之前的now_id
        Session::forget('now_id');

        $task = TasksModel::where('id',intval($id))->with('ad')->first();

        $look = 0;      //是否分享的内容被好友查看了

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        Session::put('tasks_id',$task->id);

        //先检测是否有openid，有暂时保存下
        if($request->has('openid')){

            $this->openid = $request->input('openid');

            Session::put('openid',$request->input('openid'));

        }

        if ($request->has('mark')){

            $this->mark   = $request->input('mark');

            Session::put('mark',$request->input('mark'));

        }

        if ($request->has('source')){

            $this->source = $request->input('source');

            Session::put('source',$request->input('source'));

        }


        $oauth = $this->wechat->oauth;

        $js    = $this->wechat->js;

        //授权验证
        if (!Session::has('w_user')){

            return $oauth->redirect();

    	}

        $user   = Session::get('w_user');

        $record = [
            'openid'   => $user[0]['id'],
            'tasks_id' => $task->id,
            'upper'    => $this->openid,
            'ip'       => ip2long($request->ip()),
            'action'   => 'browse',
            'url'      => $request->getRequestUri(),
            'mark'     => $this->mark,
            'source'   => $this->source,
        ];

        //记录层级
        $level = SpreadRecordModel::where('tasks_id',$task->id)->where('openid',$user[0]['id'])->where('action','browse')->orderBy('created_at','asc')->first();

        if ($level){

            $record['level'] = $level->level;

        }else{
            //Redis UV : 如果没有浏览记录的话说明新的UV +1

            if ($this->openid){

                $upper = SpreadRecordModel::where('openid',$this->openid)->where('action','browse')->where('tasks_id',$task->id)->orderBy('created_at','desc')->first();

                if (!$upper)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

                //表示有上级，并且是第一次查看
                $look  = 1;

                //只统计10层，再往下面去，都是按10层
                if ($upper->level === 10){

                    $record['level'] = 10;

                }else{

                    $record['level'] = $upper->level + 1;
                }

            }else{

                $record['level'] = 1;

            }

        }

        //来源判断（微信好友或是微信群）
        if ($this->source === 'wechat'){

            if ($this->mark && $this->openid){

                //检查当前连接标识
                $upper = SpreadRecordModel::where('tasks_id',$task->id)->where('ip',0)->where('openid',$this->openid)->where('mark',$this->mark)->first();

                if (!$upper)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

                if ($upper->action === 'wechat_group'){

                    $record['source'] = 'wechat_group';

                }else{

		            if($user[0]['id'] !== $upper->openid){
		    
                        $num = SpreadRecordModel::select('id')->where('tasks_id',$task->id)->where('action','browse')->where('source','wechat')
                                                  ->where('mark',$this->mark)->whereNotIn('openid',[$upper->openid,$user[0]['id']])->groupBy('openid')->get();

                        if ($num){

                            $nums = count($num->toArray());

                            //判断是否发到群里了
                            if($nums >= 1){

                                try{

                                    $upper->update(['action'=>'wechat_group']);

                                    //同时将原来的分享到微信好友次数-1  微信群次数+1
                                    SpreadPeopleModel::where('tasks_id',$task->id)->where('openid',$upper->openid)->increment('wechat_group',1);

                                    SpreadPeopleModel::where('tasks_id',$task->id)->where('openid',$upper->openid)->decrement('wechat',1);

                                    SpreadRecordModel::where('tasks_id',$task->id)->where('action','browse')->where('mark',$this->mark)->where('source','wechat')->update(['source'=>'wechat_group']);

                                }catch (Exception $e){

                                    return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

                                }

                                //对应的，浏览记录也得跟着变一下
                                $record['source'] = 'wechat_group';

                            }

                        }
                    }
	            }
            }

        }

        //红包发送
        if ($look){

            //保证自己转发后不是自己打开看的
            if ($record['openid'] !== $record['upper']){

                if ($record['source'] === 'timeline' || $record['source'] === 'qzone'){

                    event(new SendRedBagEvent(2,$record['upper'],$task->id,2,$user[0]['original']['city'],$user[0]['original']['sex']));

                }else{

                    event(new SendRedBagEvent(1,$record['upper'],$task->id,2,$user[0]['original']['city'],$user[0]['original']['sex']));

                }

            }

        }

        try{
            //Redis source : 这里可以取record的 source 对应的+1
            $last = SpreadRecordModel::create($record);

            //记录在用户关系表里
            $people = SpreadPeopleModel::where('openid',$user[0]['id'])->where('tasks_id',$task->id)->first();

            if ($people){

                $people->read_at = time();

                $people->read_num += 1;

                $people->update();

            }else{

                $people = SpreadPeopleModel::create([

                    'name'      => $user[0]['name'],

                    'level'     => $record['level'],

                    'tasks_id'  => $task->id,

                    'read_at'   => time(),

                    'openid'    => $record['openid'],

                    'upper'     => $record['upper'],

                    'source'    => $record['source'],

                    'sex'       => $user[0]['original']['sex'],

                    'province'  => $user[0]['original']['province'],

                    'city'      => $user[0]['original']['city']

                ]);

            }

            //如果有上级，看上级是否记录的改下级，如果没有该层级的所有上级 都添加该记录
            if ($people->upper){

                //上级
                $up = SpreadPeopleModel::where('openid',$people->upper)->where('tasks_id',$task->id)->first();

                $ids = explode(',',$up->people_ids);

                //如果上级没有，就一直循环下去，直到顶级
                if(array_search($people->id,$ids) === false){

                    $this->upper($task->id,$people->upper,$people->id,$people->level);

                }

            }


            Session::put('now_id',$last->id);

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return view('task',['user'=>$user,'js'=>$js,'url'=>$request->getRequestUri(),'upper'=>$this->openid,'task'=>$task]);

    }

    public  function oauthCallback(){

        $oauth = $this->wechat->oauth;

        $user  = $oauth->user();

        $info  = $user->toArray();
	
        $exist = GrantUserModel::where('openid',$info['id'])->exists();

        if(!$exist){

            $data = [
                'openid'   => $info['id'],
                'name'     => $info['name'],
                'avatar'   => $info['avatar'],
                'email'    => $info['email'] ? $info['email'] : '',
                'sex'      => $info['original']['sex'],
                'language' => $info['original']['language'],
                'country'  => $info['original']['country'],
                'province' => $info['original']['province'],
                'city'     => $info['original']['city'],
            ];

            try{
                
                GrantUserModel::create($data);

            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>'用户信息添加失败！']);

            }

	    }

        Session::push('w_user',$info);

        if (Session::has('openid')){

            $openid = Session::get('openid');
	 
  	        $this->openid = $openid;

        }

        if (Session::has('mark')){

            $mark  = Session::get('mark');

	        $this->mark = $mark;

        }

        if (Session::has('source')){

            $source = Session::get('source');

            $this->source = $source;

        }

        $tasks_id = Session::get('tasks_id');
        
        return redirect('wechat/task/'.$tasks_id.'?openid='.$this->openid.'&mark='.$this->mark.'&source='.$this->source);

    }

    //操作记录		
    public function record(Request $request){

        $input = $request->only(['openid','action','upper','mark','task_id']);

        $action = [
            'wechat',		        //分享至微信好友
            'timeline',		        //分享至朋友圈
            'qq',		            //分享到QQ
            'qzone',		        //分享到QQ空间
        ];
	
        $user = Session::get('w_user');

        if(e($input['openid']) !== $user[0]['id']){

            return response()->json(['success'=>false,'msg'=>'用户信息已过期，请刷新页面']);

        }

        if(!in_array($input['action'],$action)){

            return response()->json(['success'=>false,'msg'=>'action值错误：'.$input['action']]);

        }
        //这里直接把该人的操作记录 在传播用户表 对应的转发方式的值+1
        $level = SpreadPeopleModel::where('openid',$user[0]['id'])->where('tasks_id',$input['task_id'])->first();

        if (!$level) return response()->json(['success'=>false,'msg'=>'非法的操作！']);

        $record = [
            'openid'   => $user[0]['id'],
            'mark'     => e($input['mark']),
            'action'   => $input['action'],
            'upper'    => e($input['upper']),
            'level'    => $level->level,
            'tasks_id' => $input['task_id'],
        ];

        try{

            SpreadRecordModel::create($record);
            //相应的 操作次数+1
            SpreadPeopleModel::where('tasks_id',$record['tasks_id'])->where('openid',$record['openid'])->increment($record['action'],1);

            if ($record['action'] === 'wechat' && $record['action'] === 'qq'){

                $action = 1;

            }else{

                $action = 2;

            }

            event(new SendRedBagEvent($action,$record['openid'],$record['tasks_id'],1,$user[0]['original']['city'],$user[0]['original']['sex']));

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>'分享等操作失败！']);

        }

        return response()->json(['success'=>true,'msg'=>'记录成功！']);

    }

    public function upper($task_id,$openid,$id,$level)
    {
        try{
		
            //上级
            $up = SpreadPeopleModel::where('openid',$openid)->where('tasks_id',$task_id)->first();

            //下级层数记录
            if ($up->level_num < ($level - $up->level)){

                $up->level_num = $level - $up->level;

            }

            $up->people_ids .= ','.$id;

            $up->people_num += 1;

            $up->update();

            //查找上级，如果找不到了就结束
            if ($up->upper){

                $this->upper($task_id,$up->upper,$id,$level);

            }else{

                return true;

            }

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

    }


    public function entered(Request $request)
    {

        if ($request->ajax()){

            //授权验证
            if (!Session::has('w_user')){

                return response()->json(['success'=>false,'msg'=>'页面已过期，请刷新后重新提交！']);

            }

            $user   = Session::get('w_user');

            $input = $request->only(['tasks_id','name','sex','mobile','remark']);

            $validator = Validator::make($input,[
                'tasks_id'  => 'required|integer',
                'name'      => 'required|max:20',
                'sex'       => 'required|integer',
                'mobile'    => 'required|max:20',
                'remark'    => 'required|max:200'
            ]);

            if ($validator->fails()){

                return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

            }

            $preg = preg_match('/^1[3|4|5|7|8]\d{9}$/', $input['mobile']);

            if (empty($preg))    return response()->json(['success'=>false,'msg'=>'手机格式有误！']);

            $input['openid'] = $user[0]['id'];

            try{

                EnteredModel::create($input);

            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>'提交失败！']);

            }

            return response()->json(['success'=>true,'msg'=>'提交成功！']);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

    //记录文章页面停留时长
    public function stayTime(Request $request)
    {

        if ($request->has('stay')){

            if (!Session::has('now_id')){

                return response()->json(['success'=>false,'msg'=>'缺少必要的参数！']);

            }

            $id = intval(Session::get('now_id'));

            try{

                //一：把当前记录的ID存进 集合里，当索引使用

                //检测当前id是否已存在集合中
                $exists_id = Redis::sismember('record_id_list',$id);

                if (!$exists_id){
                    //添加到集合
                    if(!Redis::sadd('record_id_list',$id)){

                        return response()->json(['success'=>false,'msg'=>'索引添加失败！']);

                    }

                }
                //HASH表中是否存在id, 在这里id是作为key的
                $exists = Redis::hexists("$id",'time');

                if ($exists){
                    //递增返回的是递增后的数值，在这里所以不会为0的，所以这样判断
                    if (Redis::hincrby("$id",'stay',1) && Redis::hincrby($id,'time',1)){

                        return response()->json(['success'=>true,'msg'=>'递增成功！']);

                    }else{

                        return response()->json(['success'=>false,'msg'=>'递增失败！']);

                    }

                }else{

                    Redis::hmset($id,['stay'=>1,'time'=>time()]);

                }

                return response()->json(['success'=>true,'msg'=>'记录成功！']);

            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
            }

        }else{

            return response()->json(['success'=>false,'msg'=>'缺少必要的参数！']);

        }

    }

    //定时更新停留时长的脚本
    public function upStay()
    {
        $sql  = 'UPDATE `dc_spread_record` SET `stay` = CASE `id`';
        $ids  = [];

        //过期时间标准，允许有5秒的时差
        $time = time()-5;

        //列出所有的id索引
        $list  = Redis::smembers('record_id_list');

        if (empty($list))  return response()->json(['success'=>true,'msg'=>'empty']);

        foreach ($list as $id){

            //根据ID找对应的HASH表数据
            $res = Redis::hgetall($id);

            if ($res){

                //小于这个时间，说明页面浏览已经停止了
                if ($res['time'] < $time){

                    $sql .= ' WHEN '.intval($id).' THEN '.intval($res['stay']);

                    $ids []= $id;

                }

            }else{

                continue;

            }

        }

        //如果不为空，则表示有过期的数据。
        if (!empty($ids)){

            $str  = implode($ids,',');

            $sql .= ' END WHERE `id` IN ('.$str.');';

        }else{

            return response()->json(['success'=>true,'msg'=>'nobody']);
        }

        try{

            $result = DB::update(DB::raw($sql));

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }

        //返回的值为影响的行数，如果为0表示没有修改
        if ($result){
            //修改完之后才要删除掉这些过期的
            foreach ($ids as $id){

                Redis::srem('record_id_list',$id);

                Redis::hdel($id,['stay','time']);

            }

        }

        return response()->json(['success'=>true,'msg'=>'ok']);

    }

    public function ceshi(Request $request){

        //测试用的

        $stay = 1;

        if ($request->has('stay')){

            $stay = intval($request->input('stay'));

        }

        $task = TasksModel::find(82);

        return view('test',['task'=>$task,'stay'=>$stay]);

    }

    //redis
    public function rstayTime(Request $request)
    {
        if ($request->ajax()){

            try{

                $id = intval($request->input('stay'));

                //一：把当前记录的ID存进 集合里，当索引使用

                //检测当前id是否已存在集合中
                $exists_id = Redis::sismember('record_id_list',$id);

                if (!$exists_id){
                    //添加到集合
                    if(!Redis::sadd('record_id_list',$id)){

                        return response()->json(['success'=>false,'msg'=>'索引添加失败！']);

                    }

                }
                //HASH表中是否存在id, 在这里id是作为key的
                $exists = Redis::hexists("$id",'time');

                if ($exists){
                    //递增返回的是递增后的数值，在这里所以不会为0的，所以这样判断
                    if (Redis::hincrby("$id",'stay',1) && Redis::hincrby($id,'time',1)){

                        return response()->json(['success'=>true,'msg'=>'递增成功！']);

                    }else{

                        return response()->json(['success'=>false,'msg'=>'递增失败！']);

                    }

                }else{

                    Redis::hmset($id,['stay'=>1,'time'=>time()]);

                }

                return response()->json(['success'=>true,'msg'=>'记录成功！']);


            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
            }

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }


}
