<?php
namespace App\Validates;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewsValidate extends Controller{

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            "title" => ["required", "string", "max:100"],
            "description" => ["required", "string", "max:100"],
            "image" => ["required", "image", "mimes:jpg,png,jpeg", "max:5120"]
        ]);

        $validate = null;
        if ($validator->fails()) {
            $validate = $validator->errors();
        }
        return $validate;
    }

    public function update($request)
    {
        $rules = [
            "title" => ["required", "string", "max:100"],
            "description" => ["required", "string", "max:100"],
        ];
        if($request->image !== null){
            $rules['image'] = ["required", "image", "mimes:jpg,png,jpeg", "max:5120"];
        }

        $validator = Validator::make($request->all(), $rules);

        $validate = null;
        if ($validator->fails()) {
            $validate = $validator->errors();
        }
        return $validate;
    }
}
