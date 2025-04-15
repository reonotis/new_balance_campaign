
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnOnFormSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->dropColumn('apply_type');  //カラムの削除
            $table->dropColumn('form_no');  //カラムの削除
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
            $table->integer('apply_type')->nullable()->after('id')->comment('フォームタイプ');
            $table->integer('form_no')->nullable()->after('route_name')->comment('フォームNo.');
        });
    }
}
