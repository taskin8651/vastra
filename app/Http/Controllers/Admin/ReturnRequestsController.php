<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestsController extends Controller
{
    public function index(Request $request)
    {
        $returnRequests = ReturnRequest::query()
            ->with(['user', 'order'])
            ->when($request->q, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('return_number', 'like', '%' . $request->q . '%')
                        ->orWhereHas('order', function ($orderQuery) use ($request) {
                            $orderQuery->where('order_number', 'like', '%' . $request->q . '%');
                        });
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(20);

        return view('admin.return-requests.index', compact('returnRequests'));
    }

    public function show(ReturnRequest $returnRequest)
    {
        $returnRequest->load(['user', 'order', 'items']);

        return view('admin.return-requests.show', compact('returnRequest'));
    }

    public function updateStatus(Request $request, ReturnRequest $returnRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,picked_up,refunded'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $returnRequest->update($data);

        return redirect()
            ->route('admin.return-requests.show', $returnRequest)
            ->with('message', 'Return request updated successfully.');
    }
}