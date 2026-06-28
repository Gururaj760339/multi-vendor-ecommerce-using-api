<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReviewController extends BaseController
{

    public function singleProductReview($productId){
        try {
            $reviews = Review::where('product_id', $productId)->where('status', 'approved')->get();

            if($reviews->isEmpty()){
                return $this->sendErrorResponse(false, 'No Review Found it', 500);
            }
            return $this->sendResponse(true, 'This Product All Review Retrieve successfully', $reviews, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
    
    public function reviewStore(Request $request, $productId){
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
            'comment' => 'required',
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
