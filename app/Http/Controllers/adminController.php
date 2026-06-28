<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorEarning;
use App\Models\VendorWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class adminController extends BaseController
{
    public function adminDashboard(Request $request)
    {
        try {
            $total_platform_revenue = Payment::where('status', 'completed')->sum('amount');
            $total_commission_earned = ($total_platform_revenue / 100) * 10;
            $total_customers = User::where('role', 'customer')->count('id');
            $total_vendors = User::where('role', 'vendor')->count('id');
            $total_orders = Order::count('id');

            return response()->json([
                'status' => true,
                'message' => 'Admin Summery Retrieve Successfully',
                'total_platform_revenue' => $total_platform_revenue,
                'total_commission_earned' => $total_commission_earned,
                'total_customers' => $total_customers,
                'total_vendors' => $total_vendors,
                'total_orders' => $total_orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function vendorShow()
    {
        try {
            $vendors = Vendor::with('user')->get();
            return $this->sendResponse(true, 'All Vendors Details Retrieve Successfully', $vendors, 200);
        } catch (\Exception $e) {
            require $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function approveVendor($vendorId)
    {
        try {
            Vendor::where('id', $vendorId)->update([
                'status' => 'approved'
            ]);

            return $this->sendResponse(true, 'Vendor Approved Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, 'Vendor Approved Failed!', 500);
        }
    }

    public function suspendVendor($vendorId)
    {
        try {
            Vendor::where('id', $vendorId)->update([
                'status' => 'suspanded'
            ]);
            return $this->sendResponse(true, 'Vendor Suspande Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function pendingProductList()
    {
        try {
            $products = Product::where('status', 'pending')->get();
            return $this->sendResponse(true, 'All Pending Product Retrieve Successfully', $products, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function activeProduct($productId)
    {
        try {
            Product::where('id', $productId)->update([
                'status' => 'active'
            ]);

            return $this->sendResponse(true, 'Product Active Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function rejectProduct($productId)
    {
        try {
            Product::where('id', $productId)->update([
                'status' => 'rejected'
            ]);

            return $this->sendResponse(true, 'Product Rejected Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function pendingWithdraw()
    {
        try {
            $pending_withdraw = VendorWithdrawal::with('vendor')->where('status', 'pending')->get();
            return $this->sendResponse(true, 'All Pending Withdrawals Reterieve Successfully', $pending_withdraw, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function withdrawApproved($withdrawId)
    {
        try {
            VendorWithdrawal::where('id', $withdrawId)->update([
                'status' => 'approved'
            ]);

            return $this->sendResponse(true, 'Withdraw Approved Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function allUsers()
    {
        try {
            $allUsers = User::get();
            return $this->sendResponse(true, 'All User Retrieve Successfully', $allUsers, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function adminRevenueReport(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required'
        ]);

        try {
            $gross_sales = VendorEarning::whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->sum('gross_amount');
            $commission_amount = VendorEarning::whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->sum('commission_amount');

            return response()->json([
                'success' => true,
                'message' => 'Admin Revenue Report Retrieve Successfully',
                'gross_sales' => $gross_sales,
                'commission_amount' => $commission_amount
            ]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function topVendorReport(){
        try{
            $vendor = VendorEarning::with('vendor')->orderBy('net_amount', 'desc')->limit(1)->first();
            return $this->sendResponse(true, 'Top Perform Vendor Report Retrieve Successfully', $vendor, 200);
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function topProductReport(){
        try{
            $product = OrderItem::with('product')
                        ->selectRaw('product_id, sum(quantity) as total_quantity')
                        ->groupBy('product_id')
                        ->orderBy('quantity', 'desc')
                        ->first();
            return $product;
        }catch(\Exception $e){
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
}
