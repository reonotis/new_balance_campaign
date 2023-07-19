<?php

namespace App\Http\Controllers\Admin;

use App\Models\GolfTryOn2023;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\{DB, Log};

class AdminGolfTryOn2023Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $gto2023 = GolfTryOn2023::where('delete_flag', 0)->paginate(20);
        return view('admin.golf_try_on_2023.index', compact('gto2023'));
    }

}
