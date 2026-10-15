<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_no', 20)->unique();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->string('nama');
            $table->string('email');
            $table->string('no_hp', 20);
            $table->string('ttl_tempat');
            $table->date('ttl_tanggal');
            $table->string('cv_path');
            $table->string('surat_path');
            $table->string('status', 20)->default('menunggu');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('decided_at')->nullable();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('email');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
