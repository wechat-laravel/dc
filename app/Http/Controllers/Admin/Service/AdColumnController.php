<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\AdColumnModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()){

            if (Auth::user()->identity === 'admin'){

                $ads = AdColumnModel::select()->orderBy('created_at','DESC')->paginate(10);

            }else{

                $ads = AdColumnModel::where('user_id',Auth::id())->orderBy('created_at','DESC')->paginate(10);

            }

            return response($ads);

        }

        return view('modules.admin.service.ad_column');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('modules.admin.service.ad_create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$request->has('id')){

            //留言模板创建
            if (!$request->has('share')) {

                $file = screenFile($request->file('litimg'), 2);

                if (!$file['success']) return $file;

                $input = $request->only(['name', 'title', 'url']);

                $validator = Validator::make($input, [
                    'name' => 'required|max:100',
                    'title' => 'required|max:10',
                    'url' => 'required|max:200',
                ]);

                if ($validator->fails()) {

                    return response()->json(['success' => false, 'msg' => '表单数据有误,请检查后重新提交']);

                }

                $input['user_id'] = Auth::id();

                $input['litimg'] = $file['path'];

                $input['mark'] = 1;

                AdColumnModel::create($input);

                return response()->json(['success' => true, 'msg' => '创建成功！']);

            } else {
            //自定义模板

                $qrcode = screenFile($request->file('qrcode'), 2);

                if (!$qrcode['success']) return $qrcode;

                $litimg = screenFile($request->file('litimg'), 2);

                if (!$litimg['success']) return $litimg;

                $input = $request->only(['name','share', 'title', 'label','mobile','chat_url','one_t','one_d','one_d_url','two_t','two_d','two_d_url','three_t','three_d','three_d_url']);

                $validator = Validator::make($input, [
                    'name'        => 'required|max:100',
                    'share'       => 'required|max:20',
                    'title'       => 'required|max:20',
                    'label'       => 'required|max:20',
                    'mobile'      => 'required|max:30',
                    'chat_url'    => 'required|max:300',
                    'one_t'       => 'required|max:20',
                    'one_d'       => 'required|max:100',
                    'one_d_url'   => 'max:300',
                    'two_t'       => 'required|max:20',
                    'two_d'       => 'required|max:100',
                    'two_d_url'   => 'max:300',
                    'three_t'     => 'required|max:20',
                    'three_d'     => 'required|max:100',
                    'three_d_url' => 'max:300',
                ]);


                if ($validator->fails()) {

                    return response()->json(['success' => false, 'msg' =>$validator->errors()->all()]);

                }
                $input['user_id'] = Auth::id();

                $input['qrcode']  = $qrcode['path'];

                $input['litimg']  = $litimg['path'];

                $input['mark']    = 2;

                AdColumnModel::create($input);

                return response()->json(['success'=>true,'msg'=>'创建成功！']);

            }

        }else{
        //编辑修改

            $id = intval($request->input('id'));

            if (Auth::user()->identity === 'admin'){

                $ads = AdColumnModel::find($id);

            }else{

                $ads = AdColumnModel::where('id',$id)->where('user_id',Auth::id())->first();

            }

            if (!$ads)   return response()->json(['success'=>false,'msg'=>'非法请求！']);

            //区分留言模板还是自定义模板
            if (!$request->has('share')){

                //留言模板编辑
                $input = $request->only(['name','title','url']);

                $validator = Validator::make($input,[
                    'name'      => 'required|max:100',
                    'title'     => 'required|max:10',
                    'url'       => 'required|max:200',
                ]);

                if ($validator->fails()){

                    return response()->json(['success'=>false,'msg'=>$validator->errors()->all()]);

                }

                if ($request->hasFile('litimg')){

                    $file = screenFile($request->file('litimg'),2);

                    if(!$file['success'])  return $file;

                    $input['litimg'] = $file['path'];

                }

                $ads->update($input);


                return response()->json(['success'=>true,'msg'=>'修改成功！']);

            }else{
                //自定义模板的编辑

                $input = $request->only(['name','share', 'title', 'label','mobile','chat_url','one_t','one_d','one_d_url','two_t','two_d','two_d_url','three_t','three_d','three_d_url']);

                $validator = Validator::make($input, [
                    'name'        => 'required|max:100',
                    'share'       => 'required|max:20',
                    'title'       => 'required|max:20',
                    'label'       => 'required|max:20',
                    'mobile'      => 'required|max:30',
                    'chat_url'    => 'required|max:300',
                    'one_t'       => 'required|max:20',
                    'one_d'       => 'required|max:100',
                    'one_d_url'   => 'max:300',
                    'two_t'       => 'required|max:20',
                    'two_d'       => 'required|max:100',
                    'two_d_url'   => 'max:300',
                    'three_t'     => 'required|max:20',
                    'three_d'     => 'required|max:100',
                    'three_d_url' => 'max:300',
                ]);


                if ($validator->fails()) {

                    return response()->json(['success' => false, 'msg' =>$validator->errors()->all()]);

                }

                if ($request->hasFile('litimg')){

                    $litimg = screenFile($request->file('litimg'),2);

                    if(!$litimg['success'])  return $litimg;

                    $input['litimg'] = $litimg['path'];

                }

                if ($request->hasFile('qrcode')){

                    $qrcode = screenFile($request->file('qrcode'),2);

                    if(!$qrcode['success'])  return $qrcode;

                    $input['qrcode'] = $qrcode['path'];

                }

                $ads->update($input);

                return response()->json(['success'=>true,'msg'=>'修改成功！']);

            }

        }

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
    public function edit(Request $request,$id)
    {

        $id = intval($id);

        if (Auth::user()->identity === 'admin'){

            $ad = AdColumnModel::find($id);

        }else{

            $ad = AdColumnModel::where('id',$id)->where('user_id',Auth::id())->first();

        }

        if (!$ad){

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        }
        //留言模板的编辑 是直接ajax加载过去的
        if ($request->ajax()){

            return response()->json(['success'=>true,'ad'=>$ad]);

        }else{
        //自定义模板需要跳转

            return view('modules.admin.service.ad_edit',['ad'=>$ad]);

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
