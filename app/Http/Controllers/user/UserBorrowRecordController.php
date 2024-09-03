<?php

namespace App\Http\Controllers\user;

use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Services\BorrowService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\BorrowResources;
use App\Http\Requests\User\StoreBorrowRecordRequest;
use App\Http\Requests\User\UpdateBorrowRecordRequest;
use App\Http\Controllers\Controller;

class UserBorrowRecordController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $borrowservices;
    /**
     * construct to inject Borrow Service and have middleware to make only user role access to this functions
     * @param BorrowService $borrowservices
     */
    public function __construct(BorrowService $borrowservices)
    {
        $this->middleware(['role:user', 'permission:All borrow'])->only('index');
        $this->middleware(['role:user', 'permission:View borrow'])->only('store');
        $this->middleware(['role:user', 'permission:Add borrow'])->only('show');
        $this->middleware(['role:user', 'permission:Edit borrow'])->only('update');
        $this->middleware(['role:user', 'permission:Delete borrow'])->only('destroy');
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
            $BorrowRecord = $this->borrowservices->getAllBorrowRecords();
            $allBorrowRecords = BorrowResources::collection($BorrowRecord);
            return $this->Response($allBorrowRecords, "All Borrow Records fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with fetched Borrow Records', 400);
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
            $BorrowRecord = $this->borrowservices->createBorrowRecord($request->validated());           
            return $this->Response(new BorrowResources($BorrowRecord), "Borrow Records created successfully", 201);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        }catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with create Borrow Records', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show Borrow Record alraedy exist that belongs to this user
     * @param  $idborrowRecord
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($idborrowRecord)
    {
        try {
            $BorrowRecord = $this->borrowservices->showBorrowRecord($idborrowRecord);           
            return $this->Response(new BorrowResources($BorrowRecord), "Borrow Records viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show Borrow Records', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update Borrow Record alraedy exist
     * @param  UpdateBorrowRecordRequest $request
     * @param  $idborrowRecord
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRecordRequest $request, $idborrowRecord)
    {
        try {
            $updatedborrowRecord = $this->borrowservices->updateBorrowRecord($request->validated(), $idborrowRecord);
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
     * method to destroy Borrow Record alraedy exist
     * @param  $idborrowRecord
     * @return /Illuminate\Http\JsonResponse
     */  
    public function destroy($idborrowRecord)
    {
        try {   
            $this->borrowservices->deleteBorrowRecord($idborrowRecord);
            return $this->Response(null, "Borrow Records deleted successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with deleting Borrow Records', 400);
        }
    }
}
