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
            $recent_orders = Order::with(['user'])->latest()->limit(10)->get();

            return response()->json([
                'status' => true,
                'message' => 'Admin Summery Retrieve Successfully',
                'total_platform_revenue' => $total_platform_revenue,
                'total_commission_earned' => $total_commission_earned,
                'total_customers' => $total_customers,
                'total_vendors' => $total_vendors,
                'total_orders' => $total_orders,
                'recent_orders' => $recent_orders
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

    public function VendorStatusUpdate(Request $request, $vendorId)
    {
        try {
            $newStatus = $request->status;
            Vendor::where('id', $vendorId)->update([
                'status' => $newStatus
            ]);

            return $this->sendResponse(true, 'Vendor Status Update Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function ProductList()
    {
        try {
            $products = Product::with(['productImages', 'vendor', 'category'])->get();
            return $this->sendResponse(true, 'All Product Retrieve Successfully', $products, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 404);
        }
    }

    public function productStatusUpdate(Request $request, $productId)
    {
        try {
            Product::where('id', $productId)->update([
                'status' => $request->status
            ]);

            return $this->sendResponse(true, 'Product Status Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function AllWithdraws()
    {
        try {
            $withdraws = VendorWithdrawal::with('vendor.user')->where('status', 'pending')->get();
            $totalPendingRequest = VendorWithdrawal::with('vendor.user')->where('status', 'pending')->count();
            return response()->json([
                'success' => true,
                'data' => $withdraws,
                'totalPendingRequest' => $totalPendingRequest
            ]);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function withdrawStatusUpdate(Request $request, $withdrawId)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:500',
        ]);

        try {
            VendorWithdrawal::where('id', $withdrawId)->update([
                'status' => $request->status,
                'admin_note' => $request->admin_note
            ]);

            return $this->sendResponse(true, 'Withdraw Status Update Successfully', null, 201);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function allUsers()
    {
        try {
            $allUsers = User::query()
                        ->where('role', 'customer')
                        ->withCount('orders')
                        ->withSum('orders', 'payable_amount')
                        ->get();
            
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

    public function topVendorReport()
    {
        try {
            $vendor = Vendor::with('user')
                ->withSum('vendorEarning', 'net_amount')
                ->withCount('orderItems')
                ->orderByDesc('vendor_earning_sum_net_amount')
                ->take(5)
                ->get();

            return $this->sendResponse(true, 'Top Perform Vendor Report Retrieve Successfully', $vendor, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function topProductReport()
    {
        try {
            $product = OrderItem::with('product.vendor.user', 'product.category', 'product.productImages')
                ->selectRaw('product_id, sum(quantity) as total_quantity')
                ->selectRaw('product_id, sum(price) as total_price')
                ->groupBy('product_id')
                ->orderBy('quantity', 'desc')
                ->get();
            return $this->sendResponse(true, 'Top Products Retrieve Successfully', $product, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }
}
