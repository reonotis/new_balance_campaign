
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToFormItemTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_item', function (Blueprint $table) {
            $table->text('comment_title')->nullable()->after('choice_data')->comment('コメントタイトル');
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
            $table->dropColumn('comment_title');  //カラムの削除
        });
    }
}
