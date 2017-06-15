<?php

namespace App\Http\Controllers\Admin;

use App\Models\TasksModel;
use Illuminate\Http\Request;
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
    public function index()
    {
        return view('modules.admin.task.index');
    }

    //H5创建任务
    public function create()
    {

        return view('modules.admin.task.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->only(['title','desc','img_url','page_url']);

        $validator = Validator::make($input,[
            'title'     => 'required|max:50',
            'desc'      => 'required|max:100',
            'img_url'   => 'required|max:200',
            'page_url'  => 'required|max:200'
        ]);

        if ($validator->fails()){

            return response()->json(['success'=>false,'msg'=>'表单数据有误,请检查后重新提交']);

        }

        try{

            TasksModel::create($input);

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
        //
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
