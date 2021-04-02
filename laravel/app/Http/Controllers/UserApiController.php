<?php

namespace App\Http\Controllers;

use Validator;
Use File;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $login;
    private $user;
    public function __construct()
    {
        $this->login = new LoginController;
        $this->user = $this->login->getAuthenticatedUser()->original['user'];
        
    }

    public function index()
    {
        //see all users
        $user = User::all()->toJson(JSON_PRETTY_PRINT);
        return response($user, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (User::where('id', $id)->exists()){
            $user = User::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($user, 200);
        } else {
            return response()->json(["message" => "user not found"], 404);
        }
    }

    public function profile()
    {   
        return response($this->user->toJson(JSON_PRETTY_PRINT), 200);
    }

    public function updateProfile(Request $request)
    {   
        $user = User::find($this->user->id);
        $validateData = Validator::make($request->all(), [
            'firstName'     => 'nullable|min:4|max:250',
            'lastName'      => '',
            'username'      => 'nullable|min:4|max:250',
            'phone'         => 'numeric',
            'email'         => 'nullable|min:4|max:250',
            'birthDate'     => '',
            'sex'           => 'nullable|in:M,F',
            'password'      => 'nullable|min:4|max:250'
        ]);
        if ($validateData->fails()){
            return response($validateData->errors(), 400);
        } else {
            $user->firstName = $request->firstName == null ? $user->firstName : $request->firstName ;
            $user->lastName = $request->lastName == null ? $user->lastName : $request->lastName ;
            $user->username = $request->username == null ? $user->username : $request->username ;
            $user->phone = $request->phone == null ? $user->phone : $request->phone ;
            $user->email = $request->email == null ? $user->email : $request->email ;
            $user->birthDate = $request->birthDate == null ? $user->birthDate : $request->birthDate ;
            $user->sex = $request->sex == null ? $user->sex : $request->sex ;
            $user->password = $request->password == null ? $user->password :  Hash::make($request->password);
            $user->save();
            return response()->json([
                "message" => "user updated"], 201);
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
        //
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
        //update data user
        if (User::where('id', $id)->exists()) {
            $validateData = Validator::make($request->all(), [
                'firstName'     => 'nullable|min:4|max:250',
                'lastName'      => '',
                'username'      => 'nullable|min:4|max:250',
                'phone'         => 'numeric',
                'email'         => 'nullable|min:4|max:250',
                'birthDate'     => '',
                'sex'           => 'nullable|in:M,F',
                'password'      => 'nullable|min:4|max:250'
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $user = User::find($id);
                $user->firstName = $request->firstName == null ? $user->firstName : $request->firstName ;
                $user->lastName = $request->lastName == null ? $user->lastName : $request->lastName ;
                $user->username = $request->username == null ? $user->username : $request->username ;
                $user->phone = $request->phone == null ? $user->phone : $request->phone ;
                $user->email = $request->email == null ? $user->email : $request->email ;
                $user->birthDate = $request->birthDate == null ? $user->birthDate : $request->birthDate ;
                $user->sex = $request->sex == null ? $user->sex : $request->sex ;
                $user->password = $request->password == null ? $user->password :  Hash::make($request->password);
                $user->save();
                return response()->json([
                    "message" => "user updated"], 201);
                }
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
        //
    }
}
