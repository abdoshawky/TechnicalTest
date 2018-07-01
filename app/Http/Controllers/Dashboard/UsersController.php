<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Hobby;
use App\Models\User;
use App\Models\UserHobby;
use App\Notifications\Verification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use Validator;
use File;
use Image;

class UsersController extends Controller
{

    private $return;

    public function loginView(){
        if(auth()->check()){
            return redirect('home');
        }
        return view('auth.login');
    }

    public function login(Request $request){
        $input = $request->all();
        $rules = [
            'email'     => 'required',
            'password'  => 'required'
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $credentials =  [
            'email'     => $input['email'],
            'password'  => $input['password'],
        ];

        $remember = $request->has('remember');

        if(auth()->attempt($credentials, $remember)){
            return redirect()->intended('home');
        }else{
            session()->put('error','Invalid credentials');
            return redirect()->back()->withInput();
        }
    }

    public function registerView(){
        if(auth()->check()){
            return redirect('home');
        }

        $this->return['hobbies'] = Hobby::all();

        return view('auth.register', $this->return);
    }

    public function register(Request $request){
        $input = $request->all();
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|confirmed',
            'gender'    => [
                'required',
                Rule::in(['male','female'])
            ]
        ];
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $token = str_random(60);
        while (User::where('api_token',$token)->count() > 0){
            $token = str_random(60);
        }
        $data = [
            'name'      => $input['name'],
            'email'     => $input['email'],
            'password'  => bcrypt($input['password']),
            'gender'    => $input['gender'],
            'api_token' => $token
        ];
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

            if($request->has('hobbies')){
                foreach ($request->get('hobbies') as $hobby){
                    UserHobby::create(['user_id' => $user->id,'hobby_id' => $hobby]);
                }
            }

            return redirect('login');
        }else{
            return redirect()->back()->withInput();
        }
    }

    public function verifyUser($code){
        $verification = \App\Models\Verification::where('code',$code)->first();
        if(empty($verification)){
            session()->put('error','Invalid verification code');
        }else{
            User::find($verification->user_id)->update(['verified'=>1]);
            $verification->delete();
            session()->put('success','Your email has been verified');
        }
        return redirect('login');
    }

    public function getAccount(){
        $this->return['hobbies'] = Hobby::all();
        return view('account', $this->return);
    }

    public function updateAccount(Request $request){
        $user = User::find(auth()->id());
        $input = $request->all();
        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email,'.auth()->id(),
            'gender'    => [
                'required',
                Rule::in(['male','female'])
            ]
        ];
        if(!empty($input['password'])){
            $rules['password']  = 'required|confirmed';
        }
        $validation = Validator::make($input, $rules);
        if($validation->fails()){
            return redirect()->back()->withInput()->withErrors($validation);
        }

        $data = [
            'name'      => $input['name'],
            'email'     => $input['email'],
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

        // update user data
        $user->update($data);
        return redirect()->back();
    }

    public function logout(){
        auth()->logout();
        return redirect('login');
    }

    public function closeAccount(Request $request){
        if($request->has('reason')){
            auth()->user()->update(['delete_reason'=>$request->get('reason')]);
        }
        auth()->user()->delete();
        return redirect('login');
    }
}
