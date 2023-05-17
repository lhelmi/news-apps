<?php

namespace App\Repositories;

use App\Models\Comment;
use config\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CommentResource;

class CommentRepository extends Repository
{
    public function store($request){
        DB::beginTransaction();
        try {
            $post = New Comment();
            $post->comment = $request->comment;
            $post->user_id = (integer)Auth::user()->id;
            $post->news_id = (integer)$request->news_id;
            $post->save();
            DB::commit();
            return parent::response(true, 'Saved', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            return parent::response(false, $th->getMessage(), null);
        }
    }
}
