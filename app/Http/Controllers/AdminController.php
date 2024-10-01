<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterManagerRequest;
use App\Http\Requests\RegisterSupervisorRequest;
use App\Http\Requests\AcceptStudentRequest;
use App\Http\Requests\RejectStudentRequest;
use App\Services\AdminService;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function registerManager(RegisterManagerRequest $request)
    {
        $response = $this->adminService->registerManager($request);

        return response()->json($response, $response['status_code']);
    }

    public function registerSupervisor(RegisterSupervisorRequest $request)
    {
        $response = $this->adminService->registerSupervisor($request);

        return response()->json($response, $response['status_code']);
    }

    public function accept(AcceptStudentRequest $request)
    {
        $response = $this->adminService->acceptStudent($request->id);

        return response()->json($response, $response['status_code']);
    }

    public function reject(RejectStudentRequest $request)
    {
        $response = $this->adminService->rejectStudent($request->id);

        return response()->json($response, $response['status_code']);
    }
}
