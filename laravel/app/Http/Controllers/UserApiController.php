<?php

namespace App\Http\Controllers;

use Validator;
Use File;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //register user
        $validateData = Validator::make($request->all(), [
            'firstName'     => 'required|min:4|max:250',
            'lastName'      => 'required|min:4|max:250',
            'username'      => 'required|min:4|max:250',
            'phone'         => 'required',
            'email'         => 'required|min:4|max:250',
            'birthDate'     => 'required',
            'sex'           => 'required|in:M,F',
            'password'      => 'required|min:4|max:250'
        ]);
        if ($validateData->fails()) {
            return response($validateData->errors(), 400);
        } else {
            $user = new User();
            $user->firstName = $request->firstName;
            $user->lastName = $request->lastName;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->birthDate = $request->birthDate;
            $user->sex = $request->sex;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                "message" => "user created"], 201);
        }
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
                'firstName'     => 'required|min:4|max:250',
                'lastName'      => '',
                'username'      => 'required|min:4|max:250',
                'phone'         => 'required',
                'email'         => 'required|min:4|max:250',
                'birthDate'     => 'required',
                'sex'           => 'required|in:M,F',
                'password'      => 'required|min:4|max:250'
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $user = User::find($id);
                $user->firstName = $request->firstName;
                $user->lastName = $request->lastName;
                $user->username = $request->username;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->birthDate = $request->birthDate;
                $user->sex = $request->sex;
                $user->password = Hash::make($request->password);
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
