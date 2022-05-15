<?php

namespace App\Http\Controllers\Admin;

use App\Models\TryOn;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\{DB, Log};

class AdminTryOnController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tryOns = TryOn::paginate(5);
        return view('admin.try_on.index', compact('tryOns'));
    }

}
