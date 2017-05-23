<?php

namespace App\Http\Controllers;


use App\Models\GrantUserModel;
use App\Models\SpreadRecordModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery\CountValidator\Exception;
use Monolog\Handler\IFTTTHandler;
use phpDocumentor\Reflection\DocBlock\Tags\See;

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

    public function addMenu()
    {
        try{

            $buttons = [
                [
                    "type" => "click",
                    "name" => "乐其意",
                    "key"  => "V1001_LE71"
                ],
                [
                    "name"       => "菜单",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "首页",
                            "url"  => "http://dc.le71.cn/"
                        ],
                    ],
                ],
            ];

            $menu = $this->wechat->menu;

            $menu->add($buttons);

            $menus = $menu->all();

        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return response($menus);

    }

    public function test(Request $request){

	    //Session::flush();
	    //Session::forget('now_id');
	    Session::forget('stay');
	    //return 1;
        
        //上来先检测是否有openid，有暂时保存下
        if($request->has('openid')){

            $this->openid = $request->input('openid');

            Session::push('openid',$request->input('openid'));

        }

        if ($request->has('mark')){

            $this->mark   = $request->input('mark');

            Session::push('mark',$request->input('mark'));

        }

        if ($request->has('source')){

            $this->source = $request->input('source');

            Session::push('source',$request->input('source'));

        }


        $oauth = $this->wechat->oauth;

        $js    = $this->wechat->js;

        //授权验证
        if (!Session::has('w_user')){

            return $oauth->redirect();

    	}

        $user = Session::get('w_user');

        $record = [
            'openid' => $user[0]['id'],
            'upper'  => $this->openid,
            'ip'     => ip2long($request->ip()),
            'action' => 'browse',
            'url'    => $request->getRequestUri(),
            'mark'   => $this->mark,
            'source' => $this->source,
        ];

        if ($this->source === 'wechat'){

            if ($this->mark && $this->openid){

                //检查当前连接标识
                $upper = SpreadRecordModel::where('ip',0)->where('openid',$this->openid)->where('mark',$this->mark)->first();

                if ($upper->action === 'wechat_group'){

                    $record['source'] = 'wechat_group';

                }else{
		            if($user[0]['id'] !== $upper->openid){
			
		    
                        $num = SpreadRecordModel::select('id')->where('action','browse')->where('source','wechat')
                                                  ->where('mark',$this->mark)->whereNotIn('openid',[$upper->openid,$user[0]['id']])->groupBy('openid')->get();

                        if ($num){

                            $nums = count($num->toArray());

                            //判断是否发到群里了
                            if($nums >= 1){

                                try{

                                    $upper->update(['action'=>'wechat_group']);

                                    SpreadRecordModel::where('action','browse')->where('mark',$this->mark)->where('source','wechat')->update(['source'=>'wechat_group']);

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

        try{

            $last = SpreadRecordModel::create($record);

            Session::put('now_id',$last->id);

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return view('test',['user'=>$user,'js'=>$js,'url'=>$request->getRequestUri(),'upper'=>$this->openid]);

    }

    public function text(){

        //$js = $this->wechat->js;

        //return view('test',['js'=>$js]);

        return view('text');

    }

    public  function oauth(){

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
	 
  	        $this->openid = $openid[0];

        }

        if (Session::has('mark')){

            $mark  = Session::get('mark');

	        $this->mark = $mark[0];

        }

        if (Session::has('source')){

            $source = Session::get('source');

            $this->source = $source[0];

        }
        
        return redirect('wechat/test?openid='.$this->openid.'&mark='.$this->mark.'&source='.$this->source);

    }

    //操作记录		
    public function record(Request $request){

        $input = $request->only(['openid','action','upper','mark']);

        $action = [
            'wechat',		        //分享至微信好友
            'esc_wechat', 	        //取消分享给好友
            'timeline',		        //分享至朋友圈
            'esc_timeline',	        //取消分享朋友圈
            'qq',		            //分享到QQ
            'esc_qq',		        //取消分享QQ
            'qzone',		        //分享到QQ空间
            'esc_qzone'		        //取消分享QQ空间
        ];
	
        $user = Session::get('w_user');

        if(e($input['openid']) !== $user[0]['id']){

            return response()->json(['success'=>false,'msg'=>'用户信息已过期，请刷新页面']);

        }

        if(!in_array($input['action'],$action)){

            return response()->json(['success'=>false,'msg'=>'action值错误：'.$input['action']]);

        }

        $record = [
            'openid' => $user[0]['id'],
            'mark'   => e($input['mark']),
            'action' => $input['action'],
            'upper'  => e($input['upper']),
        ];

        try{

            SpreadRecordModel::create($record);

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return response()->json(['success'=>true,'msg'=>'记录成功！']);

    }

    public function stayTime(Request $request)
    {

        if ($request->has('stay')){

            if (!Session::has('now_id')){

                return response()->json(['success'=>false,'msg'=>'缺少必要的参数！']);

            }

            if (Session::has('stay')){

                $stay = Session::get('stay');
		
                $stay = $stay+1;

                Session::put('stay',$stay);

            }else{

                $stay = 1;

                Session::put('stay',$stay);

            }

            $now_id = Session::get('now_id');


            try{

                SpreadRecordModel::where('id',$now_id)->update(['stay'=>$stay]);

            }catch (Exception $e){

                return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

            }

            return response()->json(['success'=>true,'msg'=>['s'=>$stay,'id'=>$now_id]]);

        }else{

            return response()->json(['success'=>false,'msg'=>'缺少必要的参数！']);

        }

    }

}
