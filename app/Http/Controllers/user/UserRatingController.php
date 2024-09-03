<?php

namespace App\Http\Controllers\user;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Services\RatingService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\RatingResources;
use App\Http\Requests\User\StoreRatingRequest;
use App\Http\Requests\User\UpdateRatingRequest;

class UserRatingController extends Controller
{   
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait; 
    /**
     * construct to inject Rating Service and have middleware to make only user role access to this functions
     * @param RatingService $ratingservices
     */
    protected $ratingservices;
    public function __construct(RatingService $ratingservices)
    {
        $this->middleware(['role:user','permission:All ratings'])->only('index');
        $this->middleware(['role:user','permission:Add rating'])->only('store');
        $this->middleware(['role:user','permission:View rating'])->only('show');
        $this->middleware(['role:user','permission:Edit rating'])->only('update');
        $this->middleware(['role:user','permission:Delete rating'])->only('destroy');
        $this->ratingservices = $ratingservices;
    }

    //========================================================================================================================
        /**
     * method to view all user's ratings
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة RatingResources استخدام 
     */
    public function index()
    {  
        try {
            $ratings = $this->ratingservices->getAllRatings();
            return $this->Response(RatingResources::collection($ratings), "All Ratings fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view all rating', 400);
        }
    }
    //========================================================================================================================
    /**
     * method to store a new rating
     * @param  StoreRatingRequest $request
     * @param  $book_id
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreRatingRequest $request,$book_id)
    {
        try {
            $rating = $this->ratingservices->createRating($request->validated(),$book_id);
            return $this->Response(new RatingResources($rating), "rating created successfully", 201);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        }catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with create rating', 400);
        }

    }
    //========================================================================================================================
    /**
     * method to show rating alraedy exist
     * @param  $id //id rating
     * @return /Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $rating = $this->ratingservices->showRating($id);
            return $this->Response(new RatingResources($rating), "rating view successfully", 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with view rating', 400);
        }
    }

    //========================================================================================================================
    /** 
     * method to update rating alraedy exist
     * @param  UpdateRatingRequest $request
     * @param  $id_rating
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateRatingRequest $request, $id_rating)
    {
        try {
            $rating = $this->ratingservices->updateRating($request->validated(),$id_rating);
            return $this->Response(new RatingResources($rating), "rating update successfully", 201);
            //catch error expception
        } catch (\Exception $e) { Log::error($e->getMessage()); return $this->customeResponse($e->getMessage(), 400);}
          catch (\Throwable $th) { Log::error($th->getMessage()); return $this->customeResponse('Something went wrong with update rating', 400);
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
            $this->ratingservices->deleteRating($id_rating);
            return $this->Response(null, "delete rating successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with delete rating', 400);
        }
    }
}
