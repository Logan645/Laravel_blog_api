<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('body');

            $table->unsignedBigInteger('category_id')->index(); //新增一個欄位category_id
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade'); //發現關聯的category不見了，會自動把對有用到這category_ID的文章刪除
                // ->onDelete('set null'); //發現關聯的category不見了，會自動把有用到這category的文章該欄category_id設為null
            $table->foreignId('user_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
