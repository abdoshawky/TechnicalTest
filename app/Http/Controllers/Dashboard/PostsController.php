<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Comment;
use App\Models\Post;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{

    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'category_id'   => 'required',
            'title'         => 'required',
            'content'       => 'required'
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $data = [
            'category_id'   => $input['category_id'],
            'user_id'       => auth()->id(),
            'title'         => $input['title'],
            'content'       => $input['content']
        ];
        $add = Post::create($data);
        if($add){
            return redirect()->back();
        }else{
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $input = $request->all();
        $rules = [
            'category_id'   => 'required',
            'title'         => 'required',
            'content'       => 'required'
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withErrors($validation)->withInput();
        }
        $data = [
            'category_id'   => $input['category_id'],
            'title'         => $input['title'],
            'content'       => $input['content']
        ];
        $update = $post->update($data);
        if($update){
            return redirect()->back();
        }else{
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->back();
    }

    public function storeComment(Request $request, $id){
        if($request->has('comment')){
            $data = [
                'user_id'   => auth()->id(),
                'post_id'   => $id,
                'content'   => $request->get('comment')
            ];
            Comment::create($data);
        }
        return redirect()->back();
    }

    public function destroyComment($postId, $commentId){
        $comment = Comment::find($commentId);
        $comment->delete();
        return redirect()->back();
    }
}
