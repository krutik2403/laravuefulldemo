<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Todo;
use Storage;

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
            
            $todos = Todo::orderBy('created_at', 'DESC')->paginate(3);

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
            'description' => ['required', 'string'],
            'image' => ['required']
        ]);

        // if($request->has('image')) {
        //     $image = $request->image;
        //     $name = uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        //     list($imageData, $imageContent) = explode(',', $image);            
        //     Storage::put($name, base64_decode($imageContent));
        //     echo $name . 'Image present';
        // } else {
        //     echo 'Not Present';
        // }
        // exit;

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

            if($request->has('image')) {
                $image = $request->image;
                $filename = uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                $path = "todo_images/";

                list($imageData, $imageContent) = explode(',', $image);            
                Storage::disk('public')->put($path . $filename, base64_decode($imageContent));
                $todo->image = $filename;
                $todo->save();                
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

                if($request->has('image')) {
                    $image = $request->image;
                    $filename = uniqid() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                    $path = "todo_images/";
    
                    list($imageData, $imageContent) = explode(',', $image);            
                    Storage::disk('public')->put($path . $filename, base64_decode($imageContent));
                    $old_image = $todo->image;
                    $todo->image = $filename;
                    $todo->save();             
                    
                    if($old_image != '' && Storage::disk('public')->exists('todo_images/' . $old_image)) {
                        Storage::disk('public')->delete('todo_images/' . $old_image);
                    }
                }
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
            $old_image = $todo->image;
            if($todo->delete()) {
                
                if($old_image != '' && Storage::disk('public')->exists('todo_images/' . $old_image)) {
                    Storage::disk('public')->delete('todo_images/' . $old_image);
                }

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
