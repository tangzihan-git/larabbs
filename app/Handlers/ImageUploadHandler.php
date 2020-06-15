<?php

namespace App\Handlers;
use Illuminate\Support\Str;
use Image;
class ImageUploadHandler
{
	//允许上传的后缀
	protected $allow_ext = ['png','jpg','jpeg','gif'];

	/**
	*图像上传
	*@param file 上传的文件
	*@param $folader 文件上传的上级目录
	*@param $file_prefix 文件前缀
	*/
	public function save($file,$folader,$file_prefix,$max_width=false)
	{
		//文件夹拼接规则
		$folder_name = "uploads/images/".$folader."/".date("Ym/d",time());
		//文件上传绝对路径
		$upload_path = public_path()."/".$folder_name;
		//获取文件名后缀
		$ext_name = strtolower($file->getClientOriginalExtension())?:"png";//没有后缀默认png
		//拼接文件名
		$filename = $file_prefix."_".time()."_".Str::random(10).".".$ext_name;
		//当不是图片时切断操作
		if(!in_array($ext_name,$this->allow_ext)){
			session()->flash('danger','错误的图片类型');
			return false;
		}
		//上传文件
		$file->move($upload_path,$filename);
		 // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $ext_name != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];
	}
	public function reduceSize($file_path,$max_width)
	{
		//
		$image = Image::make($file_path);//实例化图片对象
		// 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
	}
}