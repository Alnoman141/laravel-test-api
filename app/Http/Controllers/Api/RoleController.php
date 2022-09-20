<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    //get all roles

    public function index(){
        $roles = Role::orderBy('id', 'desc')->get();
        return response()->json(['roles' => $roles, 'status' => 200]);
    }

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
            // creating new role
            $role = new Role();
            $role->name = $request->name;
            $role->guard_name = 'api';
            $role->save();

            return response()->json(['success' => 'role added successfully', 'status' => 200]);
        }
    }

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
            // creating new role
            $role = Role::find($id);
            $role->name = $request->name;
            $role->save();

            return response()->json(['success' => 'role updated successfully', 'status' => 200]);
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
