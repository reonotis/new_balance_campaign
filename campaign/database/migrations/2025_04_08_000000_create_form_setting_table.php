<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_setting', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID');
            $table->integer('apply_type')->comment('フォームタイプ');
            $table->text('route_name')->comment('ルーティング名');
            $table->integer('form_no')->comment('フォームNo.');

            $table->string('title')->comment('タイトル');
            $table->integer('max_application_count')->nullable()->comment('最大申込可能数');

            $table->timestamp('start_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('開始日時');
            $table->timestamp('end_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('終了日時');

            // メール関係
            $table->string('secretariat_mail_address')->comment('事務局のメールアドレス');
            $table->string('mail_title')->comment('自動返信メールの題名');
            $table->string('mail_text')->comment('自動返信メールの本文');

            $table->string('image_dir_name')->nullable()->comment('画像を格納するディレクトリ名');

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
        Schema::dropIfExists('form_setting');
    }
}
