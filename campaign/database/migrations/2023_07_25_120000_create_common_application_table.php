<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommonApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('common_apply', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('apply_type')->comment('対象となる申込種類');

            $table->string('f_name')->comment('苗字');
            $table->string('l_name')->comment('名前');
            $table->string('f_read')->comment('ミョウジ');
            $table->string('l_read')->comment('ナマエ');
            $table->integer('sex')->nullable()->comment('性別');
            $table->integer('age')->nullable()->comment('年齢');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('zip21', '3')->nullable()->comment('郵便番号1');
            $table->string('zip22', '4')->nullable()->comment('郵便番号2');
            $table->string('pref21')->nullable()->comment('都道府県');
            $table->string('address21')->nullable()->comment('市区町村');
            $table->string('street21')->nullable()->comment('所在');
            $table->string('img_pass')->nullable()->comment('画像パス');
            $table->string('comment', '5000')->nullable()->comment('コメント');
            $table->integer('choice_1')->nullable()->comment('選択項目1');
            $table->integer('choice_2')->nullable()->comment('選択項目2');

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
        Schema::dropIfExists('common_apply');
    }
}
