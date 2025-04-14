
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormSettingTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->text('css_file_name')->nullable()->after('image_dir_name')->comment('cssファイル名');
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
            $table->dropColumn('css_file_name');  //カラムの削除
        });
    }
}
