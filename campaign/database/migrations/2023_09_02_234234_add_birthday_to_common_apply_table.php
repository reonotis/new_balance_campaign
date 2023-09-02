<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthdayToCommonApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('sex')->comment('生年月日');
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
            $table->dropColumn('birthday');  //カラムの削除
        });
    }
}
