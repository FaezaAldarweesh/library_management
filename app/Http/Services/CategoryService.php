<?php

namespace App\Http\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        //قمت بتحميل العلاقة المرتبطة فيها التصنيفات نع الكتب من أحل عرض كتب كل تصنيف
        //eager load
        return Category::with('books')->get();
    }
    //===========================================================================================================================
    public function createCategory($data)
    {
        return Category::create($data);
    }
    //===========================================================================================================================
    public function updateCategory($data, Category $category)
    {
        $category->update($data);
        return $category;
    }
    //===========================================================================================================================
    public function deleteCategory(Category $category)
    {
        $category->delete();
        return true;
    }
    //===========================================================================================================================
    public function view_category($id)
    {
        $category = Category::with('books')->findOrFail($id);
        return $category;
    }
}
