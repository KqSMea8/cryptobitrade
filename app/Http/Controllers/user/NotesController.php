<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\user\UserAdminController;
use Response;
use Validator;
use Auth;
use App\Note;

class NotesController extends UserAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
          
        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'description'          => 'required',
            'color'          => 'required',
        ]);

        if ($validator->fails()) {

             return Response::json([
                'error' => true,
                'message' => $validator->messages()->first(),
                'code' => 400
            ], 400);

        } else {

            $note = Note::create([
                'user_id'        => Auth::id(),
                'title'          => $request->title,
                'description'    => $request->description,
                'color'          => $request->color
            ]);
            return Response::json([
            'error' => false,
            'code'  => 200,
            'color'  => $note->color,
            'id'  => $note->id,
            'title'  => $note->title,
            'description'  => $note->description,
            'date'  => $note->created_at->diffForHumans()
        ], 200);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if(Note::where('id', $id)->value('user_id') != Auth::user()->id){
              return Response::json([
                'error' => true,
                'message' =>  'Whoops, looks like something went wrong',
                'code' => 400
            ], 400);
          }else{

                 Note::where('id', $id)
                    ->where('user_id',Auth::id())
                    ->delete();

                    return Response::json([
                        'error' => false,
                        'code'  => 200
                    ], 200);
          }         
       
     
    }
}
