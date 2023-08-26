<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\C_RolesRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    
    
    public function __construct()
    {
        return;
    }

    public function changeRole(C_RolesRequest $req): JsonResponse
    {
        // create category linked to company
        $user = User::findOrFail($req->validated()['user_id']);
        if ($user)
        {
            $req['from'] ? $user->removeRole($req->validated()['from']): null;
            $user->assignRole($req->validated()['role']);
            return response()->json(['success', 'role has been changed to '.$req->validated()['role']]);
        }
        return response()->json(['failed', 'Check if user exist then try again']);
    }

    public function roles(User $user): JsonResponse
    {
        $r = $user->getRoleNames();
        return response()->json($r ?? ['failed'], 500);
    }
}
