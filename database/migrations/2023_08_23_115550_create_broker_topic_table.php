<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrokerTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('broker_topic', function (Blueprint $table) {
            $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('broker_topic');
    }
}
