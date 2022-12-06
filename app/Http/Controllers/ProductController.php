<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::with(['reviews'])->paginate(16);
    }
    public function indexMobile(){
        return Product::all();
    }
    public function searchBYme(Request $request){
        $product = Product::where('user_id',$request->user_id);
        return $product->with('user')->get();
    }
   public function homepageindex(){
    $product = Product::with(['reviews']);
    return $product->get();
   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:60' ,
            'price' => 'required|int|min:1' ,
            'description' =>'required|string',
            'image_url'=> 'required|mimes:jpg,jpeg,png,doc,docx,pdf,txt,csv|max:2048',
            'quantity'=> 'required|int|max:60',
            'category_id' => 'required',
    ]);
        if ($validator->fails()) {
            return response()->json(['message'=>'Validation errors', 'error' => $validator->messages()],422);
        }
        $user_id  =  (Auth::user())->id;


            $image_url = 'image_url'.time().'.'.$request->image_url->extension();
            $request->image_url->move(public_path('uploads/product_images'),$image_url);

        



     $url = storage_path('public\product_images\\' . $image_url);

      $product =  Product::create([
            'name' => $request->name ,
            'price' => $request->price ,
            'description' =>$request->description,
            'image_url'=> $image_url,
            'quantity'=> $request->quantity,
            'category_id' => $request->category_id,
            'user_id'=>$user_id
        ]);
        $product->load('user:id,name,email','category:id,name');
        $product->save();

          //  $product->save();
            return response()->json([
                'message'=>'Product Added!',
                'data'=>$product,
                'image_url'=>$url
            ],200);





}                                                               







    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getProductImage()
    {
        return $id->image_url;
    }
    public function getProduct(Request $request,$id){
        $product = Product::with(['reviews','user','category']);
        $product->where('id', $id);


        return $product->get();
    }

    public function productinfo(Product $product)

    {
     //   $name = auth()->user();


        return new ProductResource($product) ;


    }

    public function show($name)
    {
     //   $name = auth()->user();

        $product = Product::where("name","like","%".$name."%"  )->get();
        return $product;

    }
    public function filter(Request $request){
        $product = Product::with(['user','category']);
        if($request->name){
            $product->where('name','like','%'.$request->name.'%');
        }
        if($request->category){
            $product->where('category_id',$request->category);
        }
        if($request->price_from){
            $product->where('price','>=',$request->price_from);
        }
        if($request->price_to){
            $product->where('price','<=',$request->price_to);
        }
       /* if($request->sortBy && in_array($request->sortBy,['id','price'])){
            $sortBy=$request->sortBy;
        }
        else{
            $sortBy='id';
        }*/
        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder=$request->sortOrder;
        }
        else{
            $sortOrder='desc';
        }
        $result = $product->orderBY('price',$sortOrder)->get();
        return response()->json([
            'data'=>$result,
        ],200);

     



    }
  

    public function showByUser(Request $request)
{
    $product= Product::where('user_id', auth()->user()->id)->get();
    return $product;

}


    public function showByCategory(Request $request){
        //$product = Product::where('category_id', $category_id)->get();
        $validator = Validator::make($request->all(),[
            'category' => 'required',
    ]);
        if ($validator->fails()) {
            return response()->json(['message'=>'Validation errors', 'error' => $validator->messages()],422);
        }
        $product = Product::with(['category']);
        if($request->category){
            $product->where('category_id',$request->category);
        }
        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder=$request->sortOrder;
        }
        else{
            $sortOrder='desc';
        }
        $result = $product->orderBY('price',$sortOrder)->get();
        return response()->json([
            'data'=>$result,
        ],200);


   
        return $product;


}
    public function scopeFilter($id)
    {
        if (request('price_from')) {
            $id->where('price', '>', request('price_from'));
        }
        if (request('price_to')) {
            $id->where('price', '<', request('price_to'));
        }

        return $id;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        if($request->hasFile('image_url')){
            if($product->image_url){
                $old_path=public_path().'/storage/product_images/'
                        .$product->image_url;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            if($request->hasFile('image_url')){
            $image_url = 'image_url'.time().'.'.$request->image_url->extension();
            $request->image_url->move(public_path('/storage/product_images'),$image_url);
            }

        }
        else{
            $image_url=$product->image_url;
        }
        if($request->name){
            $name = $request->name;
        }
        else{
            $name = $product->name;
        }
        if($request->price){
            $price = $request->price;
        }
        else{
            $price = $product->price;
        }
        if($request->description){
            $description = $request->description;
        }
        else{
            $description = $product->description;
        }
        if($request->quantity){
            $quantity = $request->quantity;
        }
        else{
            $quantity = $product->quantity;
        }
        if($request->category_id){
            $category_id = $request->category_id;
        }
        else{
            $category_id = $product->category_id;
        }

        if($user->id == $product->user_id){

            $product->update([
                'name'=> $name,
                'price'=> $price,
                'description'=> $description,
                'image_url'=>$image_url,
                'quantity'=> $quantity,
                'category_id'=> $category_id,
            ]);
            $product->save();
            return $product;
        }
        else{
            return response()->json([
                'message'=>'Forrbiedn'
            ],403);
        }
   

    }
    public function updatefromMoblie(Request $request, Product $product)
    {
        $user = Auth::user();

        if($request->hasFile('image_url')){
            if($product->image_url){
                $old_path=public_path().'/storage/product_images/'
                        .$product->image_url;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            if($request->hasFile('image_url')){
            $image_url = 'image_url'.time().'.'.$request->image_url->extension();
            $request->image_url->move(public_path('/storage/product_images'),$image_url);
            }

        }
        else{
            $image_url=$product->image_url;
        }
        if($user->id == $product->user_id){
            $request->validate([
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
            ]);

            $product->update([
                'name'=> $request->name,
                'price'=> $request->price,
                'description'=> $request->description,
                'image_url'=>$image_url,
                'quantity'=> $request->quantity,
                'category_id'=> $request->category_id,
            ]);
            $product->save();
            return $product;
        }
        else{
            return response()->json([
                'message'=>'Forrbiedn'
            ],403);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(Auth::id() == $product->user_id){
            $product->delete();
       }
       else{
        return 'This product is not yours to delete';
       }
    }
}



