<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'blocked'])->default('active')->after('role');
            }

            if (!Schema::hasColumn('users', 'vendeur_id')) {
                $table->unsignedBigInteger('vendeur_id')->nullable()->after('status');
                $table->foreign('vendeur_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('users', 'vendeur_id')) {
                $table->dropForeign(['vendeur_id']);
                $table->dropColumn('vendeur_id');
            }
        });
    }
}
