<?php

namespace Database\Seeders;

use App\Models\Alert;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LegacyAlertsSeeder extends Seeder
{
    public function run(): void
    {
        // Snapshot of the pre-Laravel api/data/alerts.json, kept in-project so
        // seeding doesn't depend on original_backup/ (which lives outside the repo).
        $legacy = __DIR__.'/alerts.json';

        if (! File::exists($legacy)) {
            return;
        }

        $alerts = json_decode(File::get($legacy), true) ?: [];

        foreach ($alerts as $alert) {
            Alert::updateOrCreate(
                ['id' => $alert['id']],
                [
                    'type' => $alert['type'],
                    'facility_id' => $alert['facility_id'],
                    'room' => $alert['room'],
                    'message' => $alert['message'] ?? null,
                    'status' => $alert['status'],
                    'recipients' => $alert['recipients'] ?? 0,
                    'responders' => $alert['responders'] ?? [],
                    'acknowledged_by' => $alert['acknowledged_by'] ?? null,
                    'acknowledged_at' => $alert['acknowledged_at'] ?? null,
                    'created_at' => $alert['created_at'],
                    'updated_at' => $alert['updated_at'],
                ],
            );
        }
    }
}
