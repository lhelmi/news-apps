<?php

namespace App\Repositories;

use App\Models\News;
use config\Constant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsCommentResource;

class NewsRepository extends Repository
{
    public function showAll($offset, $limit){
        try {
            $res = NewsResource::collection(News::with([
                'user' => function($query) {
                    $query->select('id', 'name', 'email');
                }
            ])->skip($offset)->take($limit)->get());
            return parent::response(true, "list news", $res);
        } catch (\Throwable $th) {
            return parent::response(false, $th->getMessage(), null);
        }
    }

    public function show($id){
        try {
            $data = NewsCommentResource::collection(News::with([
                'user' => function($query) {
                    $query->select('id', 'name', 'email');
                },
                'comment'
            ])->where('id', $id)->get());
            return parent::response(true, "detail news", $data);
        } catch (\Throwable $th) {
            return parent::response(false, $th->getMessage(), null);
        }
    }

    public function store($request){
        DB::beginTransaction();
        try {
            $image = str_replace(' ', '-', $request->title) . '-' . time() . '.' . $request->image->extension();
            $post = New News();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = (integer)Auth::user()->id;
            $post->image = $image;

            $post->save();
            DB::commit();
            $this->moveImage($image, Constant::IMG_PATH_NEWS, $request->image);
            return parent::response(true, 'Saved', $post->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            return parent::response(false, $th->getMessage(), null);
        }
    }

    public function update($id, $request){
        DB::beginTransaction();
        try {
            $post = News::find($id);
            $post->title = $request->title;
            $post->description = $request->description;
            $post->user_id = (integer)Auth::user()->id;

            if($request->image !== null){
                $image = str_replace(' ', '-', $request->title) . '-' . time() . '.' . $request->image->extension();
                $post->image = $image;
            }
            $post->save();
            DB::commit();

            if($request->image !== null){
                $this->deleteImage($post->image, Constant::IMG_PATH_NEWS);
                $this->moveImage($image, Constant::IMG_PATH_NEWS, $request->image);
            }

            return parent::response(true, 'updated', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            return parent::response(false, $th->getMessage(), null);
        }
    }

    public function destroy($id){
        DB::beginTransaction();
        try {
            $post = News::find($id)->first();
            $image = $post->image;
            $this->deleteImage($image, Constant::IMG_PATH_NEWS);
            $post->delete();
            DB::commit();
            return parent::response(true, 'deleted', null);
        } catch (\Throwable $th) {
            DB::rollBack();
            return parent::response(false, $th->getMessage(), null);
        }
    }

    private function deleteImage($imageName, $path){
        if (file_exists(public_path($path.'/'.$imageName))){
            $deleted = unlink(public_path($path.'/'.$imageName));
            if($deleted){
                return true;
            }
        }
        return false;
    }

    private function moveImage($imageName, $path, $file){
        $file->move(public_path($path), $imageName);
    }
}
