<?php

namespace App\Http\Controllers\Admin;

use App\Consts\Common;
use App\Models\CommonApply;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 */
class AdminS223Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return View
     */
    public function index(): View
    {

        $records = CommonApply::getAll(Common::APPLY_TYPE_S223);
        return view('admin.s223.index', compact('records'));
    }

}
