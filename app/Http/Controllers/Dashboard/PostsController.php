<?php

namespace App\Http\Controllers\Dashboard;

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->back();
    }
}
