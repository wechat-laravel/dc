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

                $ads = AdColumnModel::select()->paginate(10);

            }else{

                $ads = AdColumnModel::where('user_id',Auth::id())->paginate(10);

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
        //
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

            $file = screenFile($request->file('litimg'),2);

            if(!$file['success'])  return $file;

            $input = $request->only(['name','title','url']);

            $validator = Validator::make($input,[
                'name'      => 'required|max:100',
                'title'     => 'required|max:10',
                'url'       => 'required|max:200',
            ]);

            if ($validator->fails()){

                return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

            }

            $input['user_id'] = Auth::id();

            $input['litimg'] = $file['path'];

            $input['mark']   = 1;

            AdColumnModel::create($input);

            return response()->json(['success'=>true,'msg'=>'创建成功！']);

        }else{

            $id = intval($request->input('id'));

            if (Auth::user()->identity === 'admin'){

                $ads = AdColumnModel::find($id);

            }else{

                $ads = AdColumnModel::where('id',$id)->where('user_id',Auth::id())->first();

            }

            if (!$ads)   return response()->json(['success'=>false,'msg'=>'非法请求！']);

            $input = $request->only(['name','title','url']);

            $validator = Validator::make($input,[
                'name'      => 'required|max:100',
                'title'     => 'required|max:10',
                'url'       => 'required|max:200',
            ]);

            if ($validator->fails()){

                return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

            }

            if ($request->hasFile('litimg')){

                $file = screenFile($request->file('litimg'),2);

                if(!$file['success'])  return $file;

                $input['litimg'] = $file['path'];

            }

            $ads->update($input);

            return response()->json(['success'=>true,'msg'=>'修改成功！']);

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
    public function edit($id)
    {
        $id = intval($id);

        if (Auth::user()->identity === 'admin'){

            $ad = AdColumnModel::find($id);

        }else{

            $ad = AdColumnModel::where('id',$id)->where('user_id',Auth::id())->first();

        }

        if ($ad){

            return response()->json(['success'=>true,'ad'=>$ad]);

        }else{

            return response()->json(['success'=>false,'msg'=>'非法的请求！']);

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
