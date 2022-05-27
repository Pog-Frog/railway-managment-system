<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); ///
            $table->unsignedBigInteger('ticket')->nullable();
            $table->foreign('ticket')->references('id')->on('tickets')->onDelete('set null');
            $table->unsignedBigInteger('trip')->nullable();
            $table->foreign('trip')->references('id')->on('trips')->onDelete('set null');
            $table->unsignedBigInteger('seat')->nullable();
            $table->foreign('seat')->references('id')->on('seats')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_tickets');
    }
}
