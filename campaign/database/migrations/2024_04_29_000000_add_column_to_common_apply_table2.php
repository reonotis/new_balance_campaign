<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCommonApplyTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            // 当選メールを送ったフラグ
            $table->integer('send_lottery_result_email_flg')->default(0)->after('choice_3')->comment('当選メールを送るフラグ');
            $table->integer('sent_lottery_result_email_flg')->default(0)->after('send_lottery_result_email_flg')->comment('当選メール送信済みフラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            $table->dropColumn('send_lottery_result_email_flg');  //カラムの削除
            $table->dropColumn('sent_lottery_result_email_flg');  //カラムの削除
        });
    }
}
