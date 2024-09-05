<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

use Exception;

class UserController extends Controller
{
    # function to create user
    public function createUser(Request $request){
        try{
            // Validation
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string',
                'email' => 'required|string|unique:users',
                'phone' => 'required|numeric|digits:11',
                'password' => 'required|min:6'
            ]);

            if( $validator->fails() ){
                $result = array('status' => false, 'message' => 'Validation Error Occured.', 'error_message' => $validator->errors());
                return response()->json($result, 400); // Bad Request
            }
            
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => bcrypt($request->password),
            ]);

            if( $user->id ){
                $result = array('status' => true, 'message' => 'User Created Successfully', 'data' => $user);
                $response_code = 200; // Success Request
            } else{
                $result = array('status' => false, 'message' => 'Something went wrong');
                $response_code = 400; // Bad Request
            }

            return response()->json($result, $response_code);
        } catch (Exception $e){
            $result = array( 'status' => false, 'message' => 'API failed due to an error', 'error' => $e->getMessage() );
            return response()->json($result, 400);
        }
    }
    # function to return all users
    public function getUsers(){
        $users = User::all();

        $result = array('success' => true, 'message' => count($users) . ' user(s) fetched.', 'data' => $users);
        $response_code = 200; // Success

        return response()->json($result, $response_code);
    }
    # function to return User Details
    public function getUserDetail($id){
        try{
            $user = User::find($id);

            if( !$user ){
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $result = array( 'success' => true, 'message' => 'User Found', 'data' => $user );
            $response_code = 200; // Success
            return response()->json($result, $response_code);
        } catch (Exception $e){
            $result = array( 'status' => false, 'message' => 'API failed due to an error', 'error' => $e->getMessage() );
            return response()->json($result, 400);
        }        
    }
    # function to update the User details
    public function updateUser(Request $request, $id){
        try{
            $user = User::find($id);

            if( !$user ){
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }
    
            // Validation
            $validator = Validator::make( $request->all(), [
                'name'  => 'required|string',
                'email' => 'required|string|unique:users,email,' . $id,
                'phone' => 'required|numeric|digits:11',
            ]);
    
            if( $validator->fails() ){
                $result = array('status' => false, 'message' => 'Validation Error Occured.', 'error_message' => $validator->errors());
                return response()->json($result, 400); // Bad Request
            }
    
            // Update code
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
    
            $result = array('status' => true, 'message' => 'User has been updated Successfully', 'data' => $user);
            $response_code = 200; // Success Request
    
            return response()->json($result, $response_code);
        } catch (Exception $e) {
            $result = array('status' => false, 'message' => 'API failed due to an error', 'error' => $e->getMessage());
            return response()->json($result, 400);
        }
        
    }
    # function to delete the user
    public function deleteUser($id){
        try{
            $user = User::find($id);
            if( !$user ){
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $user->delete();

            $result = array('status' => true, 'message' => 'User has been deleted Successfully', 'user_id' => $id);
            $response_code = 200; // Success Request
    
            return response()->json($result, $response_code);
        } catch (Exception $e){
            $result = array( 'status' => false, 'message' => 'API failed due to an error', 'error' => $e->getMessage() );
            return response()->json($result, 400);
        }
    }
}
