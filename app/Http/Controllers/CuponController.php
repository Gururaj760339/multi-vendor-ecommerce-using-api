<?php

namespace App\Http\Controllers;

use App\Models\Cupon;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuponController extends BaseController
{
    public function cupons(){
        try {
            $vendorId = Auth::user()->vendor->id;
            $cupons = Cupon::where('vendor_id', $vendorId)->get();

            return $this->sendResponse(true, 'Vendor All Cupons Retrive Successfully', $cupons, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function singleCupons($id){
        try {
            $cupons = Cupon::findOrFail($id);

            return $this->sendResponse(true, 'Vendor All Cupons Retrive Successfully', $cupons, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function cuponStore(Request $request){
        try {

            $request->validate([
                'code' => 'required',
                'type' => 'required',
                'expiry_date' => 'required|date|after:today',
                'discount_value' => 'required'
            ]);

            $vendor = Vendor::where('user_id', Auth::user()->id)->first();

            Cupon::create([
                'vendor_id' => $vendor->id,
                'code' => $request->code,
                'type' => $request->type,
                'expiry_date' => $request->expiry_date,
                'discount_value' => $request->discount_value
            ]);

            return $this->sendResponse(true, 'Cupon Created Successfully', null, 200);

        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function cuponUpdate(Request $request, $id){
        try {
            $request->validate([
                'code' => 'required',
                'discount_value' => 'required',
                'type' => 'required',
                'expiry_date' => 'required|date|after:today'
            ]);

            Cupon::where('id', $id)->update([
                'code' => $request->code,
                'discount_value' => $request->discount_value,
                'type' => $request->type,
                'expiry_date' => $request->expiry_date
            ]);

            return $this->sendResponse(true, 'Cupon Updated Successfully', null, 200);

        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }


    public function cuponDelete($id){
        try {
            Cupon::destroy($id);

            return $this->sendResponse(true, 'Cupon Delete Successfully', null, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function validateCupon($code){
        $cupons = Cupon::where('code', $code)->first();

        if(!$cupons) {
            return $this->sendErrorResponse(false, 'Invalid Coupon', 400);
        }

        if(Carbon::parse($cupons->expiry_date)->lt(now())){
            return $this->sendErrorResponse(false, 'Coupon expired', 404);
        }

        return $this->sendResponse(true, 'Coupon applied!', $cupons, 200);
    }

    public function cuponList(){
        try {
            $cupons = Cupon::get();

            return $this->sendResponse(true, 'All Coupon Retrive Successfully', $cupons, 200);
        } catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 400);
        }

    }

    public function couponCalculation($code, $subTotal){
        $coupon = Cupon::where('code', $code)->first();

        if(!$coupon){
            return null;
        }

        if(Carbon::parse($coupon->expiry_date)->lt(now())){
            return null;
        }

        $discount = 0;

        if($coupon->type === 'percent'){
            $discount = ($subTotal / 100) * $coupon->discount_value;
        } else if($coupon->type === 'fixed'){
            $discount = $coupon->discount_value;
        }

        return [
            'success' => true,
            'message' => 'Coupon Calculation Successfully',
            'Coupon Code' => $coupon->code,
            'discount' => $discount
        ];
    
    }
}
