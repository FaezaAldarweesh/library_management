<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Services\CategoryService;
use App\Http\Resources\CategoryResources;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Controllers\Controller;


class CategoryController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;

    protected $categoryservices;
    /**
     * construct to inject Category Services and have middleware to make only admin role access to this functions
     * @param CategoryService $categoryservices
     */
    public function __construct(CategoryService $categoryservices)
    {
        $this->middleware(['role:Admin', 'permission:All categories'])->only('index');
        $this->middleware(['role:Admin', 'permission:Add category'])->only('store');
        $this->middleware(['role:Admin', 'permission:View category'])->only('show');
        $this->middleware(['role:Admin', 'permission:Edit category'])->only('update');
        $this->middleware(['role:Admin', 'permission:Delete category'])->only('destroy');
        $this->categoryservices = $categoryservices;
    }
    //===========================================================================================================================
    /**
     * method to view all category
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة CategoryResources استخدام 
     */
    public function index()
    {  
        try {
            $categories = $this->categoryservices->getAllCategories();

            return $this->Response(CategoryResources::collection($categories), "All categories fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view category', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to store a new category
     * @param  StoreCategoryRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $this->categoryservices->createCategory($request->validated());

            return $this->Response(new CategoryResources($category), "Category created successfully", 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with creating category', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show category alraedy exist
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        try {
            return $this->Response(new CategoryResources($category), "Category viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show category', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update category alraedy exist
     * @param  UpdateCategoryRequest $request
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $updatedCategory = $this->categoryservices->updateCategory($request->validated(), $category);

            return $this->Response(new CategoryResources($updatedCategory), "Category updated successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with updating category', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to destroy category alraedy exist
     * @param  Category $category
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(Category $category)
    {
        try {   
            $this->categoryservices->deleteCategory($category);
            
            return $this->Response(null, "Category deleted successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with deleting category', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to view all category without login
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة CategoryResources استخدام 
     */
    public function all_categories()
    {  
        try {
            $categories = $this->categoryservices->getAllCategories();

            return $this->Response(CategoryResources::collection($categories), "All categories fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view categories', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show category alraedy exist without login
     * @param  $id_category
     * @return /Illuminate\Http\JsonResponse
     */
    public function view_category($id_category)
    {
        try {
            $category = $this->categoryservices->view_category($id_category);

            return $this->Response(new CategoryResources($category), "Category viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show category', 400);
        }
    }
}
