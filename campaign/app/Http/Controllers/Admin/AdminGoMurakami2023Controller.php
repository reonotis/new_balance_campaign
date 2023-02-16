<?php

namespace App\Http\Controllers\Admin;

use App\Models\GoMurakami;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\{DB, Log};

class AdminGoMurakami2023Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $applyList = GoMurakami::where('delete_flag', 0)->paginate(20);
        return view('admin.goMurakami.index', compact('applyList'));
    }

}
