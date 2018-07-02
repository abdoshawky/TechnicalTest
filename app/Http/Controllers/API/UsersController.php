<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Notifications\Verification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Validator;
use File;
use Image;

class UsersController extends Controller
{
    public function getUsers(){
        $users = User::orderBy('created_at','desc')->select('id','name','email','verified','gender','image','phone','address')->paginate(10);
        $headers = [
            'currentPage'  => $users->currentPage(),
            'lastPage'  => $users->lastPage(),
        ];
        return response()->json($users->items())->withHeaders($headers);
    }

    public function getUser($id){
        $user = User::select('id','name','email','verified','gender','image','phone','address')->find($id);
        return response()->json($user);
    }

    public function createUser(Request $request){
        $input = $request->all();
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required',
            'gender'    => [
                'required',
                Rule::in(['male','female'])
            ]
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            $error = [
                'message'   => $validation->errors()->first()
            ];
            return response()->json($error, 400);
        }

        $token = str_random(60);
        while (User::where('api_token',$token)->count() > 0){
            $token = str_random(60);
        }
        $image = 'default_user.png';
        if($request->hasFile('image')){
            $fullName = str_random(12).date('Y-m-d').'.jpeg';
            $path = public_path('uploaded/users/');
            Image::make($request->file('image'))->save($path.$fullName);

            $image = $fullName;
        }
        $data = [
            'name'      => $input['name'],
            'email'     => $input['email'],
            'password'  => bcrypt($input['password']),
            'gender'    => $input['gender'],
            'api_token' => $token,
            'image'     => $image
        ];
        $optional = [
            'address',
            'phone'
        ];
        foreach ($optional as $option){
            if(!empty($input[$option])){
                $data[$option] = $input[$option];
            }
        }
        $user = User::create($data);
        if($user){

            session()->put('success','Please visit your email to activate your account');

            // generate verification code
            $code = str_random(60);
            while (\App\Models\Verification::where('code',$code)->count() > 0){
                $code = str_random(60);
            }
            \App\Models\Verification::create(['user_id'=>$user->id, 'code'=>$code]);

            // send verification email
            $user->notify(New Verification($user, $code));

            $user = User::select('id','name','email','verified','gender','image','phone','address')->find($user->id);
            return response()->json($user);
        }else{
            $error = [
                'message'   => 'something went wrong'
            ];
            return response()->json($error,500);
        }
    }

    public function updateUser(Request $request, $id){
        $user = User::find($id);
        $input = $request->all();
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.$id,
            'gender'    => [
                'required',
                Rule::in(['male','female'])
            ]
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            $error = [
                'message'   => $validation->errors()->first()
            ];
            return response()->json($error, 400);
        }


        $data = [
            'name'      => $input['name'],
            'email'     => $input['email'],
            'password'  => bcrypt($input['password']),
            'gender'    => $input['gender'],
        ];
        $optional = [
            'password',
            'address',
            'phone'
        ];
        foreach ($optional as $option){
            if(!empty($input[$option])){
                if($option == 'password'){
                    $data[$option] = bcrypt($input[$option]);
                }else{
                    $data[$option] = $input[$option];
                }

            }
        }

        $image = $user->imageName;
        if($request->hasFile('image')){
            // delete the user old image
            File::delete($image);
            // then upload the new image
            $fullName = str_random(12).date('Y-m-d').'.jpeg';

            $path = public_path('uploaded/users/');
            Image::make($request->file('image'))->save($path.$fullName);

            $image = $fullName;
        }
        $data['image'] = $image;
        $update = $user->update($data);
        if($update){
            $user = User::select('id','name','email','verified','gender','image','phone','address')->find($id);
            return response()->json($user);
        }else{
            $error = [
                'message'   => 'something went wrong'
            ];
            return response()->json($error,500);
        }
    }

    public function deleteUser($id){
        $user = User::find($id);
        $user->delete();
        return response()->json([], 204);
    }
}
