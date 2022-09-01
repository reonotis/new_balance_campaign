<?php

namespace App\Http\Controllers\Admin;

use App\Models\KokurutsuArukuTokyo;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\{DB, Log};

class AdminArukuTokyo2022Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $kokurutsuArukuTokyo = KokurutsuArukuTokyo::where('delete_flag', 0)->paginate(20);
        return view('admin.kokurutsuArukuTokyo.index', compact('kokurutsuArukuTokyo'));
    }

}
