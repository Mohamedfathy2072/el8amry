<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{

    public function index()
    {
        if(request()->expectsJson()) {
            $partners = Partner::all();

            $items = $partners->mapWithKeys(function ($partner) {
                return [
                    $partner->id => [
                        'id' => $partner->id,
                        'title' => $partner->title,
                        'description' => $partner->description,
                        'link' => $partner->link,
                        'image_url' => asset('storage/' . $partner->image),
                        'created_at' => $partner->created_at,
                        'updated_at' => $partner->updated_at,
                    ]
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Partners fetched successfully.',
                'data' => [
                    'items' => $items,
                ]
            ]);
        }
        $data = Partner::latest()->paginate(10);
        return view('pages.partners', compact('data'));
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'required|image|max:2048',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('partners', 'public');
        }

        Partner::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'link' => $request->input('link'),
            'image' => $imagePath
        ]);

        return redirect()->route('admin.Partners')->with('success', 'Partner created successfully.');
    }

    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $partner = Partner::findOrFail($id);
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'link' => $request->input('link')
        ];

        if ($request->hasFile('image')) {
            if ($partner->image && Storage::disk('public')->exists($partner->image)) {
                Storage::disk('public')->delete($partner->image);
            }

            $data['image'] = $request->file('image')->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.Partners')->with('success', 'Partner updated successfully.');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        Storage::disk('public')->delete($partner->image);
        $partner->delete();

        return redirect()->route('admin.Partners')->with('success', 'Partner deleted successfully.');
    }


    public function show($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            return response()->json($partner, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Partner not found'], 404);
        }
    }

}
