<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\recommendation;
use App\Http\Resources\ArchiveResource;
use App\Http\Requests\StoreArchiveRequest;
use App\Http\Requests\UpdateArchiveRequest;

class ArchiveController extends Controller
{

    public function index()
    {
// return 150;
        return   ArchiveResource::collection(Archive::orderBy('created_at', 'desc')->with(['user','recommendation'])->get());
    }


    public function store(StoreArchiveRequest $request)
    {

 

        $rec=recommendation::where('id',$request['recomondation_id'])->first();



        if (!$rec) {
           return response()->json(['message' => 'request not found'], 404);
       }
        $rec->update([
           'archive'=>1,
        ]);



        $archive=Archive::create([
            'recomondation_id'=>$request['recomondation_id'],
            "desc"=>$request->desc,
            "user_id"=>$request->user_id,
            "title"=>$request->title

           ]);
           return response()->json(['message' => 'The conversion was completed successfully']);

       }




        // return response()->json(Archive::create($request->all()));



    public function show($id)
    {
        $request = Archive::with(['recommendation:id,img,title', 'user:id,email'])->find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($request);
    }



    public function update(UpdateArchiveRequest $request,$id)
    {
           $archive=Archive::find($id);
           if (!$archive) {
            return response()->json(['message' => 'Request not found'], 404);
        }
            $archive->update($request->all());

            return $this->show($archive);

    }


    public function destroy($id)
    {
        $archive=Archive::find($id);
        if (!$archive) {
         return response()->json(['message' => 'Request not found'], 404);
     }
        $archive->delete();
        return response()->json(['message' => 'Deleted successfully']);


    }
}
