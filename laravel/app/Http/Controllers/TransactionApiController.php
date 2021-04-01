<?php

namespace App\Http\Controllers;

use Validator;
Use File;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //see all transaction
        $transaction = Transaction::all()->toJson(JSON_PRETTY_PRINT);
        return response($transaction, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function join($id)
    {
        $transaction = DB::table('transactions')->join('users', 'id_user', '=', 'users.id')
                    ->select('users.username')->get();
        return response($transaction, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //add transactions
        $validateData = Validator::make($request->all(), [
            'id_user'       => 'required',
            'id_product'    => 'required',
            'qty'           => 'required',
            'totalPrice'    => 'required',
        ]);
        if ($validateData->fails()) {
            return response($validateData->errors(), 400);
        } else {
            $transaction = new Transaction();
            $transaction->id_user = $request->id_user;
            $transaction->id_product = $request->id_product;
            $transaction->qty = $request->qty;
            $transaction->totalPrice = $request->totalPrice;
            $transaction->save();
            return response()->json([
                "message" => "transaction added"], 201);
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
        //get transaction by id
        if (Transaction::where('id', $id)->exists()){
            $transaction = Transaction::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($transaction, 200);
        } else {
            return response()->json(["message" => "transaction not found"], 404);
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
        //update data transaction
        if (Transaction::where('id', $id)->exists()) {
            $validateData = Validator::make($request->all(), [
                'id_user'       => 'required',
                'id_product'    => 'required',
                'qty'           => 'required',
                'totalPrice'    => 'required',
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $transaction = Transaction::find($id);
                $transaction->name = $request->name;
                $transaction->brand = $request->brand;
                $transaction->qty = $request->qty;
                $transaction->save();
                return response()->json([
                    "message" => "transaction updated"], 201);
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
