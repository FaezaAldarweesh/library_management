<?php

namespace App\Http\Controllers;
use App\Http\Requests\loginRequest;
use App\Http\Services\Authservices;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\registerRequest;
use App\Http\Resources\registerResource;

class AuthController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $authservices;    
    /**
     * construct to inject auth services
     * @param Authservices $authservices
     */
    public function __construct(Authservices $authservices)
    {
        $this->authservices = $authservices;
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    //===========================================================================================================================
    /**
     * function to login users
     * @param loginRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request)
    {        
        try {
            $token = $this->authservices->login($request->validated());
            return $this->apiResponse(null,$token,"login has been successfully",200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with login', 400);
        }
    }
//===========================================================================================================================
    /**
     * function to register users
     * @param registerRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function register(registerRequest $request){
        try {
            $result = $this->authservices->register($request->validated());
            return $this->apiResponse(new registerResource($result['user']),$result['token']," register has been successfully",201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with register', 400);
        }
    }

//===========================================================================================================================
    /**
     * function to logout users
     * @return /Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            $this->authservices->logout();
            return $this->apiResponse(null,null,"Successfully logged out",200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with logout', 400);
        }
    }

}
