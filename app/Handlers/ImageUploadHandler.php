<?php

namespace App\Handlers;
use Illuminate\Support\Str;

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
	public function save($file,$folader,$file_prefix)
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

		return [
            'path' => config('app.url') . "/$folder_name/$filename"
        ];

	}
}