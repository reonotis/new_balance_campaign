<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTryOnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('try_ons', function (Blueprint $table) {
            $table->bigIncrements('id')                    ->comment('ID');
            $table->string('f_name')                       ->comment('苗字');
            $table->string('l_name')                       ->comment('名前');
            $table->string('f_read')                       ->comment('ミョウジ');
            $table->string('l_read')                       ->comment('ナマエ');
            // $table->integer('age')                         ->comment('年齢');
            // $table->integer('sex')                         ->comment('性別');
            $table->string('tel')              ->nullable()->comment('電話番号');
            $table->string('email')            ->nullable()->comment('メールアドレス');
            $table->string('zip21', '3')        ->nullable()->comment('郵便番号1');
            $table->string('zip22', '4')        ->nullable()->comment('郵便番号2');
            $table->string('pref21')           ->nullable()->comment('都道府県');
            $table->string('addr21')           ->nullable()->comment('市区町村');
            $table->string('street21')         ->nullable()->comment('所在');
            $table->string('img_pass')                     ->comment('画像パス');
            $table->string('reason_applying')  ->nullable()->comment('応募動機');

            $table->timestamp('created_at')    ->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時')	;
            $table->timestamp('updated_at')    ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
            $table->boolean('delete_flag')     ->default('0')->comment('削除フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('try_ons');
    }
}
