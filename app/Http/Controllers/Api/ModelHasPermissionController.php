<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelHasPermission;
use App\Models\User;
use Validator;

class ModelHasPermissionController extends Controller
{
    // create new model has permission
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
                // creating new permission for model
                $modelHasPermission = new ModelHasPermission();
                $modelHasPermission->model_id = $request->model_id;
                $modelHasPermission->permission_id = $permission;
                $modelHasPermission->model_type = "App\Models\User";
                $modelHasPermission->save();
            }


            return response()->json(['success' => 'permissions added successfully for the model', 'status' => 200]);
        }
    }

    // update a permission for model
    public function update(Request $request, $model_id){
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
            $model = User::where('id', $model_id)->first();
            if($model){
                $permissionIds = $request->get('permissions', []);
                $modelPermissions = ModelHasPermission::where('model_id', $model->id)->get();
                $modelPermissionIds = array_map(
                    function($permission){
                        return $permission['permission_id'];
                    },
                    $modelPermissions->toArray()
                );
                $newPermissionIds = array_diff($permissionIds, $modelPermissionIds);
                $oldPermissionIds = array_diff($modelPermissionIds, $permissionIds);
                foreach($oldPermissionIds as $oldId){
                    $modelPermission = ModelHasPermission::where('model_id', $model->id)->where('permission_id', $oldId)->delete();
                }
                foreach($newPermissionIds as $newId){
                    $new = new ModelHasPermission();
                    $new->model_id = $model->id;
                    $new->permission_id = $newId;
                    $new->model_type = "App\Models\User";
                    $new->save();
                }
                return response()->json(['success' => 'permission updated successfully for the model', 'status' => 200]);
            } else {
                return response()->json(['error' => 'no permission found with this model', 'status' => 403]);
            }

        }
    }

    // getValidationRules is used for validate requiest data
    private function getValidationRules($isNew = true)
    {
        return [
            'model_id' => 'required|numeric',
        ];
    }
}
