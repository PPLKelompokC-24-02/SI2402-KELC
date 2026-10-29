<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerCategory;
use App\Models\Competency;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

class MasterDataController extends Controller
{
    public function categories(): JsonResponse
    {
        return response()->json(['data' => CareerCategory::orderBy('name')->get(['id', 'name', 'description'])]);
    }

    public function competencies(): JsonResponse
    {
        return response()->json(['data' => Competency::orderBy('name')->get(['id', 'name', 'description'])]);
    }

    public function roles(): JsonResponse
    {
        return response()->json(['data' => Role::orderBy('id')->get(['id', 'name'])]);
    }
}
