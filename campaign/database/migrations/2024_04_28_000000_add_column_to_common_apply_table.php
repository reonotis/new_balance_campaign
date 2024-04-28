<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCommonApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            $table->integer('choice_3')->nullable()->after('choice_2')->comment('選択項目3');
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
            $table->dropColumn('choice_3');  //カラムの削除
        });
    }
}
