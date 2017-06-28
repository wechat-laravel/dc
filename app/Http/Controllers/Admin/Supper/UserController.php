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

            $users = UserModel::select('id','name','avatar','email','qq','wechat_id','password','mobile','balance','consume','identity','created_at')
                ->whereIn('identity',['vip','visitor'])->orderBy('created_at','desc')->paginate(10);

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

        $identity = e($request->input('identity'));

        $user = UserModel::where('email',$email)->first();

        if (!$user)  return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($identity === 'visitor' || $identity === 'vip'){

            $user->identity = $identity;

            $user->update();

            return response()->json(['success'=>true,'msg'=>'操作成功！']);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }

    }

}
