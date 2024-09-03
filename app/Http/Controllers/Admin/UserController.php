<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class UserController extends Controller
{
    //trait لقولبة رسائل الاستجابة
    use ApiResponseTrait;
    protected $userservices;
    /**
     * construct to inject User Services and have middleware to make only admin role access to this functions
     * @param UserService $userservices
     */
    public function __construct(UserService $userservices)
    {
        $this->middleware(['role:Admin', 'permission:All Users'])->only('index');
        $this->middleware(['role:Admin', 'permission:Add User'])->only('store');
        $this->middleware(['role:Admin', 'permission:View User'])->only('show');
        $this->middleware(['role:Admin', 'permission:Edit User'])->only('update');
        $this->middleware(['role:Admin', 'permission:Delete User'])->only('destroy');
        $this->userservices = $userservices;
    }
    //===========================================================================================================================
    /**
     * method to view all category
     * @return /Illuminate\Http\JsonResponse
     * من أجل قولبة شكل الاستجابة المعادة UserResources استخدام 
     */
    public function index()
    {  
        try {
            $categories = $this->userservices->getAllUsers();

            return $this->Response(UserResources::collection($categories), "All Users fetched successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with fetche users', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to store a new User
     * @param  StoreUserRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $category = $this->userservices->createUser($request->validated());

            return $this->Response(new UserResources($category), "user created successfully", 201);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with creating user', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to show user alraedy exist
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        try {
            return $this->Response(new UserResources($user), "user viewed successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with show user', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to update user alraedy exist
     * @param  UpdateUserRequest $request
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $updatedUser = $this->userservices->updateCategory($request->validated(), $user);

            return $this->Response(new UserResources($updatedUser), "user updated successfully", 200);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with updating user', 400);
        }
    }
    //===========================================================================================================================
    /**
     * method to destroy user alraedy exist
     * @param  User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        try {   
            $this->userservices->deleteUser($user);
            
            return $this->Response(null, "user deleted successfully", 200);
            //catch error expception
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->customeResponse($e->getMessage(), 400);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $this->customeResponse('Something went wrong with deleting user', 400);
        }
    }

}
