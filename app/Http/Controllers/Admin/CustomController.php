<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdColumnModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ads = AdColumnModel::select('id','name')->where('user_id',Auth::id())->get();

        return view('modules.admin.task.custom',['ads'=>$ads]);

    }

    public function store(Request $request)
    {

        if (!$request->has('id')) {


            $str = str_random(32);

            $input = $request->only(['title', 'desc', 'editorValue', 'is_ad', 'ad_column_id', 'wechat_url', 'wechat_name']);

            $validator = Validator::make($input, [
                'title' => 'required|max:50',
                'desc' => 'required|max:100',
                'editorValue' => 'required',
                'wechat_name' => 'max:100'
            ]);

            if ($validator->fails()) {

                return response()->json(['success' => false, 'msg' => '表单数据有误,请检查后重新提交']);

            }

            $file = screenFile($request->file('img_url'), 2);

            if (!$file['success']) return $file;

            $input['img_url'] = $file['path'];

            try {

                $input['user_id'] = Auth::id();

                $input['mark'] = 'custom';

                $input['qrcode_url'] = '/assets/images/qrcode/' . $str . '.png';

                $ret = TasksModel::create($input);

                QrCode::format('png')->size(120)->generate('http://www.maidamaida.com/wechat/task/' . $ret->id, public_path('assets/images/qrcode/' . $str . '.png'));


            } catch (\Exception $e) {

                return response()->json(['success' => false, 'msg' => '操作失败！']);

            }
        }else{

            $id = intval($request->input('id'));

            $task = TasksModel::find(intval($id));

            if (!$task)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

            if (Auth::user()->identity !== 'admin'){

                if (Auth::id() !== $task->user_id)  return response()->json(['success'=>false,'msg'=>'非法请求！']);

            }

            $input = $request->only(['title','desc','editorValue','is_ad','ad_column_id','wechat_url','wechat_name']);

            $validator = Validator::make($input,[
                'title'         => 'required|max:50',
                'desc'          => 'required|max:100',
                'editorValue'   => 'required',
                'wechat_name'   => 'max:100'
            ]);

            if ($validator->fails()){

                return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

            }

            if ($request->hasFile('img_url')){

                $file = screenFile($request->file('img_url'),2);

                if(!$file['success'])  return $file;

                $input['img_url'] = $file['path'];

            }

            try{

                $task->update($input);

            }catch (\Exception $e){

                return response()->json(['success'=>false,'msg'=>'操作失败！']);

            }

        }

        return response()->json(['success'=>true,'msg'=>'操作成功！']);

    }

    public function edit($id)
    {
        $id = intval($id);

        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('id',$id)->where('user_id',Auth::id())->first();

        }else{

            $task = TasksModel::find($id);

        }

        $ads = AdColumnModel::select('id','name')->where('user_id',Auth::id())->get();

        if ($task){

            return view('modules.admin.task.custom_edit',['task'=>$task,'ads'=>$ads]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法请求！']);

        }


    }


    public function update(Request $request,$id)
    {

    }

}
