<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function someAction()
{
    // Your action logic

    // Log activity
    Log::channel('activity')->info('User Activity', [
        'user_id' => auth()->id(),
        'action' => 'Quiz Attempt',
        'details' => 'User attempted quiz on ' . now(),
    ]);
}

}

