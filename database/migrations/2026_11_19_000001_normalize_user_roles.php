<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeUserRoles extends Migration
{
    public function up()
    {
        DB::table('users')
            ->whereIn('role', ['super_admin', 'super-admin', 'SuperAdmin'])
            ->update(['role' => 'superadmin']);

        DB::table('users')
            ->where(function ($query) {
                $query->whereNull('role')
                    ->orWhere('role', '');
            })
            ->update(['role' => 'peserta']);
    }

    public function down()
    {
    }
}
