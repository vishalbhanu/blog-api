<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\helpers;
use Config;
use Log;

class LoginController extends Controller
{
    public function signUp(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();

        $current_Date = Date('Y-m-d H:i:s');
        if(isset($input) && !empty($input)){
          $validatedData = \Validator::make($input, Config::get('validate.SIGNUP_VALIDATION'), Config::get('validate.SIGNUP_MESSAGE'));
          $errors = array();
          if ($validatedData->fails()) {
              foreach ($validatedData->messages()->getMessages() as $field_name => $messages) {
                  $errors = $messages[0];
              }
              $message = $errors;
              $status = false;
          }else{
            $insertData = new User;
            $insertData->name = $input['name'];
            $insertData->email = $input['email'];
    				$insertData->password = $input['password'];
    				$insertData->created_at = $current_Date;
    				$insertData->updated_at = $current_Date;
    				$insertData->save();

            $message = "Sign Up successfully.";
            $status = true;
          }
          }else{
            $message = "Please check input data.";
            $status = false;
          }
        $response = response()->json(['status' => $status, 'data' => $message]);

        return $response;
      }catch (\Exception $e) {
        if ($e->getCode() == 23000) {
            $message = "Email id already exists. please try again with another email id.";
         }else{
           $message = $e->getMessage();
         }
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
        $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function login(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();
        if(isset($input) && !empty($input)){
            $users = new User;
            $login = $users::where('email',$input['email'])->where('password',$input['password'])->get()->toArray();
            if(isset($login) && !empty($login)){
              $message = "login Successful";
              $status = true;
            }else{
              $message ="Login Failed";
              $status = false;
            }
        }else{
          $message = "Please check input data.";
          $status = false;
        }
        $response = response()->json(['status' => $status, 'data' => $message]);
        return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function createPost(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();
        $current_Date = Date('Y-m-d H:i:s');

        if(isset($input) && !empty($input)){
          $output = \DB::table('blog_post')->insert(
            [
              'title' => $input['title'],
              'sub_title' => $input['sub_title'],
              'tags' =>  $input['tags'],
              'content' =>  $input['content'],
              'created_at' => $current_Date,
              'updated_at' =>  $current_Date
            ]);
            if($output == 1){
              $message = "Post Created Successfully.";
              $status = true;
            }else{
              $message = "Something went Wrong with api.";
              $status = false;
            }
        }else{
          $message = "Please check input data.";
          $status = false;
        }
         $response = response()->json(['status' => $status, 'data' => $message]);
         return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function updatePost(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();
        if(isset($input) && !empty($input)){
          $output = \DB::table('blog_post')
          ->where('id', $request['id'])
          ->update(['title' => $input['title']]);
          if($output == 1){
            $message = "Post Updated Successfully.";
            $status = true;
          }else{
            $message = "Something went Wrong with api.";
            $status = false;
          }
        }else{
          $message = "Please check input data.";
          $status = false;
        }
         $response = response()->json(['status' => $status, 'data' => $message]);
         return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function deletePost(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();
        if(isset($input) && !empty($input)){
          $output = \DB::table('blog_post')
          ->where('id', $request['id'])->delete();
          if($output == 1){
            $message = "Post Deleted Successfully.";
            $status = true;
          }else{
            $message = "Something went Wrong with api.";
            $status = false;
          }
        }else{
          $message = "Please check input data.";
          $status = false;
        }
         $response = response()->json(['status' => $status, 'data' => $message]);
         return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function getPost(){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
          $output = \DB::table('blog_post')->select()->get()->toArray();
          if(isset($output) && !empty($output) && $output !== ''){
            $message = $output;
            $status = true;
          }else{
            $message = "No data found.";
            $status = true;
          }
         $response = response()->json(['status' => $status, 'data' => $message]);
         return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }

    public function getPostsByTag(Request $request){
      try{
        Log::debug(__METHOD__ .  ' Entered in ' .__FUNCTION__ . ' function');
        $input = $request->all();
        if(isset($input) && !empty($input)){
          $output = \DB::table('blog_post')->select()->where('tags',$input['tag'])->get()->toArray();
          if(isset($output) && !empty($output) && $output !== ''){
            $message = $output;
            $status = true;
          }else{
            $message = "No data found.";
            $status = true;
          }
        }else{
          $message = "Please check input data.";
          $status = false;
        }
         $response = response()->json(['status' => $status, 'data' => $message]);
         return $response;
       }catch (Exception $e) {
         $status = false;
         $message = $e->getMessage();
         Log::error(' [LoginController] '.' ['.__FUNCTION__.'] '.$message);
         $response = response()->json(['status' => false, 'data' => $message]);
         return $response;
      }
    }
}
