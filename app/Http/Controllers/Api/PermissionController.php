<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Validator;

class PermissionController extends Controller
{
    //get all permissions

    public function index(){
        $permissions = Permission::orderBy('id', 'desc')->get();
        if($permissions){
            return response()->json(['permissions' => $permissions, 'status' => 200]);
        } else {
            return response()->json(['error' => 'no permission found!', 'status' => 403]);
        }

    }

    // create new permission
    public function store(Request $request){
        // check validation rules from getValidationRules method
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }else{
            // creating new permission
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->guard_name = 'api';
            $permission->save();

            return response()->json(['success' => 'permission added successfully', 'status' => 200]);
        }
    }

    // update a permission
    public function update(Request $request, $id){
        // check validation rules from getValidationRules method
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }else{
            // creating new permission
            $permission = Permission::find($id);
            if($permission){
                $permission->name = $request->name;
                $permission->save();

                return response()->json(['success' => 'permission updated successfully', 'status' => 200]);
            } else {
                return response()->json(['error' => 'no permission found with this id', 'status' => 403]);
            }

        }
    }



    // getValidationRules is used for validate requiest data
    private function getValidationRules($isNew = true)
    {
        return [
            'name' => 'required|string',
        ];
    }
}
