<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCommonApplyTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('common_apply', function (Blueprint $table) {
            $table->string('comment2', '5000')->nullable()->after('comment')->comment('コメント2');
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
            $table->dropColumn('comment2');  //カラムの削除
        });
    }
}
