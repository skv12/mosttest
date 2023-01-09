<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    //
    public function index(){
        return User::all();
    }
    public function chats(Request $request){
        if($request->user()->auth()->check()){
            $user = User::find(user()->auth()->id());
            return $user->chats;  
        }
        return response()->json([
            'message' => 'Message not sent. Please, retry'], 400);
    }
}
