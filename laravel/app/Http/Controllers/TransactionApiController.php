<?php

namespace App\Http\Controllers;

use Validator;
Use File;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\LoginController;

class TransactionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
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
        $transaction = DB::table('transactions')->join('products', 'id_product', '=', 'products.id')
                    ->select('products.price')->get();
        return response($transaction, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function idShow(){
        $transaction = DB::table('transactions')->select('*')
                    ->where('id_user', '=', $this->user->id)->get();
        return response($transaction, 200);
    }

    public function store(Request $request)
    {
        //add transactions
        $validateData = Validator::make($request->all(), [
            'id_product'    => 'required|numeric',
            'qty'           => 'required|numeric',
        ]);
        if ($validateData->fails()) {
            return response($validateData->errors(), 400);
        } else {
            $product = Product::find($request->id_product);
            if (!$product){
                return response()->json([
                    "message" => "product unavailable"], 500);
            }
            if($request->qty > $product->qty){
                return response()->json([
                    "message" => "stock unavailable"], 500);
            }  
            $totalPrice = $request->qty * $product->price;
            $transaction = new Transaction();
            $transaction->id_user = $this->user->id;
            $transaction->id_product = $request->id_product;
            $transaction->qty = $request->qty;
            $transaction->totalPrice = $totalPrice;
            $transaction->save();

            $product->qty = $product->qty - $request->qty;
            $product->save();
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
