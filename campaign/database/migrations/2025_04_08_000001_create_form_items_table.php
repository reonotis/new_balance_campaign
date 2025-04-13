<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_item', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->bigInteger('form_setting_id')->comment('form_settingのID');

            $table->tinyInteger('type_no')->comment('項目タイプ');
            $table->tinyInteger('sort')->comment('並び順');
            $table->tinyInteger('require_flg')->comment('必須フラグ');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')->default('0')->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_item');
    }
}
