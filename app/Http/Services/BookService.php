<?php

namespace App\Http\Services;

use App\Models\Book;
use Illuminate\Http\Request;


class BookService
{
    public function getAllBooks(Request $request)
    {
        // إنشاء كويري من الموديل لتتم معالجتها لاحقًا
        $query = Book::query();
    
        // تطبيق الفلاتر بناءً على المدخلات من الطلب
        //فلترة حسب التصنيف
        if (!empty($request->category_id)) {
            $query->where('category_id', '=', $request->category_id);
        }
        //فلترة حسب الكاتب
        if (!empty($request->author)) {
            $query->where('author', '=', $request->author);
        }
        //فلترة حسب الحالة
        if (!empty($request->status)) {
            $query->where('status', '=', $request->status);
        }
    
        // تنفيذ الكويري وجلب النتائج
        return $query->get();
    }
    //===========================================================================================================================
    public function createBook($data)
    {
        return Book::create($data); 
    }
    //===========================================================================================================================
    public function updateBook($data, Book $book)
    {
        $book->update($data);
        return $book;
    }
    //===========================================================================================================================
    public function deleteBook(Book $book)
    {
        $book->delete();
        return true;
    }
    //===========================================================================================================================
     public function view_book($id)
     {
        //تحميل بالعلاقة من أجل عرض كل الكتب مع تقييماتها
        $result = Book::with('ratings')->findOrFail($id);
        return $result;
     }
}
