<?php

namespace Tests\Feature;

use App\Models\Alert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertApiTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'Fire',
            'facility_id' => 'building-a',
            'room' => 'Room 214',
            'message' => 'Smoke detected',
            'recipients' => 45,
        ], $overrides);
    }

    public function test_creating_an_alert_returns_it_with_an_active_status(): void
    {
        $response = $this->postJson('/api/alerts', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('type', 'Fire')
            ->assertJsonPath('facility_id', 'building-a')
            ->assertJsonPath('status', 'active')
            ->assertJsonPath('recipients', 45);

        $this->assertCount(1, Alert::all());
    }

    public function test_creating_an_alert_requires_type_facility_and_room(): void
    {
        $this->postJson('/api/alerts', ['room' => 'Room 214'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'facility_id']);
    }

    public function test_responders_accumulate_as_a_list(): void
    {
        $id = $this->postJson('/api/alerts', $this->payload())->json('id');

        $first = $this->postJson("/api/alerts/{$id}/respond", ['name' => 'Maria']);
        $second = $this->postJson("/api/alerts/{$id}/respond", ['name' => 'Mike']);

        // The admin UI reads `alert.responders.length`, so this must stay an array.
        $this->assertIsArray($first->json('responders'));
        $this->assertCount(2, $second->json('responders'));
        $this->assertSame('Maria', $second->json('responders.0.name'));
        $this->assertSame('Mike', $second->json('responders.1.name'));
    }

    public function test_acknowledging_stamps_the_acknowledger_and_time(): void
    {
        $id = $this->postJson('/api/alerts', $this->payload())->json('id');

        $response = $this->putJson("/api/alerts/{$id}", [
            'status' => 'acknowledged',
            'acknowledged_by' => 'Admin',
        ]);

        $response->assertOk()
            ->assertJsonPath('status', 'acknowledged')
            ->assertJsonPath('acknowledged_by', 'Admin');

        $this->assertNotNull($response->json('acknowledged_at'));
    }

    public function test_alerts_can_be_filtered_by_status_and_facility(): void
    {
        $this->postJson('/api/alerts', $this->payload(['status' => 'active']));
        $other = $this->postJson('/api/alerts', $this->payload(['facility_id' => 'campus']));

        $this->putJson("/api/alerts/{$other->json('id')}", ['status' => 'resolved']);

        $active = $this->getJson('/api/alerts?status=active');
        $active->assertOk()->assertJsonPath('total', 1)
            ->assertJsonPath('alerts.0.facility_id', 'building-a');

        $this->getJson('/api/alerts?facility=campus')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('alerts.0.status', 'resolved');
    }

    public function test_alerts_are_listed_newest_first(): void
    {
        $older = $this->postJson('/api/alerts', $this->payload(['room' => 'Older']))->json('id');
        $newer = $this->postJson('/api/alerts', $this->payload(['room' => 'Newer']))->json('id');

        Alert::where('id', $older)->update(['created_at' => now()->subDay()]);

        $this->getJson('/api/alerts')
            ->assertOk()
            ->assertJsonPath('alerts.0.id', $newer)
            ->assertJsonPath('alerts.1.id', $older);
    }

    public function test_updating_a_missing_alert_returns_404(): void
    {
        $this->putJson('/api/alerts/ALT-missing', ['status' => 'resolved'])
            ->assertNotFound();
    }

    public function test_facilities_and_stats_endpoints_respond(): void
    {
        $this->getJson('/api/facilities')
            ->assertOk()
            ->assertJsonPath('0.id', 'building-a');

        $this->postJson('/api/alerts', $this->payload());

        $this->getJson('/api/stats')
            ->assertOk()
            ->assertJsonPath('active_count', 1)
            ->assertJsonPath('total_alerts', 1)
            ->assertJsonStructure(['active_count', 'resolved_today', 'total_alerts', 'responders_on_duty']);
    }

    public function test_status_rejects_unknown_values(): void
    {
        $id = $this->postJson('/api/alerts', $this->payload())->json('id');

        $this->putJson("/api/alerts/{$id}", ['status' => 'exploded'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('status');
    }

    public function test_the_admin_and_rescuee_pages_render(): void
    {
        $this->get('/admin')->assertOk();
        $this->get('/rescuee')->assertOk();
    }
}
