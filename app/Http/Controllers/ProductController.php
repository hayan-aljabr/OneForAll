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

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
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
       $request->image_url->move(public_path('/uploads/product_images'),$image_url);
      // $image_url = time().'.'.$request->image_url->extension();
     // $request->image_url->move(public_path('/uploads/product_images'));
     //$image_url = $request->file('image')->move(public_path('/uploads/product_images'),$request->file('image')->getClientOriginalName().".".$request->file('image')->getClientOriginalExtension());


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

          //  $product->save();
            return response()->json([
                'message'=>'Product Added!',
                'data'=>$product
            ],200);
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

    public function productinfo(Product $product)
    {
     //   $name = auth()->user();


        return new ProductResource($product) ;


    }

    public function show($name)
    {
     //   $name = auth()->user();

        return Product::where("name","like","%".$name."%"  )->get();

    }
   /* public function showByUser(Request $request,$id){

        return Product::where('user_id','=',$id);

    }*/

    public function showByUser(Request $request)
{
    $product= Product::where('user_id', auth()->user()->id)->get();
    return $product;

}


    public function showByCategory($category_id){
        return Product::where("category_id","like","%".$category_id."%"  )->get();
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
        if(Auth::id() == $product->user_id){
             $product->update($request->all());
             return $product;
        }

        else{
             return 'This product does not belong to you';        }



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
