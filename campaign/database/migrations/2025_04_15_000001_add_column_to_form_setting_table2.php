
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormSettingTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->text('banner_file_name')->nullable()->after('css_file_name')->comment('バナーファイル名');
            $table->string('agreement', 5000)->nullable()->after('banner_file_name')->comment('規約');
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
            $table->dropColumn('banner_file_name');  //カラムの削除
            $table->dropColumn('agreement');  //カラムの削除
        });
    }
}
