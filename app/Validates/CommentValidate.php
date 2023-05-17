<?php
namespace App\Validates;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentValidate extends Controller{

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            "comment" => ["required", "string"],
            "news_id" => ["required", "numeric", "exists:news,id"],
        ]);

        $validate = null;
        if ($validator->fails()) {
            $validate = $validator->errors();
        }
        return $validate;
    }
}
