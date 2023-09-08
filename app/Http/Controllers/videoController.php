<?php

namespace App\Http\Controllers;

use App\Models\video;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Resources\VadioResource;
use App\Http\Requests\StoreVideosRequest;

class videoController extends Controller
{
    public function index()
    {
        return VadioResource::collection(video::get());
    }

    public function create()
    {
        //
    }

    public function store(StoreVideosRequest $request)
    {
        $img = null;
        $video = null;

        // For img
        if ($request->hasFile('img')) {
            $img = $this->uploadFile($request->file('img'), 'videosthumbnails');
        }

        // For Video
        if ($request->hasFile('video')) {
            $video = $this->uploadFile($request->file('video'), 'Videos');
        }

        $videos = video::create([
            'title' => $request['title'],
            'img' => $img,
            'desc' => $request['desc'],
            'video' => $video,
        ]);

        return response()->json([
            'Massage' => "Request is Success",
        ]);
    }

    public function show($id)
    {
        return VadioResource::make(video::find($id));
    }

    public function edit($id)
    {
        return VadioResource::collection(video::find($id));
    }

    public function update(StoreVideosRequest $request,$id)
    {

        // return $request->all();

        $check = video::find($id);

        if (!$check) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        if ($request->hasFile('img')) {
            return 1500;
            $img = $this->uploadFile($request->file('img'), 'videosthumbnails');
        } else {
            $img = $check->img;
        }

        if ($request->hasFile('video')) {
            $this->deletePreviousImage($check->video, 'Videos');
            $video = $this->uploadFile($request->file('video'), 'Videos');
        } else {
            $video = $check->video;
        }

        $check->update([
            'img' => $img,
            'desc' => $request['desc'],
            'video' => $video,
            'title' => $request->title,
        ]);

        return response()->json([
            'Massage' => "Request is Updated",
        ]);
    }

    public function destroy($id)
    {
        $check = video::find($id);


        if (!$check) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $this->deletePreviousImage($check->video, 'Videos');
        $this->deletePreviousImage($check->img, 'videosthumbnails');
        $check->delete();

         return response()->json(['massage'=>'deleted']);

    }

    private function uploadFile($file, $directory)
    {
        $fileName = time() . '.' . $file->extension();
        $file->move(public_path($directory), $fileName);
        return $fileName;
    }


   public function deletePreviousImage($fileName, $directory)

  {
    if ($fileName) {
        $previousImagePath = public_path("$directory/$fileName");
        if (file_exists($previousImagePath)) {
            unlink($previousImagePath);
        }
    }
  }



}
