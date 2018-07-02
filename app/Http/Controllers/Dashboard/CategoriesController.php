<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{

    private $return;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->return['categories'] = Category::all();
        return view('categories.index', $this->return);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = [
            'name_ar'   => 'required',
            'name_en'   => 'required'
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = [
            'name'  => [
                'ar'    => $input['name_ar'],
                'en'    => $input['name_en']
            ]
        ];
        $add = Category::create($data);
        if($add){
            return redirect('categories');
        }else{
            return redirect()->back()->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->return['category'] = Category::find($id);
        return view('categories.edit', $this->return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $input = $request->all();
        $rules = [
            'name_ar'   => 'required',
            'name_en'   => 'required'
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }
        $data = [
            'name'  => [
                'ar'    => $input['name_ar'],
                'en'    => $input['name_en']
            ]
        ];
        $update = $category->update($data);
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
        $category = Category::find($id);
        $category->delete();
        return redirect('categories');
    }
}
