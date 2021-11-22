<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('published_by')->nullable();
            $table->string('content', 500)->nullable(false);
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
              
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();    

            $table->foreign('published_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
