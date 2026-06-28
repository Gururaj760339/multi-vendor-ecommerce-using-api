<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = Category::with('childrens')->get();

        if ($categorys) {
            return $this->sendResponse(true, 'Data Retrive Successfully', $categorys, 200);
        } else {
            return $this->sendErrorResponse(false, 'Data Retrive Failed!', 404);
        }
    }

    public function childCategory()
    {
        $categorys = Category::whereNotNull('parent_id')->with('childrens')->get();

        if ($categorys) {
            return $this->sendResponse(true, 'Data Retrive Successfully', $categorys, 200);
        } else {
            return $this->sendErrorResponse(false, 'Data Retrive Failed!', 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::allows('isAdmin');

        $request->validate([
            'name' => 'required'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id
        ]);

        if ($category) {
            return $this->sendResponse(true, 'Category Add Successfully', $category, 201);
        } else {
            return $this->sendErrorResponse(false, 'Category Add Failed', 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $categorie = Category::with('childrens')->where('slug', $slug)->get();
        $categories = Category::with('childrens')->get();

        if ($categorie) {
            return response()->json([
                'success' => true,
                'message' => 'All Category Retrive Successfully',
                'categorie' => $categorie,
                'categories' => $categories
            ]);
        } else {
            return $this->sendErrorResponse(false, 'Data Retrive Failed', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        Gate::allows('isAdmin');

        $request->validate([
            'name' => 'required'
        ]);

        try {
            $categories = Category::where('slug', $slug);

            $updateCategories = $categories->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'parent_id' => $request->parent_id
            ]);

            return $this->sendResponse(true, 'Category Update Successfully', $updateCategories, 200);
        } catch (\Exception $e) {
            return $this->sendErrorResponse(false, 'Category Update Failed', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        DB::beginTransaction();
        try {
            $categories = Category::where('slug', $slug)->first();

            if (is_null($categories->parent_id)) {
                $categories->childrens()->delete();
            } 
                
            $categories->delete();
            
            DB::commit();
            return $this->sendResponse(true, 'Category Delete Successfully', null, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendErrorResponse(false, 'Category Delete Failed', 404);
        }
    }
}
