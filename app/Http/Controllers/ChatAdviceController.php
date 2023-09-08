<?php

namespace App\Http\Controllers;

use App\Models\Massage;
use App\Events\ChatPlan;
use Illuminate\Http\Request;

class ChatAdviceController extends Controller
{
    public function getChat()
    {
    return $collection=Massage::get();
     return view('welcome',compact('collection'));
    }

    public function store(Request $request)
    {

            if ($request->hasFile('img'))
            {

                $img=time(). '.'.$request->img->extension();
                $path= $request->img->move(public_path('Audio'),$img);


            }else
            {
                $img='null';
            }





         $user=Massage::create([
            'user_id'=>$request['user_id'],
            'plan_id'=>$request['plan_id'],
            'massage'=>$request['massage'],
            'img'=>$img,

         ]);

         event(new ChatPlan($user));

         return 150;


    }

    protected function getMimeTypeFromText($text)
{
    // Create a temporary file with the text content
    $tempFile = tempnam(sys_get_temp_dir(), 'text_check');
    file_put_contents($tempFile, $text);

    // Get the MIME type using finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $tempFile);
    finfo_close($finfo);

    return $mimeType;
}
}
