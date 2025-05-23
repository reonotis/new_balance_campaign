
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_item', function (Blueprint $table) {
            $table->json('choice_data')->nullable()->after('require_flg')->comment('選択肢データ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_item', function (Blueprint $table) {
            $table->dropColumn('choice_data');  //カラムの削除
        });
    }
}
