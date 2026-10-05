<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('detail_heading')->nullable()->after('description');
            $table->json('places')->nullable()->after('detail_heading');
        });

        $locations = DB::table('locations')->get();
        foreach ($locations as $location) {
            $rows = DB::table('villa_attractions')
                ->join('villas', 'villas.id', '=', 'villa_attractions.villa_id')
                ->where('villas.location_id', $location->id)
                ->orderBy('villa_attractions.display_order')
                ->get(['villa_attractions.name', 'villa_attractions.distance', 'villa_attractions.category']);

            $places = [];
            $seen = [];
            foreach ($rows as $row) {
                $key = strtolower(trim($row->name));
                if ($key === '' || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $places[] = [
                    'name' => $row->name,
                    'distance' => $row->distance,
                    'category' => $row->category,
                ];
            }

            if ($places) {
                DB::table('locations')->where('id', $location->id)->update([
                    'places' => json_encode($places),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['detail_heading', 'places']);
        });
    }
};
