<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Services\RatingService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\RatingResources;
use Illuminate\Http\Request;

class AdminRatingController extends Controller
{   
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait; 
    /**
     * construct to inject Rating Service and have middleware to make only admin role access to this functions
     * @param RatingService $ratingservices
     */
    protected $ratingservices;
    public function __construct(RatingService $ratingservices)
    {
        $this->middleware(['role:Admin','permission:All ratings'])->only('index');
        $this->middleware(['role:Admin','permission:Delete rating'])->only('destroy');
        $this->ratingservices = $ratingservices;
    }

    //========================================================================================================================
        /**
     * method to view all user ratings
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة RatingResources استخدام 
     */
    public function index(Request $request)
    {  
        try {
            $ratings = $this->ratingservices->getAllRatingsForAdmin($request);
            return $this->Response(RatingResources::collection($ratings), "All Ratings fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse($th->getMessage(), 400);
        }
    }
    //========================================================================================================================
    /**
     * method to destroy rating alraedy exist
     * @param  $id_rating
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy($id_rating)
    {
        try {
            $this->ratingservices->deleteRatingForAdmin($id_rating);
            return $this->Response(null, "delete rating successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with delete rating', 400);
        }
    }
}
