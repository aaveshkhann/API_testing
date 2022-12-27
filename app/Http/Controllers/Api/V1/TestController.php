<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\Validator;
use App\Models\Test;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = Test::all();
            return $data;
        }
        catch(ModelNotFoundException $exception){
         return response()->json(['error'=>$exception->getMessage()]);
        }
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
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'email' => 'required|string|max:255|email:rfc,dns|unique:users',
            'number' => 'required|max:10',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>$validator->errors()],401);
        }

        try {
            $test = Test::firstOrCreate([
                'first_name' => $request->first_name,
                'last_Name' => $request->last_name,
                'email' => $request->email,
                'number' => $request->number
            ]);

            if($test){
                return ["return "=>"data has been saved"];
            }
            else{
                return["return"=>"does not submit"];
            }

        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' =>$exception->getMessage()]);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test, $id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'string|max:25',
            'last_name' => 'string|max:25',
            'email' => 'string|max:25',
            'number' => 'string|max:25',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'=>$validator->errors()],401);
        }

      try{

           $test = Test::findOrFail($id)
            ->update();


           Cache::put('Test['. $id .']', $test, 15);

           if($test){
            return ["return "=>"your data update successfully"];
        }
      }

      catch( ModelNotFoundException $exception){
        return response()->json(['error' =>$exception->getMessage()]);
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
       try{ $count = Test::where('id',$id)->delete();

        if($count > 0 ){

            return response()->json(['message'=>'Successfully Deleted']);
        }

       }catch(ModelNotFoundException $exception){
            return response()->json(['error'=>$exception->getMessage()]);
       }
    }
}
