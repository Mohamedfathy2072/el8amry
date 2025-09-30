<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BodyStyleResource;
use App\Models\BodyStyle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BodyStyleController extends Controller
{
    public function index()
    {
        $data = BodyStyle::latest()->paginate(10);
        return view('pages.BodyStyles', compact('data'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('body_style', 'public');
        }

        BodyStyle::create([
            'name' => [
                'ar' => $request->input('name_ar'),
                'en' => $request->input('name_en')
            ],
            'image' => $imagePath
        ]);

        return redirect()->route('admin.BodyStyles')->with('success', 'Body Style created successfully.');
    }

    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $bodyStyle = BodyStyle::findOrFail($id);

        $data = [
            'name' => [
                'ar' => $request->input('name_ar'),
                'en' => $request->input('name_en')
            ]
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($bodyStyle->image) {
                Storage::disk('public')->delete($bodyStyle->image);
            }
            $data['image'] = $request->file('image')->store('body_style', 'public');
        }

        $bodyStyle->update($data);

        return redirect()->route('admin.BodyStyles')->with('success', 'Body Style updated successfully.');
    }

    public function destroy($id)
    {
        $brand = BodyStyle::findOrFail($id);

        if (!empty($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('admin.BodyStyles')
            ->with('success', 'Body Style deleted successfully.');
    }

    /**
     * Display a listing of the resource.
     */
    public function indexAPI()
    {
        $bodyStyles = BodyStyle::all();
        return BodyStyleResource::collection($bodyStyles);
    }

    /**
     * Display the specified resource.
     */
    public function showAPI(int $id)
    {
        try {
            $bodyStyle = BodyStyle::findOrFail($id);
            return new BodyStyleResource($bodyStyle);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'BodyStyle not found'], 404);
        }
    }
}
