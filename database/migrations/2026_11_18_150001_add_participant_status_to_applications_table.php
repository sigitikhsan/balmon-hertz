<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddParticipantStatusToApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('participant_status', 30)->nullable()->after('status');
            $table->index('participant_status');
        });

        DB::table('applications')
            ->where('status', 'diterima')
            ->update(['participant_status' => 'belum_mulai']);
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['participant_status']);
            $table->dropColumn('participant_status');
        });
    }
}
