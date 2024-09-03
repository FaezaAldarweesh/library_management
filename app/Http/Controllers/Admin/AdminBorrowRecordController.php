<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Services\BorrowService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\BorrowResources;
use App\Http\Requests\Admin\UpdateBorrowRequest;
use App\Http\Requests\Admin\StoreBorrowRecordRequest;
use App\Http\Requests\Admin\UpdateBorrowRecordRequest;


class AdminBorrowRecordController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $borrowservices;
    /**
     * construct to inject Borrow Service and have middleware to make only admin role access to this functions
     * @param BorrowService $borrowservices
     */
    public function __construct(BorrowService $borrowservices)
    {
        $this->middleware(['role:Admin', 'permission:All borrow'])->only('index');
        $this->middleware(['role:Admin', 'permission:View borrow'])->only('store');
        $this->middleware(['role:Admin', 'permission:Add borrow'])->only('show');
        $this->middleware(['role:Admin', 'permission:Edit borrow'])->only('update');
        $this->middleware(['role:Admin', 'permission:Delete borrow'])->only('destroy');
        $this->middleware(['role:Admin', 'permission:Update status borrow'])->only('editStatus');
        $this->borrowservices = $borrowservices;
    }
    //===========================================================================================================================
    /**
     * method to view all Borrow Records
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة BorrowResources استخدام 
     */
    public function index()
    {  
        try {
            $BorrowRecord = $this->borrowservices->getAllBorrowRecordsForAdmin();
            $allBorrowRecords = BorrowResources::collection($BorrowRecord);
            return $this->Response($allBorrowRecords, "All Borrow Records fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view Borrow Records', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to store a new Borrow Record
     * @param  StoreBorrowRecordRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreBorrowRecordRequest $request)
    {
        try {
            $BorrowRecord = $this->borrowservices->createBorrowRecordForAdmin($request->validated());           
            return $this->Response(new BorrowResources($BorrowRecord), "Borrow Records created successfully", 201);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        }catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with create Borrow Record', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show Borrow Record alraedy exist
     * @param  $id
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $BorrowRecord = $this->borrowservices->showBorrowRecord($id);           
            return $this->Response(new BorrowResources($BorrowRecord), "Borrow Records viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show Borrow Record', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update Borrow Record alraedy exist
     * @param  UpdateBorrowRecordRequest $request
     * @param  $id
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRecordRequest $request, $id)
    {
        try {
            $updatedborrowRecord = $this->borrowservices->updateBorrowRecordForAdmin($request->validated(), $id);
            return $this->Response(new BorrowResources($updatedborrowRecord), "Borrow Records updated successfully", 200);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        }catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with update Borrow Records', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update status on Borrow Record alraedy exist
     * @param  UpdateBorrowRequest $request
     * @param  $id
     * @return /Illuminate\Http\JsonResponse
     */
    public function editStatus(UpdateBorrowRequest $request, $id)
    {
        try {
            $updatedborrowRecord = $this->borrowservices->updateBorrowStatusForAdmin($request->validated(), $id);
            return $this->Response(new BorrowResources($updatedborrowRecord), "Borrow Records status updated successfully", 200);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        }catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with status update Borrow Records', 400);
        }
    }
    //===========================================================================================================================
      /**
     * method to destroy Borrow Record alraedy exist
     * @param  $id
     * @return /Illuminate\Http\JsonResponse
     */  
    public function destroy($id)
    {
        try {   
            $this->borrowservices->deleteBorrowRecord($id);
            return $this->Response(null, "Borrow Records deleted successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with deleting Borrow Records', 400);
        }
    }
}
