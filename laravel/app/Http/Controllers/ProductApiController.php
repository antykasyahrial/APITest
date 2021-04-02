<?php

namespace App\Http\Controllers;

use Validator;
Use File;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //see all products
        $product = Product::all()->toJson(JSON_PRETTY_PRINT);
        return response($product, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //add product
         $validateData = Validator::make($request->all(), [
             'name'          => 'required|min:4|max:250',
             'brand'         => 'required|min:4|max:250',
             'qty'           => 'required',
             'price'         => 'required',
             'description'   => 'required'
         ]);
         if ($validateData->fails()) {
             return response($validateData->errors(), 400);
         } else {
             $product = new Product();
             $product->name = $request->name;
             $product->brand = $request->brand;
             $product->qty = $request->qty;
             $product->price = $request->price;
             $product->description = $request->description;
             $product->save();
             return response()->json([
                 "message" => "product added"], 201);
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
        if (Product::where('id', $id)->exists()){
            $product = Product::find($id)->toJson(JSON_PRETTY_PRINT);
            return response($product, 200);
        } else {
            return response()->json(["message" => "product not found"], 404);
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
        //update data product
        if (Product::where('id', $id)->exists()) {
            $validateData = Validator::make($request->all(), [
                'name'          => 'nullable|min:4|max:250',
                'brand'         => 'nullable|min:4|max:250',
                'qty'           => 'numeric',
                'price'         => 'numeric',
                'description'   => 'text'
            ]);
            if ($validateData->fails()){
                return response($validateData->errors(), 400);
            } else {
                $product = Product::find($id);
                $product->name =  $request->name == null ? $product->name : $request->name ;
                $product->brand = $request->brand == null ? $product->brand : $request->brand ;
                $product->qty = $request->qty == null ? $product->qty : $request->qty ;
                $product->price = $request->price == null ? $product->price : $request->price ;
                $product->description = $request->description == null ? $product->description : $request->description ;
                $product->save();
                return response()->json([
                    "message" => "product updated"], 201);
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
