
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMailTextTypeInFormSettingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->longText('mail_text')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_setting', function (Blueprint $table) {
            $table->string('mail_text', 255)->change();
        });
    }
}
