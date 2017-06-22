<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\UserModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class IndexController extends Controller
{

    //个人资料
    public function profile()
    {

        return view('modules.admin.user.profile');

    }

    public  function postProfile(Request $request)
    {
        //图片上传
        if ($request->input('src')){

            $src = base64_decode(str_replace('data:image/png;base64,', '', $request->input('src')));

            $uploadPath = '/upload/'. Auth::id() . '/';

            $name = 'avatar.png';

            if(!is_dir(public_path().$uploadPath)) {

                $res = File::makeDirectory(public_path() . $uploadPath, $mode = 0777, $recursive = true);

            }

            if(file_put_contents(public_path().$uploadPath.$name, $src)){

                $input = ['avatar' => $uploadPath.$name];

                UserModel::where('id',Auth::id())->update($input);

                return response()->json(['success'=>true,'msg'=>'修改头像成功!']);

            }else{

                return response()->json(['success'=>false,'msg'=>'修改头像失败!']);

            }

        }

    }

    //账户安全
    public function account()
    {

    }

    //账户资产
    public function assets(Request $request)
    {
        //
    }

}
