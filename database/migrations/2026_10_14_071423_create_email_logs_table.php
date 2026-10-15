<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->nullable()->constrained('applications')->nullOnDelete();
            $table->string('to_email');
            $table->string('channel', 20)->default('email');
            $table->string('subject');
            $table->text('body');
            $table->string('status', 20)->default('sent');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('to_email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_logs');
    }
}
