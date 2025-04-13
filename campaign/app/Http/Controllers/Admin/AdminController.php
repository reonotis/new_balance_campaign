<?php

namespace App\Http\Controllers\Admin;

use App\Models\FormSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     * @return View
     */
    public function index(): View
    {
        $form_settings = FormSetting::orderBy('form_no', 'desc')
            ->paginate(10);

        return view('admin.index', [
            'form_settings' => $form_settings,
        ]);
    }

    /**
     *
     */
    public function formCreate(): View
    {
        return view('admin.create', []);
    }

    /**
     * @return RedirectResponse|void
     */
    public function formRegister(Request $request)
    {
        try {
            DB::beginTransaction();

            $form_setting_query = FormSetting::where('apply_type', $request->apply_type)
                ->where('form_no', $request->form_no)->first();

            if ($form_setting_query) {
                // 更新
                $form_setting_query->update($request->except('_token'));
            } else {
                // 登録
                FormSetting::create($request->except('_token'));
            }

            DB::commit();
            Redirect::route('admin.form-create')->send();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     *
     */
    public function getFormDetail(Request $request)
    {
        $apply_type = $request->query('apply_type');
        $form_no = $request->query('form_no') ?? 1;

        $form_setting_query = FormSetting::where('apply_type', $apply_type)
            ->where('form_no', $form_no);

        return response()->json([
            'form_setting' => $form_setting_query->first(),
        ]);
    }

}
