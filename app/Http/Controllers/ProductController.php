<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                    'title' => ['required','min:3','max:255'],
                ]
            );

            $data = $request->all();
            if (!empty($data['image'])) {
                $image_name = $this->uploadImage($data['image'], $request->title);
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $data['image_url'] = 'http://localhost/Api_Vue/storage/app/public/products/' . $image_name;
                }else{
                    $data['image_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/app/public/products/' . $image_name;
                }
                $data['image'] = $image_name;
            }
            //            $data['created_by'] = auth()->user()->id;



            $product =Product::create($data);
        }catch (QueryException $e){
            DB::rollBack();
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
        DB::commit();
        return response()->json(['status'=>'success','message'=>'Created Successfully !']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
                $product = Product::findOrFail($id);

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);
            $data = $request->all();
            if (!empty($data['image'])) {
                $this->unlink($product->image);
                $image_name = $this->uploadImage($data['image'], $request->title);
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $data['image_url'] = 'http://localhost/Api_Vue/storage/app/public/products/' . $image_name;
                }else{
                    $data['image_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/storage/app/public/products/' . $image_name;
                }
                $data['image'] = $image_name;
            }
            $product->update($data);
        }catch (QueryException $e){
            DB::rollBack();
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
        DB::commit();
        return response()->json(['status'=>'success','message'=>'Updated Successfully !']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
         DB::beginTransaction();
        try{
            $product = Product::findOrFail($id);
            $product->delete();
        }catch (QueryException $e){
           DB::rollBack();
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
        DB::commit();
        return response()->json(['status'=>'success','message'=>'Deleted Successfully !']);
    }

    private function uploadImage($filee, $name)
    {
        $file = explode(",", $filee);
        $file_type1 = str_replace("data:image/", "",$file[0]);
        $file_type = str_replace(";base64", "",$file_type1);
        $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $file_name = $timestamp .'-'.$name. '.' . $file_type;
        $pathToUpload = storage_path().'/app/public/products/';
        $base64_str = substr($filee, strpos($filee, ",")+1);
        $image = base64_decode($base64_str);
        Image::make($image)->resize(634,792)->save($pathToUpload.$file_name);
        return $file_name;
    }
    private function unlink($file)
    {
        $pathToUpload = storage_path().'/app/public/products/';
        if ($file != '' && file_exists($pathToUpload. $file)) {
            @unlink($pathToUpload. $file);
        }
    }
}
