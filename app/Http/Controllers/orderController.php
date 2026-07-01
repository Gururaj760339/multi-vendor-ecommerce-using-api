<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Cupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class orderController extends CartController
{
    public function orderStore(Request $request)
    {
        $code = $request->code;

        $CouponData = null;
        $cartData = $this->carts();

        $discount = 0;
        $couponId = null;

        if ($code) {
            $CouponData = $this->applyCoupon($request);
            $coupon = Cupon::where('code', $code)->first();
            $discount = $CouponData['Discount_Amount'] ?? 0;
            
            if($coupon){
                $couponId = $coupon->id;
            }
        }

        $totalAmount = $cartData['total'];
        $grandTotal = $totalAmount - $discount;

        DB::beginTransaction();

        try {
            
            $address = Address::where('user_id', Auth::user()->id)->where('is_default', 1)->first();

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'cupon_id' => $couponId,
                'total_amount' => $totalAmount,
                'discount_amount' => $discount,
                'payable_amount' => $grandTotal,
                'shipping_address' => json_encode($address)
            ]);

            foreach ($cartData['items'] as $cart) {
                $vendorId = $cart->product->vendor_id;
                $productQuantity = $cart->quantity;
                $productPrice = $cart->product->price;

                $orderItem = $order->orderItems()->create([
                    'product_id' => $cart->product_id,
                    'vendor_id' => $vendorId,
                    'quantity' => $productQuantity,
                    'price' => $productPrice
                ]);

                $discountRation = $discount / $totalAmount;
                $commissionRate = Vendor::where('id', $vendorId)->value('commission_rate');
                $totalPrice = $productPrice * $productQuantity;
                $itemDiscount = $totalPrice * $discountRation;
                $grossAmount = $totalPrice - $itemDiscount;
                $commissionAmount = ($grossAmount / 100) * $commissionRate;
                $netAmount = $grossAmount - $commissionAmount;

                $order->vendorEarning()->create([
                    'order_id' => $order->id,
                    'vendor_id' => $vendorId,
                    'order_item_id' => $orderItem->id,
                    'gross_amount' => $grossAmount,
                    'commission_amount' => $commissionAmount,
                    'net_amount' => $netAmount
                ]);
            }

            DB::commit();

            Cart::where('user_id', Auth::user()->id)->delete();

            return $this->sendResponse(true, 'Order Created Successfully', $order->load('orderItems'), 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function allOrders()
    {
        try {
            $orders = Order::where('user_id', Auth::user()->id)->get();
            return $this->sendResponse(true, 'All Order Retrieve Successfully', $orders, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function singleOrder($id)
    {
        try {
            $order = Order::with('orderItems.product.productImages')->where('id', $id)->get();
            return $this->sendResponse(true, 'Order Detail Retrieve Successfully', $order, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function orderCancel($id)
    {
        $order = Order::where('id', $id)->first();

        if ($order->order_status === ['shipped', 'delivered']) {
            return response()->json([
                'message' => 'This Order Cannot Cancel Because this items already' . $order->order_status
            ]);
        }

        DB::beginTransaction();

        try {
            $order->update([
                'order_status' => 'cancelled'
            ]);

            $order->orderItems()->update([
                'item_status' => 'cancelled'
            ]);

            DB::commit();

            return $this->sendResponse(true, 'Order Cancel Successfully', null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function vendorOrders()
    {
        try {
            $vendorId = Auth::user()->vendor->id;
            $order = Order::with(['user'])->whereHas('orderItems', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->withCount(['OrderItems as total_items' => function($query) use ($vendorId){
                $query->where('vendor_id', $vendorId);
            }])
            ->get();

            return response()->json([
                'success' => true,
                'message' => 'Vendor Order Retrieve Successfully',
                'data' => $order
            ], 200);

        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function vendorOrderItems($id)
    {
        try {
            $orderItems = OrderItem::with(['product.productImages', 'order.user'])->where('order_id', $id)->get();
            return $this->sendResponse(true, 'Vendor Order Retrieve Successfully', $orderItems, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function updateStatus(Request $request, $itemId)
    {
        $request->validate([
            'item_status' => 'required'
        ]);

        try {
            $orderItems = OrderItem::where('id', $itemId)
                ->where('vendor_id', Auth::user()->vendor->id)->firstOrFail();

            $orderItems->update([
                'item_status' => $request->item_status
            ]);

            return $this->sendResponse(true, 'Order Item Status Update Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function adminAllOrder()
    {
        try {
            $orders = Order::with(['user', 'payment'])
            ->withCount(['orderItems as total_items'])
            ->get();
            return $this->sendResponse(true, 'All Order Retrieve Successfully', $orders, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function adminUpdateStatus(Request $request, $orderId)
    {
        $request->validate([
            'order_status' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($orderId);

            $order->update([
                'order_status' => $request->order_status
            ]);

            $order->orderItems()->update([
                'item_status' => $request->order_status
            ]);

            if($request->order_status === 'delivered'){
                VendorEarning::where('order_id', $orderId)->update([
                    'status' => 'available'
                ]);
            }

            DB::commit();

            return $this->sendResponse(true, 'Order Status Update Successfully', null, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }
}
