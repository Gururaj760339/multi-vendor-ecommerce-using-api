<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorEarning;
use App\Models\VendorWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class VendorController extends BaseController
{
    public function applyVendor(Request $request)
    {
        $request->validate([
            'shop_name' => 'required',
            'description' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail(Auth::user()->id);

            $user->vendor()->create([
                'shop_name' => $request->shop_name,
                'description' => $request->description,
                'slug' => Str::slug($request->shop_name) . '-' . uniqid()
            ]);

            $user->update([
                'role' => 'vendor'
            ]);

            DB::commit();

            return $this->sendResponse(true, 'Vendor application submitted successfully', null, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendErrorResponse(false, 'Vendor application submitted Failed', 500);
        }
    }

    public function vendorProfile(Request $request)
    {
        try {
            $userId = Auth::user()->id;
            $vendor = Vendor::where('user_id', $userId)->first();
            return $this->sendResponse(true, 'Vendor Data Retrive Successful', $vendor, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function vendorProfileUpdate(Request $request, $id)
    {

        $request->validate([
            'address' => 'required',
            'shop_name' => 'required',
            'description' => 'required',
            'logo_url' => 'image|mimes:jpg,png,jpeg|max:2048',
            'banner_url' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        try {
            $vendor = Vendor::findOrFail($id);

            $data = [
                'slug' => Str::slug($request->shop_name),
                'address' => $request->address,
                'shop_name' => $request->shop_name,
                'description' => $request->description
            ];

            if ($request->hasFile('logo_url')) {
                $data['logo_url'] = $request->file('logo_url')->store('images', 'public');
            }

            if ($request->hasFile('banner_url')) {
                $data['banner_url'] = $request->file('banner_url')->store('images', 'public');
            }

            $vendor->update($data);

            return $this->sendResponse(true, 'Vendor Profile Update Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function vendorDashboard()
    {
        try {
            $vendorId = Auth::user()->vendor->id;
            $total_sales = VendorEarning::where('vendor_id', $vendorId)->where('status', 'available')->sum('gross_amount');
            $net_earnings = VendorEarning::where('vendor_id', $vendorId)->where('status', 'available')->sum('net_amount');
            $pending_total_sales = VendorEarning::where('vendor_id', $vendorId)->where('status', 'pending')->sum('gross_amount');
            $pending_net_earnings = VendorEarning::where('vendor_id', $vendorId)->where('status', 'pending')->sum('net_amount');
            $total_order = VendorEarning::where('vendor_id', $vendorId)->count('id');
            $pending_withdrawals = VendorWithdrawal::where('vendor_id', $vendorId)->where('status', 'pending')->sum('amount');

            return response()->json([
                'success' => true,
                'message' => 'Vendor Dashboard Details Retrieve Successfully',
                'total_sales' => $total_sales,
                'net_earnings' => $net_earnings,
                'pending_total_sales' => $pending_total_sales,
                'pending_net_earnings' => $pending_net_earnings,
                'total_order' => $total_order,
                'pending_withdrawals' => $pending_withdrawals
            ]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function vendorEarningHistroy()
    {
        try {
            $vendorEarning = VendorEarning::where('vendor_id', Auth::user()->vendor->id)->get();
            return $this->sendResponse(true, 'Vendor Earning Histroy Retrieve Successfully', $vendorEarning, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function vendorWithdraw(Request $request)
    {
        try {
            $request->validate(
                [
                    'amount' => 'required|integer|min:10000|max:100000',
                    'payment_method' => 'required',
                    'payment_details' => 'required'
                ],
                [
                    'amount.min' => 'The minimum withdrawal amount is 10,000 TK.',
                    'amount.max' => 'The maximum withdrawal amount per transaction is 100,000 TK.',
                ]
            );

            $vendorId = Auth::user()->vendor->id;

            $totalEarning = VendorEarning::where('vendor_id', $vendorId)->where('status', 'available')->sum('net_amount');
            $vendor = VendorWithdrawal::where('vendor_id', $vendorId)->exists();
            $totalWithdraw = 0;
            if ($vendor) {
                $totalWithdraw = VendorWithdrawal::where('vendor_id', $vendorId)->whereIn('status', ['pending', 'approved'])->sum('amount');
            }

            $currentBalance = $totalEarning - $totalWithdraw;
            $request_withdraw_balance = $request->amount;

            if ($currentBalance < $request_withdraw_balance) {
                return $this->sendErrorResponse(false, 'Insufficient balance for this withdrawal request', 500);
            }

            VendorWithdrawal::create([
                'vendor_id' => $vendorId,
                'amount' => $request_withdraw_balance,
                'payment_method' => $request->payment_method,
                'payment_details' => $request->payment_details,
            ]);

            return $this->sendResponse(true, 'Withdraw Request Successfully', null, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function withdrawHistroy()
    {
        try {
            $vendorId = Auth::user()->vendor->id;
            $vendor_withdraw_history = VendorWithdrawal::where('vendor_id', $vendorId)->get();
            return $this->sendResponse(true, 'Vendor All Withdraw Histroy Retrieve Successfully', $vendor_withdraw_history, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function publicShop($slug)
    {
        try {
            $vendorData = Vendor::where('slug', $slug)->first();

            if (!$vendorData) {
                return $this->sendErrorResponse(false, 'Shop Not Found', 500);
            }

            $vendorId = $vendorData->id;

            $average_rating = round(Review::whereHas('product', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })->avg('rating'), 1);
            $total_products = Product::where('vendor_id', $vendorId)->count('id');

            return response()->json([
                'success' => true,
                'message' => 'Vendor Shop Details Retrieve Successfully',
                'vendorData' => $vendorData,
                'average_rating' => $average_rating,
                'total_products' => $total_products
            ]);
        } catch (\Exception $e) {
            require $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function getVendorProducts($slug)
    {
        try {
            $vendor = Vendor::where('slug', $slug)->first();

            if (!$vendor) {
                return $this->sendErrorResponse(false, 'Vendor not found', 404);
            }

            $vendorId = $vendor->id;

            $products = Product::where('vendor_id', $vendorId)->get();

            return $this->sendResponse(true, 'All Product Retrieve Successfully', $products, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
}
