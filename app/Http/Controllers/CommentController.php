<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CommentRepository;
use App\Validates\CommentValidate;
use Illuminate\Http\Response;

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

        $save = $this->repository->store($request);
        if(!$save['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $save['message'], null);
        return parent::getRespnse(Response::HTTP_CREATED, $save['message'], null);
    }
}
