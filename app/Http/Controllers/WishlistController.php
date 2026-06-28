<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends BaseController
{
    public function wishlistShow(){
        try {
            $wishlist = Wishlist::with('product.productImages')->where('user_id', Auth::user()->id)->get();
            $totalWishlist = Wishlist::where('user_id', Auth::user()->id)->count();

            return response()->json([
                'success' => true,
                'message' => 'All Wishlist Product Retrieve Successfully',
                'wishlist' => $wishlist,
                'totalWishlist' => $totalWishlist
            ]);

        } catch(\Exception $e){

            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function wishlistStore(Request $request){
        try {
            $request->validate([
                'product_id' => 'required'
            ]);

            Wishlist::create([
                'product_id' => $request->product_id,
                'user_id' => Auth::user()->id
            ]);

            return $this->sendResponse(true, 'Wishlist Add Successfully', null, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 400);
        }
    }

    public function wishlistDelete($id){
        try {
            Wishlist::destroy($id);

            return $this->sendResponse(true, 'Wishlist Delete Successfully', null, 200);
        } catch(\Exception $e){

            require $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }
}
