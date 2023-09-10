<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    /**
     * @var array<string, string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'link',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $table = 'ads';

    private static function handleModel($request, $existingImagePath)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'link' => $request->link,
            'image' => $existingImagePath, // Initialize with existing image path
        ];
        // Check if a new image is provided in the request
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $newImage = $request->file('image');
            // Generate a unique filename based on current time and the original filename
            $newImageFilename = time() . '_' . $newImage->getClientOriginalName();
            // Construct the directory path using the public_path() function
            $imageDirectory = public_path('images/ads/');
            // Store the new image in the desired directory with the generated filename
            $newImage->move($imageDirectory,  $newImageFilename);
            if ($existingImagePath) {
                $fullExistingImagePath = public_path('images/ads/') . $existingImagePath;
                if (file_exists($fullExistingImagePath)) {
                    unlink($fullExistingImagePath);
                }
            }
            $data['image'] = $newImageFilename;
        }

        return $data;
    }

    public static function deleteModel($id)
    {
        $ads = self::find($id);
        $existingImagePath = $ads->image; // Store the existing image path
        if ($existingImagePath) {
            $fullExistingImagePath = public_path('images/ads/') . $existingImagePath;
            if (file_exists($fullExistingImagePath)) {
                unlink($fullExistingImagePath);
            }
        }
        return $ads->delete();
    }

    public static function updateModel($request, $id)
    {
            $ads = self::find($id);
            $existingImagePath = $ads->image; // Store the existing image path

            // Create an array of data to update
            $dataToUpdate = self::handleModel($request, $existingImagePath);

            // Remove any fields that are not present in the request
            $dataToUpdate = array_filter($dataToUpdate, function ($value) {
                return !is_null($value);
            });

            // Update the record with the data
            $result = $ads->update($dataToUpdate);
    }
    

    public static function SaveModel($request)
    {
        return self::create(self::handleModel($request, $request->image));
    }

}
