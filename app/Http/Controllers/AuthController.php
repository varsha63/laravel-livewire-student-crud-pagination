<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|between:2,100',
            'lastname' => 'required|string|between:2,100',
            'phone' => 'required|max:11',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|same:confirm_password|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['firstname'] =  $user->firstname;
        $success['lastname'] =  $user->lastname;
        $success['email'] =  $user->email;
        $success['phone'] =  $user->phone;

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
        
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required_without:phone|string|email',
            'phone' => 'required_without:email|numeric|max:11',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        if(Auth::attempt(
            ['phone' => request('phone'), 'password' => request('password')]) ||
        Auth::attempt(
            ['email' => request('email'), 'password' => request('password')])){
            $authUser = Auth::user(); 
            $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken; 
            $success['firstname'] =  $authUser->firstname;
            $success['lastname'] =  $authUser->firstname;
            $success['email'] =  $authUser->email;
            $success['phone'] =  $authUser->email;
            return $this->success([
                'token' => auth()->user()->createToken('API Token')->plainTextToken
            ]);
            //return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->error('Credentials not match', 401);
            
        } 
    }

    public function allCategory(Request $request){

        $category = Category::all();
        return $this->success($category, 'Category list.');
    }

/*
INSERT INTO `products` (
    `id`, `product_title`, `product_slug`, `featured_image`,
 `gallary`, `product_description`, `status`, `created_at`, 
 `updated_at`, `user_id`, `category_id`) 
 VALUES ('1', 'Lg gram laptop', 'ELEC23', '', '',
  'ksdfad fa f as f as f as f a sf a s f as f a s f a sf aaaaasfasf as f a sf a sf as f as f a fa',
   'active', '2021-05-31 12:25:32', '2021-05-31 12:25:33', '4', '1');
*/

    public function categoryProduct(Request $request,$title){
        $cate = Category::find(1)->products->with('category_title'); 
        
        
        return $this->success($cate, 'Products list.');
    }




    /*public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }*/

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
