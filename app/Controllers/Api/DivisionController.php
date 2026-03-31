<?php

namespace Controllers\Api;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Models\Division;
use Models\Order;
use Src\Auth\Auth;
use Src\Request;
use Src\View;

class DivisionController
{
    public function reports(Request $request): void
    {
        $user = Auth::user();

        if ($user->isStorekeeper()) {
            $division = $user->division;
            if ($division) {
                $this->showReport($request->set('id', $division->id));
                return;
            } else {
                (new View())->toJSON(['message' => 'You are not assigned to any division.'], 403);
                return;
            }
        }

        $divisions = Division::all();
        $reports = [];
        foreach ($divisions as $division) {
            $orders = Order::where('division_id', $division->id)
                ->with('product')
                ->get();
            $reports[] = [
                'division' => $division->toArray(),
                'orders' => $orders->toArray()
            ];
        }
        (new View())->toJSON($reports);
    }

    public function showReport(Request $request): void
    {
        $user = Auth::user();

        if ($user->isStorekeeper()) {
            $division = $user->division;
            if (!$division) {
                (new View)->toJSON([
                    'error' => 'You are not assigned to any division.',
                ], 403);
            }
            $division_id = $division->id;
        } else {
            $division_id = $request->get('id');
        }

        if (!$division_id) {
            (new View())->toJSON(['error' => 'Division ID is required.'], 400);
            return;
        }

        if ($user->isStorekeeper() && $user->division && $user->division->id != $division_id) {
            (new View())->toJSON(['error' => 'Unauthorized to view this division\'s report.'], 403);
            return;
        }

        try {
            $division = Division::findOrFail($division_id);
            $orders = Order::where('division_id', $division_id)
                ->with('product')
                ->get();

            (new View())->toJSON([[
                'division' => $division->toArray(),
                'orders' => $orders->toArray()
            ]]);
        } catch (ModelNotFoundException $e) {
            (new View())->toJSON(['error' => 'Division not found.'], 404);
        }
    }
}
