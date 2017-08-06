<?php

namespace App\Http\Controllers\Admin\Supper;

use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()){

            $res   = UserModel::select('id','name','avatar','email','qq','wechat_id','password','mobile','balance','consume','identity','created_at','overdue_at')
                ->whereIn('identity',['vip','visitor']);

            if ($request->has('email')){

                $email = e($request->input('email'));

                $res->where('email',$email);

            }

            if ($request->has('identity')){

                $res->where('identity',e($request->input('identity')));

            }

            $users = $res->orderBy('created_at','desc')->paginate(10);

            return response()->json($users);

        }

        return view('modules.admin.supper.user');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function identity(Request $request)
    {
        $email = e($request->input('email'));

        if (!$email){

            return response()->json(['success'=>false,'msg'=>'因为表单重置，Email为空']);

        }

        $identity = e($request->input('identity'));

        $user = UserModel::where('email',$email)->first();

        if (!$user)  return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($identity === 'visitor' || $identity === 'vip'){

            if ($identity === 'vip'){

                if ($request->has('overdue_at')){

                    //有效时长（单位：天）
                    $time = intval($request->input('overdue_at'));
                    //一天的秒数为86400
                    $user->overdue_at = time()+$time*30*86400;

                }else{

                    return response()->json(['success'=>false,'msg'=>'非法的请求！']);

                }

            }else{

                $user->overdue_at = time();

            }

            $user->identity = $identity;

            $user->update();

            return response()->json(['success'=>true,'msg'=>'操作成功！']);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

}
