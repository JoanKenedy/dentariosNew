<?php

namespace App\Http\Controllers\Admin\Course;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Course\CourseGResource;
use App\Http\Resources\Course\CourseGCollection;

class CourseGController extends Controller
{
      /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $state = $request->state;
      
        $courses = Course::orderBy("id","desc")->get();

        return response()->json([
            "courses" => CourseGCollection::make($courses),
         /*   "courses" => $courses->map(function($categorie){
                return [
                    "name"=>$categorie->name,
                    "surname"=>$categorie->surname,
                    "email"=>$categorie->email,
                    "role"=>$categorie->role,
                    "avatar"=>env("APP_URL")."storage/". $categorie->avatar,


                ];
            }),*/
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        if($request ->hasFile('portada')){
           $path = Storage::putFile("courses",$request->file("portada"));
           $request->request->add(["imagen" => $path]); 
        }
        $request->request->add(["slug" => Str::slug($request->title)]);
        $request->request->add(["requirements" => json_encode($request->requirements)]);
        $request->request->add(["who_is_it_for" => json_encode($request->who_is_it_for)]);
        $course = Course::create($request -> all());

        return response()-> json(["course" => CourseGResource::make($course)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    
        $course = Course::findOrFail($id);
        if($request ->hasFile('portada')){
            if($course->imagen){
                Storage::delete($course->imagen);
            }
            $path = Storage::putFile("courses",$request->file("portada"));
            $request->request->add(["imagen" => $path]); 
         }
         $request->request->add(["slug" => Str::slug($request->title)]);
         $request->request->add(["requirements" => json_encode($request->requirements)]);
         $request->request->add(["who_is_it_for" => json_encode($request->who_is_it_for)]);
       
       $course->update($request->all());
       return response()->json(["categorie" => CourseGResource::make($course)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return response()->json(["message" => 200]);
    }

}
