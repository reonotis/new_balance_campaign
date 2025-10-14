
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application', function (Blueprint $table) {
            $table->string('my_NBID', 20)->nullable()->after('choice_4')->comment('myNBID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application', function (Blueprint $table) {
            $table->dropColumn('my_NBID');  //カラムの削除
        });
    }
}
