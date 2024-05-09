<?php

namespace App\Service;

use App\Consts\Common;
use App\Consts\CommonApplyConst;
use App\Consts\KichijojiGrayDays5KRun;
use App\Models\CommonApply;
use Mail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class MailSendService
{
    protected int $applyType;

    function __construct(int $applyType)
    {
        $this->applyType = $applyType;
    }

    /**
     *
     * @param CommonApply $target
     * @return void
     */
    public function sendmail(CommonApply $target)
    {
        $template = CommonApplyConst::WINNING_EMAIL_TEMPLATE[$this->applyType];
        $mailTitle = CommonApplyConst::WINNING_EMAIL_TITLE[$this->applyType];

        $data = [
            'customerName' => $target->f_name . ' ' . $target->l_name,
            'read' => $target->f_read . ' ' . $target->l_read,
            'zip' => $target->zip21 . '-' . $target->zip22,
            'streetAddress' => $target->pref21 . ' ' . $target->address21 . ' ' . $target->street21,
            'tel' => $target->tel,
            'email' => $target->email,
            'url' => url('') . '/admin',
        ];
        Mail::send($template, $data, function ($message) use ($target, $mailTitle) {
            $message->to($target->email)
                ->from("info@newbalance-campaign.jp")
                ->bcc("fujisawareon@yahoo.co.jp")
                ->subject($mailTitle);
        });

    }

}
