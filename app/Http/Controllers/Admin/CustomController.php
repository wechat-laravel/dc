<?php

namespace App\Http\Controllers\Admin;

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

        return view('modules.admin.task.custom');

    }

    public function store(Request $request)
    {

        $str = str_random(32);

        $input = $request->only(['title','desc','img_url','editorValue']);

        $validator = Validator::make($input,[
            'title'         => 'required|max:50',
            'desc'          => 'required|max:100',
            'img_url'       => 'required|max:200',
            'editorValue'   => 'required'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }

        try{

            $input['user_id']    = Auth::id();

            $input['mark']       = 'custom';

            $input['qrcode_url'] = '/assets/images/qrcode/'.$str.'.png';

            $ret = TasksModel::create($input);

            QrCode::format('png')->size(100)->generate('http://www.maoliduo.cn/wechat/task/'.$ret->id,public_path('assets/images/qrcode/'.$str.'.png'));


        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>'操作失败！']);

        }


        return response()->json(['success'=>true,'msg'=>'操作成功！']);

    }

}
