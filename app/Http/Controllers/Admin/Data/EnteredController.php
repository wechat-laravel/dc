<?php

namespace App\Http\Controllers\Admin\Data;

use App\Models\EnteredModel;
use App\Models\TasksModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EnteredController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {

        $id = intval($id);

        if (Auth::user()->identity !== 'admin'){

            $task = TasksModel::where('user_id',Auth::id())->where('id',$id)->first();

        }else{

            $task = TasksModel::find($id);

        }

        if (!$task) return response()->json(['success'=>false,'msg'=>'非法的请求！']);

        if ($request->ajax()){

            $ens = EnteredModel::where('tasks_id',$task->id)->with([

                'user'=>function($query){
                    $query->select('id','openid','name','avatar','city','province');
                },
                'people'=>function($query) use($id){
                    $query->select('id','openid')->where('tasks_id',$id);
                }

            ])->orderBy('created_at','desc')->paginate(10);

            return response()->json($ens);

        }

        return view('modules.admin.data.entered',['task_id'=>$task->id,'title'=>$task->title]);

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
        //
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
