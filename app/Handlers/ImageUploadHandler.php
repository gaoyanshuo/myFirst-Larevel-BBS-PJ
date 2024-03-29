<?php

namespace App\Handlers;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageUploadHandler
{

    protected $allowed_ext = ["png","jpg","gif","jpeg"];

    public function save($file, $folder, $file_prefix,$max_width = false)
    {


        // 构建存储的文件夹规则，值如：uploads/images/avatars/201709/21/
        // 文件夹切割提高查找效率。
        $folder_name = "upload/images/$folder/" . date('Ym/d', time());

        // 文件具体存储的物理路径，`public_path()` 是 `public` 文件夹的物理路径。
        $upload_path = public_path() . '/' . $folder_name;

        //获取文件的后缀名
        $extension = strtolower($file->getClientOriginalExtension()) ? :'png';

        // 拼接文件名，前缀是相关数据模型的 ID,增加辨析度.
        $file_name = $file_prefix . '_' . time() . '-' . Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if (! in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $file_name);
        if ($max_width && $extension != 'gif'){
            $this->reduceSize($upload_path . '/' . $file_name, $max_width);
        }

        return [
            'path' =>  "/$folder_name/$file_name"
        ];
    }

    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width,null,function ($constraint) {

            // 设定宽度是 $max_width，高度等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });
        $image->save();
    }

}

