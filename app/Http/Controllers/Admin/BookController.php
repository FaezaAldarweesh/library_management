<?php

namespace App\Http\Controllers\Admin;


use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Services\BookService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;

class BookController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $bookservices;
    /**
     * construct to inject Book Service and have middleware to make only admin role access to this functions
     * @param BookService $bookservices
     */
    public function __construct(BookService $bookservices)
    {
        $this->middleware(['role:Admin', 'permission:All books'])->only('index');
        $this->middleware(['role:Admin', 'permission:Add book'])->only('store');
        $this->middleware(['role:Admin', 'permission:View book'])->only('show');
        $this->middleware(['role:Admin', 'permission:Edit book'])->only('update');
        $this->middleware(['role:Admin', 'permission:Delete book'])->only('destroy');
        $this->bookservices = $bookservices;
    }
    //===========================================================================================================================
    /**
     * method to view all books
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة BookResources استخدام 
     */
    public function index(Request $request)
    {  
        try {
            $book = $this->bookservices->getAllBooks($request);

            return $this->Response(BookResources::collection($book), "All books fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view book', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to store a new book
     * @param  StoreBookRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $book = $this->bookservices->createBook($request->validated());    

            return $this->Response(new BookResources($book), "book created successfully", 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with createing book', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show book alraedy exist
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        try {
            return $this->Response(new BookResources($book), "book viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show book', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update book alraedy exist
     * @param  UpdateBookRequest $request
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $updatedBook = $this->bookservices->updateBook($request->validated(), $book);

            return $this->Response(new BookResources($updatedBook), "book updated successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with updating book', 400);
        }
    }
    //===========================================================================================================================
      /**
     * method to destroy book alraedy exist
     * @param  Book $book
     * @return /Illuminate\Http\JsonResponse
     */  
    public function destroy(Book $book)
    {
        try {   
            $this->bookservices->deleteBook($book);
            
            return $this->Response(null, "book deleted successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with deleting book', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to return all book without login
     * @return /Illuminate\Http\JsonResponse
     */  
    public function all_books(Request $request)
    {
        try {   
            $book = $this->bookservices->getAllBooks($request);

            return $this->Response(BookResources::collection($book), "All books fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with fetching book', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show book without login 
     * @param  $id_book
     * @return /Illuminate\Http\JsonResponse
     */
    public function view_book($id_book)
    {
        try {
            $book = $this->bookservices->view_book($id_book);
            return $this->Response(new BookResources($book), "book viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view book', 400);
        }
    }
}
