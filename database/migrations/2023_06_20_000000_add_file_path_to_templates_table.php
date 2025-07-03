<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathToTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('templates', 'file_path')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->string('file_path')->nullable()->after('content');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('templates', 'file_path')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }
}