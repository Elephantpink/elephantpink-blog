<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('title');
            $table->text('subtitle')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->longText('body')->nullable();
            $table->string('slug', 255)->unique();
            $table->unsignedBigInteger('author_id');
            $table->datetime('publish_date')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('blog_authors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
