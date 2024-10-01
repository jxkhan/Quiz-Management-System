<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendingStudentRequest;
use App\Services\PendingStudentService;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse; // Remove this if present


class PendingStudentController extends Controller
{
    protected $pendingStudentService;

    public function __construct(PendingStudentService $pendingStudentService)
    {
        $this->pendingStudentService = $pendingStudentService;
    }

    public function register(StorePendingStudentRequest $request)
    {
        try {
            $data = $request->all();
            $this->pendingStudentService->register($data);
            return response()->json(['message' => 'Registration submitted successfully, pending admin approval.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'There was an issue processing your registration. Please try again.'], 500);
        }
    }
    public function getAllPendingStudents(): JsonResponse
    {
        // Fetch all pending students via the service
        $pendingStudents = $this->pendingStudentService->getAllPendingStudents();

        // Return a success response using the helper
        return ResponseHelper::success($pendingStudents);
    }
   

}
