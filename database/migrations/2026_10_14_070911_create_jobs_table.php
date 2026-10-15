<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('fields')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type', 20);
            $table->string('duration', 50);
            $table->unsignedInteger('quota')->default(0);
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
