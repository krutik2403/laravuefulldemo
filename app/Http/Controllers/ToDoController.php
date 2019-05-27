<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Todo;

class ToDoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            $todos = Todo::orderBy('created_at', 'DESC')->get();

            return response()->json([   
                'status'    => 1,
                'message'   => trans('validation.success'),  
                'data'      => [
                    'todos' => $todos
                ]
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'status'    => 0, 
                'message'   => $e->getMessage()
            ]);
        }
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
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        try {

            if($validator->fails()) {
                return response()->json([
                    'status' => 0, 
                    'message' => implode(", ", $validator->messages()->all())
                ]);
            }

            $todo = Todo::create([
                'title' => $request->title,
                'description' => $request->description
            ]);

            return response()->json([
                'status'    => 1, 
                'message'   => 'Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 0, 
                'message'   => $e->getMessage()
            ]);    
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
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string']
        ]);

        try {

            if($validator->fails()) {
                return response()->json([
                    'status' => 0, 
                    'message' => implode(", ", $validator->messages()->all())
                ]);
            }

            $todo = Todo::find($id);            
            if( $todo ) {               
                $todo->title = $request->title;
                $todo->description = $request->description;
                $todo->save();
            } else {
                return response()->json([
                    'status'    => 0, 
                    'message'   => 'Item not found'
                ]);
            }        

            return response()->json([
                'status'    => 1, 
                'message'   => 'Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'    => 0, 
                'message'   => $e->getMessage()
            ]);    
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $todo = Todo::findOrFail($id);

            if($todo->delete()) {
                return response()->json([
                    'status'    => 1, 
                    'message'   => 'Success'
                ]);    
            } else {
                return response()->json([
                    'status'    => 0, 
                    'message'   => 'Something went wrong'
                ]);        
            }

        } catch (\Exception $e) {
            return response()->json([
                'status'    => 0, 
                'message'   => $e->getMessage()
            ]);    
        }
    }
}
