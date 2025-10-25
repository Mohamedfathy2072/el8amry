<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancingRequest;
use Illuminate\Http\Request;

class FinancingRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancingRequest::with('car');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhereHas('car', function ($q) use ($search) {
                        $q->where('description', 'like', "%$search%")
                            ->orWhere('location', 'like', "%$search%");
                    });
            });
        }


        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('admin.requests.index', compact('requests'));
    }

    public function show($id)
    {
        $financingRequest = FinancingRequest::with('car')->findOrFail($id);

        return view('admin.requests.show', compact('financingRequest'));
    }


    public function updateStatus(Request $request, FinancingRequest $financingRequest)
    {

        $validated = $request->validate([
            'status' => 'required|in:Cancelled,Rejected,Accepted,In process'
        ]);

        $financingRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Request status updated successfully');
    }

    public function destroy(FinancingRequest $financingRequest)
    {
        $financingRequest->delete();
        return redirect()->route('admin.financing-requests.index')
            ->with('success', 'Request deleted successfully');
    }
}
