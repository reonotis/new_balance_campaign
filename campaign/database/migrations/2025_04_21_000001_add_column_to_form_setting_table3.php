
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormSettingTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->string('form_information', '5000')->nullable()->after('end_at')->comment('記入事項');
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
            $table->dropColumn('form_information');  //カラムの削除
        });
    }
}
