<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\VendorEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends BaseController
{
    public function placeCodOrder(Request $request, $orderId){
        $request->validate([
            'payment_method' => 'required'
        ]);

        try {
            $order = Order::where('id', $orderId)->first();

            if($request->payment_method === 'cod'){
                Payment::create([
                    'order_id' => $orderId,
                    'payment_method' => $request->payment_method,
                    'transaction_id' => 'COD-' . strtoupper(uniqid()),
                    'amount' => $order->payable_amount,
                    'status' => 'pending'
                ]);
            }

            return $this->sendResponse(true, 'Order placed with Cash on Delivery', null, 201);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500); 
        }
    }

    public function paymentStatusUpdate(Request $request, $paymentId){
        $request->validate([
            'status' => 'required'
        ]);

        DB::beginTransaction();
        try{
            $payment = Payment::findOrFail($paymentId);

            if (!$payment) {
                return $this->sendErrorResponse(false, 'Payment record not found!', 404);
            }

            $payment->update([
                'status' => $request->status
            ]);
            
            $order = $payment->order;
            if($request->status === 'completed'){
                $order->update([
                    'order_status' => 'delivered'
                ]);

                $orderId = $order->id;

                OrderItem::where('order_id', $orderId)->update([
                    'item_status' => 'delivered'
                ]);

                VendorEarning::where('order_id', $orderId)->update([
                    'status' => 'available'
                ]);

            } else if($request->status === 'refunded'){
                $order = $payment->order()->update([
                    'order_status' => 'cancelled'
                ]);
            }

            DB::commit();

            return $this->sendResponse(true, 'Payment Status Update Successfully', null, 201);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function paymentHistroy(){
        try{
            $paymentHistroy = Payment::
                    whereHas('order', function($query){
                        $query->where('user_id', Auth::user()->id);
                    })->get();

            return $this->sendResponse(true, 'All Payment Histroy Retrieve Successfully', $paymentHistroy, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
}
