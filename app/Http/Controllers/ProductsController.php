<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\carbon;

use App\Models\Product;
use App\Models\Category;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('deleted_at', null)->get();
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.form', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'=>'required|max:20',
                'category_id' => 'required',
                'price' => 'required|numeric',
                'image'=> 'required|mimes:jpg,jpeg,png'
            ],[
                'name.required'=>'Name field is required',
                'name.max' => 'Name cannot be more than 20 charecters',
                'price.required' => 'Price field is required',
                'price.numeric' => 'Price must be a Number',
                'category_id.required' => 'Category field is required',
                'image.required' => 'Image field is required',
                'image.mime' => 'Invalid file type',
            ]);
      
            $inputs = $request->all();
            $image_name = $this->fileUpload($request->file('image'), 'image', '/image');
            $product = new Product();
            $product->name = $inputs['name'];
            $product->category_id = $inputs['category_id'];
            $product->image = $image_name;
            $product->price = $inputs['price'];
            $product->save();
        
            return redirect()->route('products.index');
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $product = Product::find($id);
        return view('products.form', ['categories' => $categories, 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try {
            $request->validate([
                'name'=>'required|max:20',
                'category_id' => 'required',
                'price' => 'required|numeric'
            ],[
                'name.required'=>'Name field is required',
                'name.max' => 'Name cannot be more than 20 charecters',
                'price.required' => 'Price field is required',
                'price.numeric' => 'Price must be a Number',
                'category_id.required' => 'Category field is required',
            ]);
    
            $inputs = $request->all();
            $product = [];
            $product['name'] = $inputs['name'];
            $product['category_id'] = $inputs['category_id'];
            $product['price'] = $inputs['price'];
            if($request->file('image')) {
                $image_name = $this->fileUpload($request->file('image'), 'image', '/image');
                $product['image'] = $image_name;
            }
            
            Product::where('id', $id)->update($product);
    
            return redirect()->route('products.index');
        } catch (\Throwable $th) {
            throw $th;
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //dd($id);
        $product = Product::find($id);
        if(!$product) {
            // include flash message
            return redirect()->route('products.index');
        }
        Product::where('id',$id)->update(['deleted_at' => Carbon::now()]);

        return redirect()->route('products.index');
    }

    public static function fileUpload($requestFile, $fileName, $path)
    {
        $fileName = date('Ymdhis') . '-' . $fileName . '.' . $requestFile->getClientOriginalExtension();
        \Storage::disk('public')->put($path . '/' . $fileName, file_get_contents($requestFile->getRealPath()), 'public');
        return $fileName;
    }
}


