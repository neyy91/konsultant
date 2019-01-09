<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function forbiddenResponse()
    {
        if ($request->ajax()) {
            return response()->json([
                'messages' => trans('app.error.403'),
            ]);
        }
        return response()->view('errors.403');
    }
}
