<?php

namespace Picobaz\GridView\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

/**
 * GridViewController - Handle inline updates and bulk actions
 *
 * @author PicoBaz <picobaz3@gmail.com>
 * @package Picobaz\GridView\Controllers
 */
class GridViewController extends Controller
{
    /**
     * Handle inline update request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inlineUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pk' => 'required',
            'attribute' => 'required|string',
            'value' => 'nullable',
            'model' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $modelClass = $request->input('model');

            // Security check: Validate model class
            if (!class_exists($modelClass)) {
                throw new \Exception('Invalid model class');
            }

            $model = $modelClass::findOrFail($request->input('pk'));
            $attribute = $request->input('attribute');

            // Check if attribute is fillable
            if (!$model->isFillable($attribute)) {
                throw new \Exception('Attribute is not fillable');
            }

            $model->$attribute = $request->input('value');
            $model->save();

            return response()->json([
                'success' => true,
                'message' => 'Updated successfully',
                'data' => [
                    'id' => $model->id,
                    'attribute' => $attribute,
                    'value' => $model->$attribute
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle bulk action request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'required',
            'model' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $modelClass = $request->input('model');

            // Security check
            if (!class_exists($modelClass)) {
                throw new \Exception('Invalid model class');
            }

            $action = $request->input('action');
            $ids = $request->input('ids');

            $count = 0;

            switch ($action) {
                case 'delete':
                    $count = $modelClass::whereIn('id', $ids)->delete();
                    break;

                case 'activate':
                    $count = $modelClass::whereIn('id', $ids)->update(['status' => 'active']);
                    break;

                case 'deactivate':
                    $count = $modelClass::whereIn('id', $ids)->update(['status' => 'inactive']);
                    break;

                default:
                    // Allow custom actions via events
                    event('gridview.bulk.action.' . $action, [$modelClass, $ids]);
                    $count = count($ids);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => ucfirst($action) . ' completed successfully',
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}