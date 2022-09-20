<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoleHasPermission;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Validator;

class RoleHasPermissionController extends Controller
{

    // create new role has permission
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
            $permissions = $request->permissions;
            foreach($permissions as $permission){
                // creating new permission for role
                $roleHasPermission = new RoleHasPermission();
                $roleHasPermission->role_id = $request->role_id;
                $roleHasPermission->permission_id = $permission;
                $roleHasPermission->save();
            }


            return response()->json(['success' => 'permissions added successfully for the role', 'status' => 200]);
        }
    }

    // update a permission for role
    public function update(Request $request, $role_id){
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
            $role = Role::where('id', $role_id)->first();
            if($role){
                $permissionIds = $request->get('permissions', []);
                $rolePermissions = RoleHasPermission::where('role_id', $role->id)->get();
                $rolePermissionIds = array_map(
                    function($permission){
                        return $permission['permission_id'];
                    },
                    $rolePermissions->toArray()
                );
                $newPermissionIds = array_diff($permissionIds, $rolePermissionIds);
                $oldPermissionIds = array_diff($rolePermissionIds, $permissionIds);
                foreach($oldPermissionIds as $oldId){
                    $rolePermission = RoleHasPermission::where('role_id', $role->id)->where('permission_id', $oldId)->delete();
                }
                foreach($newPermissionIds as $newId){
                    $new = new RoleHasPermission();
                    $new->role_id = $role->id;
                    $new->permission_id = $newId;
                    $new->save();
                }
                return response()->json(['success' => 'permission updated successfully for the role', 'status' => 200]);
            } else {
                return response()->json(['error' => 'no permission found with this role', 'status' => 403]);
            }

        }
    }



    // getValidationRules is used for validate requiest data
    private function getValidationRules($isNew = true)
    {
        return [
            'role_id' => 'required|numeric',
        ];
    }
}
