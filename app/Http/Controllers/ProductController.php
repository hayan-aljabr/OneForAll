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
   /* 'name',
        'price',
        'description',
        'image_url',
        'quantity',
        'category_id',
        'user_id'
* */
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

        // Storage::disk('public')->putFileAs('uploads/product_images/',$request->image_url, $image_url);



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
 // $request->image_url->move(public_path('/uploads/product_images'),$image_url);
      // $image_url = time().'.'.$request->image_url->extension();
     // $request->image_url->move(public_path('/uploads/product_images'));
     //$image_url = $request->file('image')->move(public_path('/uploads/product_images'),$request->file('image')->getClientOriginalName().".".$request->file('image')->getClientOriginalExtension());

              // $url= Storage::get('\public\product_images\{{$image_url}}');
     //$url = Pubi::url('image_url');
   /* $product = new Product($request->all());
     // return "dd";
     $product->
    // $id = optional(Auth::user())->id;
      if ($request->hasFile('img_url')) {
        $file = $request->file('img_url');
        $file_extension = $file->getClientOriginalName();
        $destination_path = public_path() . '/folder/images/';
        $filename = $file_extension;
        $request->file('image')->move($destination_path, $filename);
        $input['image'] = $filename;
       /* $path = $file->store('public/files');
        $name = $file->getClientOriginalName();*/






}                                                               /**
* if(Auth::user()->id = user->id() && name already exists )
*/
/* $input = $request->all();
$input['user_id'] = auth()->user()->id;*/







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

       /* $category_id = $request->query('category_id');
        $price_from = $request->query('price_from');
        $price_to = $request->query('price_to');

        $product = Product::query();
        if($category_id){
            $product->where('category_id', $category_id);
        }
        if($price_from){
            $product->where('price', '>=' , $price_from);
        }
        if($price_to){
            $product->where('price', '=<' , $price_to);
        }
        $result = $product->get();*/



    }
   /* public function showByUser(Request $request,$id){

        return Product::where('user_id','=',$id);

    }*/

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


      /*  if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder=$request->sortOrder;
        }
        else{
            $sortOrder='desc';
        }
        $result = $product->orderBY('price',$sortOrder);
        return response()->json([
            'data'=>$result,
        ],200);*/
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








/*
       /* if($request->hasFile('iamge_url')){
            if($product->image_url){
                $old_path=public_path().'storage/product_images/'
                        .$product->image_url;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            if($request->hasFile('image_url')){
            $image_url = 'image_url'.time().'.'.$request->image_url->extension();
            $request->image_url->move(public_path('storage/product_images'),$image_url);
            }


        }
        else{
            $image_url=$product->image_url;
        }*/
    /*    $product = $request->product_id;
        $name = $request->name;
        $price = $request->price;
        $description = $request->description;
        $quantity = $request->quantity;
        $category_id = $request->category_id;



      /*  $updated = TICKET_TRACKER::where('id', $ticket_row_id)
            ->update(
                ['assigned_to_id' => $assigned_to_id],
                ['team_id' => $team_id],
                ['resolve_date' => $resolve_date],
                ['status_id' => $status_id],
                ['description' => $description]
            );*/
      /*  $updated = Product::where('id',$product)
                ->update([
                    'name' => $name ,
                    'price' => $price ,
                    'description' =>$description,
                    'quantity'=> $quantity,
                    'category_id' => $category_id,

        ]);


        return response()->json([
            'message'=>'Product has been updated!',
            'data'=>$updated

        ],200);*/




        /*$product = Product::find($product);
       $product->update([
            $product->name = $request->name,
            $product->description = $request->description,
            $product->price = $request->price,
            $product->quantity = $request->quantity,
            $product->category_id = $request->category_id,
        ]);


        if ($request->has('image_url')) {
            $image_url = $request->file('image');
            $filename = $image_url->getClientOriginalName();
            $image_url->move(public_path('storage/product_images'), $filename);
            $product->image_url = $request->file('image_url')->getClientOriginalName();

    }


            $product->save();
            return $product;
*/
       /*
            $product->update([
                'name' => $request->name,

            ]);;
            return $product;*/


          /*  $validator = Validator::make($request->all(),[
         /*       'name' => 'nullable|string|max:60' ,
                'price' => 'nullable|int|min:1' ,
                'description' =>'nullable|string',
                'image_url'=> 'nullable|mimes:jpg,jpeg,png,doc,docx,pdf,txt,csv|max:2048',
                'quantity'=> 'nullable|int|max:60',
                'category_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message'=>'Validation fails',
                    'errors'=>$validator->errors()
                ],422);
            }



            if($request->hasFile('image_url')){
                if($product->image_url){
                    $old_path=storage_path().'app/public/profile_images/'
                            .$product->image_url;
                    if(File::exists($old_path)){
                        File::delete($old_path);
                    }
                }
                if($request->hasFile('image_url')){
                $image_url = 'image_url'.time().'.'.$request->image_url->extension();
                $request->image_url->move(storage_path('app/public/product_images'),$image_url);
                }


            }
            else{
                $image_url=$product->image_url;
            }

            $product->update([
                'name' => $request->name ,
                'price' => $request->price ,
                'description' =>$request->description,
                'image_url'=> $image_url,
                'quantity'=> $request->quantity,
                'category_id' => $request->category_id,
            ]);

            return response()->json([
                'message'=>'Profile has been updated!',

            ],200);
        }*/





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



/*\n<?php\n\nnamespace App\\Http\\Livewire;\n\nuse App\\Models\\Product;\nuse Livewire\\Component;\nuse Livewire\\WithPagination;\nuse Cart;\nuse App\\Models\\Category;\n\nclass SearchComponent extends Component\n{\n    public $sorting;\n    public $pagesize;\n    public $search;\n    public $product_cat;\n    public $product_cat_id;\n\n    public function mount()\n    {\n        $this->sorting = \"default\";\n        $this->pagesize = 12;\n        $this->fill(request()->only('search','product_cat','product_cat_id'));\n    }\n\n    public function store($product_id,$product_name,$product_price)\n    {\n        Cart::add($product_id,$product_name,1,$product_price)->associate('App\\Models\\Product');\n        session()->flash('success_message','Item added in Cart');\n        return redirect()->route('product.cart');\n    }\n\n    use WithPagination;\n    public function render()\n    {  \n        if($this->sorting=='date')   \n        {\n
    $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('created_at','DESC')->paginate($this->pagesize);  \n        }\n
        else if($this->sorting==\"price\")\n        {\n            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price','ASC')->paginate($this->pagesize); \n        }\n
      else if($this->sorting==\"price-desc\")\n        {\n            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->orderBy('regular_price','DESC')->paginate($this->pagesize); \n        }\n
        else{\n            $products = Product::where('name','like','%'.$this->search .'%')->where('category_id','like','%'.$this->product_cat_id.'%')->paginate($this->pagesize);  \n        }   \n        \n
      $categories = Category::all();\n        \n        return view('livewire.search-component',['products'=> $products,'categories'=>$categories])->layout(\"layouts.base\");\n    }\n}\n
 */
