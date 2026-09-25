<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'active_count' => Alert::where('status', 'active')->count(),
            'resolved_today' => Alert::where('status', 'resolved')
                ->whereDate('updated_at', today())
                ->count(),
            'total_alerts' => Alert::count(),
            // ponytail: hardcoded, as in the legacy API. Make this a real count
            // of on-duty responders once the responders table exists.
            'responders_on_duty' => 12,
        ]);
    }
}
