<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends BaseController
{
    public function canReview($slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        $productId = $product->id;

        $canReview = OrderItem::where('product_id', $productId)
                            ->where('item_status', 'delivered')
                            ->whereHas('order', function($query){
                                $query->where('user_id', Auth::user()->id);
                            })->exists();
        
        if($canReview){
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }


    public function canDeleteReview(Request $request, $slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        $productId = $product->id;

        $canDeleteReview =  Review::where('user_id', Auth::user()->id)
            ->where('product_id', $productId)
            ->where('id', $request->review_id)->exists();
        
        if($canDeleteReview){
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function singleProductReview($slug){
        try {
            $product = Product::where('slug', $slug)->firstOrFail();
            $productId = $product->id;
            $reviews = Review::with(['product.productImages', 'user'])->where('product_id', $productId)->get();

            if($reviews->isEmpty()){
                return $this->sendErrorResponse(false, 'No Review Found it', 500);
            }
            return $this->sendResponse(true, 'This Product All Review Retrieve successfully', $reviews, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
    
    public function reviewStore(Request $request, $slug){
        $product = Product::where('slug', $slug)->firstOrFail();
        $productId = $product->id;

        $isReview = Order::whereHas('payment', function($query){
            $query->where('status', 'completed');
        })
        ->whereHas('orderItems', function($query) use($productId){
            $query->where('product_id', $productId);
        })
        ->where('user_id', Auth::user()->id)->exists();

        if(!$isReview){
            return $this->sendErrorResponse(false, 'You cannot review this product', 403);
        }

        $request->validate([
            'rating' => 'required',
            'comment' => 'required'
        ]);

        try{
            Review::create([
                'user_id' => Auth::user()->id,
                'product_id' => $productId,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            return $this->sendResponse(true, 'Review Post Successfully', null, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function editReview($reviewId){
        try{
            $review = Review::where('id', $reviewId)->first();
            return $this->sendResponse(true, 'Review Retrieve Successfully', $review, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function reviewStatusUpdate(Request $request, $reviewId){
        $request->validate([
            'status' => 'required'
        ]);

        try{
            Review::where('id', $reviewId)->update([
                'status' => $request->status
            ]);

            return $this->sendResponse(true, 'Review Status Update Successfully', null, 201);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function reviewDelete($id){
        try {
            Gate::any(['isCustomer','isAdmin']);
            Review::destroy($id);
            return $this->sendResponse(true, 'Review Delete Successfully', null, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function adminPanelReview(){
        try {
            $reviews = Review::get();
            return $this->sendResponse(true, 'All Review Retrieve Successfully', $reviews, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }
}
