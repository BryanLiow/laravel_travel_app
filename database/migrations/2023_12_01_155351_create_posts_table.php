<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title'); // Title of the post
            $table->string('country'); // Country
            $table->unsignedBigInteger('likes')->default(0); // Number of likes, default is 0
            $table->unsignedBigInteger('user_id'); // Foreign key to users table
            $table->string('image_path'); // Path to the stored image
            $table->timestamps(); // created_at and updated_at timestamps

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade'); // if user is deleted, delete their posts too
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
