<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlertController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alerts = Alert::query()
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('facility'), fn ($q, $facility) => $q->where('facility_id', $facility))
            ->latest('created_at')
            ->get();

        return response()->json(['alerts' => $alerts, 'total' => $alerts->count()]);
    }

    public function store(Request $request): JsonResponse
    {
        // `type` stays a free string, as in the legacy API: adding a button in the
        // frontend must not require a backend change.
        $data = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'facility_id' => ['required', 'string'],
            'room' => ['required', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'recipients' => ['nullable', 'integer', 'min:0'],
        ]);

        $alert = Alert::create($data + ['status' => 'active']);

        return response()->json($alert, 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $alert = Alert::findOrFail($id);

        $data = $request->validate([
            'status' => ['sometimes', Rule::in(['active', 'acknowledged', 'resolved'])],
            'acknowledged_by' => ['sometimes', 'string'],
        ]);

        if (isset($data['acknowledged_by'])) {
            $data['acknowledged_at'] = now();
        }

        $alert->update($data);

        return response()->json($alert->fresh());
    }

    public function respond(Request $request, string $id): JsonResponse
    {
        $alert = Alert::findOrFail($id);

        $data = $request->validate(['name' => ['nullable', 'string']]);

        // Wrapped in its own array so it appends as a list entry rather than
        // merging into the existing keys.
        $alert->responders = array_merge($alert->responders ?? [], [[
            'name' => $data['name'] ?? 'Responder',
            'responded_at' => now()->toIso8601String(),
        ]]);

        $alert->save();

        return response()->json($alert->fresh());
    }
}
