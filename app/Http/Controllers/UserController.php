<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends BaseController
{
    public function profile()
    {
        try {
            $user = User::where('id', Auth::user()->id)->first();

            return $this->sendResponse(true, 'Data Retrieve Successfully', $user, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }

    public function editProfile($id)
    {
        try {
            $user = User::where('id', $id)->first();

            return $this->sendResponse(true, 'Data Retrieve Successfully', $user, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, $e->getMessage(), 500);
        }
    }


    public function updateProfile(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'avatar' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $user = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('images', 'public');

            $data['avatar'] = $path;
        }

        $user->update($data);

        return $this->sendResponse(true, 'Profile Update Successfully', $user, 200);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'old_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ]);

        $currentUser = User::where('email', $request->email)->first();

        if (Hash::check($request->old_password, $currentUser->password)) {
            $currentUser->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password Change Successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password Change Failed'
            ], 500);
        }
    }

    public function getAddresses(Request $request)
    {
        $address = Address::where('user_id', Auth::user()->id)->get();

        return $this->sendResponse(true, 'User Address Retrive Successfully', $address, 200);
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_line1' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();

            if ($request->is_default) {
                $user->addresses()->update([
                    'is_default' => 0
                ]);
            }

            $address = $user->addresses()->create([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'is_default' => $request->is_default ? 1 : 0
            ]);

            DB::commit();

            return $this->sendResponse(true, 'Data Inserted Successfully', $address, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Data Inserted Failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showEditAddresses(Request $request, $addressId)
    {
        $address = Address::where('id', $addressId)->get();

        return $this->sendResponse(true, 'User Edit Address Retrive Successfully', $address, 200);
    }

    public function updateAddress(Request $request, $addressId)
    {
        $request->validate([
            'address_line1' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'phone' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail(Auth::user()->id);
            $address = $user->addresses()->findOrFail($addressId);

            $isDefault = $request->is_default ? 1 : 0;

            if ($isDefault) {
                $user->addresses()->update([
                    'is_default' => 0
                ]);
            }

            $address->update([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'is_default' => $isDefault
            ]);

            DB::commit();

            return $this->sendResponse(true, 'User Address Updated Successfully', $address, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse(false, 'User Address Updated Failed', 500);
        }
    }


    public function destroyAddress($id)
    {
        $address = Address::destroy($id);

        if ($address) {
            return $this->sendResponse(true, 'Address Delete Successfully', null, 200);
        } else {
            return $this->sendErrorResponse(false, 'Address Delete Failed', null, 404);
        }
    }
}
