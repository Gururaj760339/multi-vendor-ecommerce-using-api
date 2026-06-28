<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    public function CustomerProduct()
    {
        try {
            // $product = Product::where('status', 'active')->get();
            $product = Product::with(['productImages', 'vendor.user'])
            ->whereHas('productImages', function ($query) {
                $query->where('is_primary', 1);
            })
            ->get();

            return $this->sendResponse(true, 'All Customer Product Retrive Successfully', $product, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function CustomerSingleProduct($slug)
    {
        try {
            $product = Product::with('vendor', 'category', 'productImages', 'reviews')->where('slug', $slug)->first();

            if (!$product) {
                return $this->sendErrorResponse(false, 'Product Not found', 404);
            }
            return $this->sendResponse(true, 'Customer Single Data Retrive Successfully', $product, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock_quantity' => 'required',
            'category_id' => 'required'
        ]);

        Gate::authorize('isVendor');

        try {
            $vendor = Vendor::where('user_id', Auth::user()->id)->first();

            $vendor->products()->create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity
            ]);

            return $this->sendResponse(true, 'Product Uploade Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 422);
        }
    }

    public function vendorProductEdit($productId){
        
        try {
            $products = Product::with('category')->where('id', $productId)->first();
            $categories = Category::whereNull('parent_id')->get();
            return response()->json([
                'success' => true,
                'message' => 'Vendor Product Retrive Successfully',
                'products' => $products,
                'categories' => $categories
            ]);

        } catch (\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }

    }

    public function productUpdate(Request $request, $id)
    {

        try {
            $request->validate([
                'category_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'stock_quantity' => 'required'
            ]);

            $product = Product::findOrFail($id);

            $product->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'stock_quantity' => $request->stock_quantity,
                'price' => $request->price,
                'slug' => Str::slug($request->name)
            ]);

            return $this->sendResponse(true, 'Product Update Successfully', $product, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function productDelete($id)
    {
        try {
            Product::destroy($id);
            return $this->sendResponse(true, 'Product Delete Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function productImageStore(Request $request)
    {

        $request->validate([
            'product_id' => 'required',
            'image_path' => 'required|mimes:jpg,png,jpeg|max:2048',
            'is_primary' => 'required|boolean'
        ]);

        DB::beginTransaction();

        try {

            $product = Product::where('id', $request->product_id)->first();

            if($request->is_primary){
                $product->productImages()->update([
                    'is_primary' => 0
                ]);
            }

            $imagePath = $request->file('image_path')->store('images', 'public');

            $productImage = $product->productImages()->create([
                'image_path' => $imagePath,
                'is_primary' => $request->is_primary
            ]);

            DB::commit();

            return $this->sendResponse(true, 'Image Upload Successfully', $productImage, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendErrorResponse(false, $e->getMessage(), 422);
        }
    }

    public function productImage(){
        try{
            $productImage = ProductImage::whereHas('product', function($query){
                $query->where('vendor_id', Auth::user()->vendor->id);
            })->with('product')->get();

            return $this->sendResponse(true, 'Vendor Product Image Retrieve Successfully', $productImage, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function productImageDelete($id){
        try {
            $productImage = ProductImage::findOrFail($id);

            Storage::disk('public')->delete($productImage->image_path);

            $productImage->delete();

            return $this->sendResponse(true, 'Product Image Delete Successfully', null, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function vendorProduct(){
        
        try {
            $products = Product::with(['category'])->where('vendor_id', Auth::user()->vendor->id)->get();
            return $this->sendResponse(true, 'Vendor All Product Retrive Successfully', $products, 200);
        } catch (\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }

    }
}
