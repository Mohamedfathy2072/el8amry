<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        if(request()->expectsJson()) {
            $videos = Video::all();

            $items = $videos->mapWithKeys(function ($video) {
                return [
                    $video->id => [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'video_url' => $video->video,
                        'created_at' => $video->created_at,
                        'updated_at' => $video->updated_at,
                    ]
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Videos fetched successfully.',
                'data' => [
                    'items' => $items,
                ]
            ]);
        }
        $data = Video::latest()->paginate(10);
        return view('pages.videos', compact('data'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'video' => 'required|video|mimes:mp4,mov,avi,wmv'
        ]);
        dd($validate->fails(), $validate->errors());
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('videos', 'public');
        }

        Video::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'video' => $videoPath
        ]);

        return redirect()->route('admin.Videos')->with('success', 'Video created successfully.');

    }

    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'nullable|video|mimes:mp4,mov,avi,wmv'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $video = Video::findOrFail($id);
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ];

        if ($request->hasFile('video')) {
            if ($video->video && Storage::disk('public')->exists($video->video)) {
                Storage::disk('public')->delete($video->video);
            }

            $data['video'] = $request->file('video')->store('videos', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.Videos')->with('success', 'Video updated successfully.');
    }


    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        Storage::disk('public')->delete($video->video);
        $video->delete();

        return redirect()->route('admin.Videos')->with('success', 'Video deleted successfully.');
    }


    public function show($id)
    {
        try {
            $video = Video::findOrFail($id);
            return response()->json($video, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found'], 404);
        }
    }

}

