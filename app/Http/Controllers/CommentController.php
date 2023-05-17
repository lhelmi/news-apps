<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCommentJob;
use Illuminate\Http\Request;
use App\Repositories\CommentRepository;
use App\Validates\CommentValidate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->validation = new CommentValidate();
        $this->repository = new CommentRepository();
    }

    private $validation;
    private $repository;

    public function store(Request $request)
    {
        $validation = $this->validation->store($request);
        if ($validation != null) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $validation);
        $commentData = [
            'comment' => $request->comment,
            'user_id' => (integer)Auth::user()->id,
            'news_id' => (integer)$request->news_id
        ];
        CreateCommentJob::dispatch($commentData);
        return parent::getRespnse(Response::HTTP_PROCESSING, 'Processing', null);
    }
}
