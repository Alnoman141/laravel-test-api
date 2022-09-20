<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelHasRole;
use Validator;

class ModelHasRoleController extends Controller
{
    // create model has role
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
            $modelRole = new ModelHasRole();
            $modelRole->model_id = $request->model_id;
            $modelRole->role_id = $request->role_id;
            $modelRole->model_type = "App\Models\User";
            $modelRole->save();

            return response()->json(['success' => 'Role has been assigned for the model','status' => 200]);
        }
    }

    // update nmodel has role
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
            $modelRole = ModelHasRole::where('model_id', $model_id)->update($request->all());

            return response()->json(['success' => 'Role has been updated for the model','status' => 200]);
        }
    }


    // getValidationRules is used for validate requiest data
    private function getValidationRules($isNew = true)
    {
        return [
            'role_id' => 'required|numeric',
            'model_id' => 'required|numeric',
        ];
    }
}
