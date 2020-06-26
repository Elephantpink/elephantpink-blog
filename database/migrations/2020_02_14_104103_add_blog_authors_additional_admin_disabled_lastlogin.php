<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlogAuthorsAdditionalAdminDisabledLastlogin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_authors', function (Blueprint $table) {
            $table->text('additional_information')->after('name')->nullable();
            $table->boolean('is_admin')->after('password')->default(0);
            $table->boolean('is_disabled')->after('is_admin')->default(0);
            $table->dateTime('last_login')->after('is_disabled')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_authors', function (Blueprint $table) {
            $table->dropColumn('additional_information');
            $table->dropColumn('is_admin');
            $table->dropColumn('is_disabled');
            $table->dropColumn('last_login');
        });
    }
}
