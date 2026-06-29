<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Cupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends CuponController
{
    public function carts()
    {
        $carts = Cart::with(['product.productImages', 'product.vendor'])->where('user_id', Auth::user()->id)->get();
        $totalCarts = Cart::where('user_id', Auth::user()->id)->count();
        $sub_total = 0;

        foreach ($carts as $cart) {
            $cart->total_price = $cart->product->price * $cart->quantity;
            $sub_total +=  $cart->total_price;
        }

        $shippingFee = 100;
        $total = $sub_total + $shippingFee;

        $results = [
            'items' => $carts,
            'sub_total' => $sub_total,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'total_carts' => $totalCarts
        ];
        return $results;
    }


    public function cartStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required'
        ]);

        try {
            $cart = Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);

            return $this->sendResponse(true, 'Cart Add Successfully', $cart, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, 'Cart Add Failed', 500);
        }
    }

    public function cartUpdate(Request $request)
    {
        try {
            $id = $request->id;
            $request->validate([
                'quantity' => 'required'
            ]);

            Cart::where('id', $id)->update([
                'quantity' => $request->quantity
            ]);

            return $this->sendResponse(true, 'Cart Update Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function cartDelete(Request $request)
    {
        try {
            $id = $request->id;
            Cart::destroy($id);
            return $this->sendResponse(true, 'Cart Delete Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function cartFullDelete(Request $request)
    {
        try {
            $id = $request->id;
            Cart::where('user_id', $id)->delete();
            return $this->sendResponse(true, 'Full Cart Delete Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, 'Full Cart Delete Failed', 500);
        }
    }

    public function applyCoupon(Request $request)
    {

        $cartData = $this->carts();

        if ($cartData instanceof \Illuminate\Http\JsonResponse) {
            $cartData = $cartData->getData(true);
        }

        $subTotal = $cartData['sub_total'];
        $shippingFee = $cartData['shipping_fee'];

        $result = $this->couponCalculation($request->code, $subTotal);

        if ($result instanceof \Illuminate\Http\JsonResponse) {

            $result = $result->getData(true);
        }

        if (!$result) {
            return $this->sendErrorResponse(false, 'Invalid Coupon', 400);
        }

        $grandTotal = ($cartData['sub_total'] + $cartData['shipping_fee']) - $result['discount'];


        return [
            'success' => true,
            'Coupon_Code' => $request->code,
            'subTotal' => $subTotal,
            'shippingFee' => $shippingFee,
            'Discount_Amount' => round($result['discount'], 2),
            'Grand_Total' => $grandTotal,
            'message' => 'Coupon Applied!'
        ];
    }

    public function removeCoupon(Request $request)
    {
        $cartData = $this->carts();

        $data = [
            'sub_total' => $cartData['sub_total'],
            'shipping_fee' => $cartData['shipping_fee'],
            'grand_total' => $cartData['grand_total'],
            'items' => $cartData['items']
        ];
        return $this->sendResponse(true, 'Coupon Remove Successful', $data, 200);
    }
}
