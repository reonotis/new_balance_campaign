<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCommonApplyTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            $table->string('comment3', '1000')->nullable()->after('comment2')->comment('コメント3');
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
            $table->dropColumn('1000');  //カラムの削除
        });
    }
}
