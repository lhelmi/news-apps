<?php

namespace App\Http\Controllers;

use App\Providers\NewsHistory;
use Illuminate\Http\Request;
use App\Repositories\NewsRepository;
use App\Validates\NewsValidate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->validation = new NewsValidate();
        $this->repository = new NewsRepository();
    }

    private $validation;
    private $repository;

    public function showAll($offset, $limit)
    {
        $res = $this->repository->showAll($offset, $limit);
        if(!$res['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $res['message'], null);
        return parent::getRespnse(Response::HTTP_OK, $res['message'], $res);
    }

    public function show($id)
    {
        $res = $this->repository->show($id);
        if(!$res['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $res['message'], null);
        return parent::getRespnse(Response::HTTP_OK, $res['message'], $res);
    }

    public function store(Request $request)
    {
        $validation = $this->validation->store($request);
        if ($validation != null) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $validation);

        $save = $this->repository->store($request);
        if(!$save['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $save['message'], null);
        $this->newsHistory($save['data'], 'store');
        return parent::getRespnse(Response::HTTP_CREATED, $save['message'], null);
    }

    public function update($id, Request $request)
    {
        $validation = $this->validation->update($request);
        if ($validation != null) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $validation);

        $save = $this->repository->update($id, $request);
        if(!$save['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $save['message'], null);

        $this->newsHistory($id, 'updated');
        return parent::getRespnse(Response::HTTP_OK, $save['message'], null);
    }

    public function destroy($id)
    {
        $save = $this->repository->destroy($id);
        if(!$save['res']) return parent::getRespnse(Response::HTTP_BAD_REQUEST, $save['message'], null);
        $this->newsHistory($id, 'deleted');
        return parent::getRespnse(Response::HTTP_OK, $save['message'], null);
    }

    public function newsHistory($newsId, $method){
        $user = Auth::user();
        (object)$data = [
            'email' => $user->email,
            'user_id' => $user->id,
            'news_id' => $newsId,
            'news_method' => $method
        ];
        event(new NewsHistory($data));
    }
}
