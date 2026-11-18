<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryTrackingToEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('application_status_history_id')->nullable()->unique();
            $table->foreign('application_status_history_id', 'email_logs_status_history_foreign')
                ->references('id')
                ->on('application_status_histories')
                ->nullOnDelete();
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->text('error_message')->nullable();
        });
    }

    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropForeign('email_logs_status_history_foreign');
            $table->dropUnique(['application_status_history_id']);
            $table->dropColumn([
                'application_status_history_id',
                'attempts',
                'error_message',
            ]);
        });
    }
}
