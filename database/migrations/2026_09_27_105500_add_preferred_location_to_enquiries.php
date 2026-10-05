<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->string('preferred_location')->nullable()->after('guests');
        });

        $rows = DB::table('enquiries')->whereNotNull('message')->get(['id', 'message']);
        foreach ($rows as $row) {
            if (! preg_match('/^Preferred location:\s*(.*?)(?:\R([\s\S]*))?$/u', (string) $row->message, $match)) {
                continue;
            }
            $location = trim($match[1]);
            $message = trim($match[2] ?? '');
            DB::table('enquiries')->where('id', $row->id)->update([
                'preferred_location' => $location !== '' ? $location : null,
                'message' => $message !== '' ? $message : null,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropColumn('preferred_location');
        });
    }
};
