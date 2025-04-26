
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormSettingTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->boolean('send_bulk_mail_flg')->after('agreement')->comment('一斉メール送信フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->dropColumn('send_bulk_mail_flg');  //カラムの削除
        });
    }
}
