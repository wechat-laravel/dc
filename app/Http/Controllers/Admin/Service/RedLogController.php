<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\GrantUserModel;
use App\Models\RedBagModel;
use App\Models\RedLogModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RedLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(\Input::ajax()){
            if(\Input::get('status')!= '' && \Input::get('name')!= ''){
                $open_id = GrantUserModel::where('name','like','%'.\Input::get('name').'%')->select('openid')
                    ->first();
                if(\Input::get('status') == 0){
                    return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                        ->where('open_id',$open_id->openid)
                        ->with('info')
                        ->paginate(10);
                }else{
                    return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                        ->where('open_id',$open_id->openid)
                        ->where('status',intval(\Input::get('status')))
                        ->with('info')
                        ->paginate(10);
                }

            }else if(\Input::has('status') != ''){
                if(\Input::get('status') == 0){
                    return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                        ->with('info')
                        ->paginate(10);
                }else{
                    return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                        ->where('status',intval(\Input::get('status')))
                        ->with('info')
                        ->paginate(10);
                }
            }else if(\Input::get('name')!= ''){
                $open_id = GrantUserModel::where('name','like','%'.\Input::get('name').'%')->select('openid')
                    ->first();
                return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                    ->where('open_id',$open_id->openid)
                    ->with('info')
                    ->paginate(10);
            }
            return RedLogModel::where('tasks_id', \Input::get('tasks_id'))
                ->with('info')
                ->paginate(10);
        }
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
