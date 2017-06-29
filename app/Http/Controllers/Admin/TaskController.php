<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdColumnModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\CountValidator\Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{

            if ($request->ajax()){

                $res = TasksModel::select();

                if (Auth::user()->identity !== 'admin'){

                    $res->where('user_id',Auth::id());

                }

                $data  = $res->paginate(10);

                return response($data);

            }

        }catch (Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return view('modules.admin.task.index');

    }

    //H5创建任务
    public function create()
    {

        $ads = AdColumnModel::select('id','name')->where('user_id',Auth::id())->get();

        return view('modules.admin.task.create',['ads'=>$ads]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $str = str_random(32);

        $input = $request->only(['title','desc','img_url','page_url','is_ad','ad_column_id']);

        $validator = Validator::make($input,[
            'title'     => 'required|max:50',
            'desc'      => 'required|max:100',
            'img_url'   => 'required|max:300',
            'page_url'  => 'required|max:300'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }

        try{

            $input['user_id'] = Auth::id();

            $input['qrcode_url'] = '/assets/images/qrcode/'.$str.'.png';

            $ret = TasksModel::create($input);

            QrCode::format('png')->size(120)->generate('http://www.maoliduo.cn/wechat/task/'.$ret->id,public_path('assets/images/qrcode/'.$str.'.png'));


        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>'操作失败！']);

        }


        return response()->json(['success'=>true,'msg'=>'操作成功！']);
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
        $id = intval($id);

        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('id',$id)->where('user_id',Auth::id())->first();

        }

        $task = TasksModel::find($id);

        $ads = AdColumnModel::select('id','name')->where('user_id',Auth::id())->get();

        if ($task){

            return view('modules.admin.task.edit',['task'=>$task,'ads'=>$ads]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }


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
        $task = TasksModel::find(intval($id));

        if (!$task)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

        if (Auth::user()->identity !== 'admin'){

            if (Auth::id() !== $task->user_id)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }

        $input = $request->only(['title','desc','img_url','page_url','is_ad','ad_column_id']);

        $validator = Validator::make($input,[
            'title'     => 'required|max:50',
            'desc'      => 'required|max:100',
            'img_url'   => 'required|max:300',
            'page_url'  => 'required|max:300'
        ]);
        
        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }

        try{

            $task->update($input);

        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>'操作失败！']);

        }


        return response()->json(['success'=>true,'msg'=>'操作成功！']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {

        if ($request->ajax()){

            $id = intval($id);

            if (Auth::user()->identity === 'admin'){

                $task = TasksModel::find($id);

            }else{

                $task = TasksModel::where('id',$id)->where('user_id',Auth::id())->first();

            }

            if (!$task)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

            $task->delete();

            return response()->json(['success'=>true,'msg'=>'操作成功！']);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }

    }
}
