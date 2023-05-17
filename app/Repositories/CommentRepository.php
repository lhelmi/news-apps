<?php

namespace App\Repositories;

use App\Models\Comment;
use config\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\CreateCommentJob;

class CommentRepository extends Repository
{
    public function store($request){
        // $commentData = [
        //     'comment' => $request->comment,
        //     'user_id' => (integer)Auth::user()->id,
        //     'news_id' => (integer)$request->news_id
        // ];
        // $save = CreateCommentJob::dispatch($commentData);
    }
}
