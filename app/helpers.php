<?php

/**
* 上传图片过滤(size单位为M)
*
* @param    $object  (input File对象)
*
* @param    $int
*
* @return   mixed
*/
function screenFile($file,$size)
{

    if(!$file) return (['success'=>false,'message'=>'图片不存在 !']);

    if($file->isValid()){

        $num = $size*1024*1024;

        if ($file->getSize() > $num)    return (['success'=>false,'msg'=>'图片超出限制大小,请重新提交 !']);

        if (!in_array($file->getClientMimeType(),['image/jpeg','image/png','image/jpg'])) return (['success'=>false,'msg'=>'图片仅支持PNG,JPG,JPEG格式的图片,请重新提交 !']);

    }else{

        return (['success'=>false,'msg'=>'图片无效,请重新提交 !']);

    }

    $uploadPath = '/upload/'. \Auth::id() . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

    if(!is_dir(public_path().$uploadPath)) {

        $res = \File::makeDirectory(public_path() . $uploadPath, $mode = 0777, $recursive = true);

    }

    $extension = $file->getClientOriginalExtension();

    $name = $uploadPath . md5($file) . '.' . $extension;

    move_uploaded_file($file,public_path().$name);

    return  (['success'=>true,'path'=>$name]);

}

function wx($url){
    $i       = 0;
    $content = null;

    while($i < 5)
    {
        $i ++;

        try
        {
            $html = new \Yangqi\Htmldom\Htmldom($url);
        } catch(\Exception $e)
        {
            continue;
        }

        //文章不可用
        if($html->find('.global_error_msg',0))
        {
            break;
        }

        if($html->find('#js_content',0))
        {
            $content = $html->find('#js_content',0)->innertext;
            break;
        }
    }

    return $content;
}