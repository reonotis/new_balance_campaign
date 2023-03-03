<?php

namespace App\Http\Controllers\Admin;

use App\Models\TryOn2023;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 */
class AdminTryOn20232Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $applyList = TryOn2023::where('delete_flag', 0)->paginate(50);
        return view('admin.tryOn2023.index', compact('applyList'));
    }

}
