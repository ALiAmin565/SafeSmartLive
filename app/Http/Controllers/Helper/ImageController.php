<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function deletePreviousImage($fileName, $directory)

    {

      if ($fileName) {
           $previousImagePath=public_path("$directory/$fileName");
          if (file_exists($previousImagePath)) {
              unlink($previousImagePath);

          }
      }
    }

}
