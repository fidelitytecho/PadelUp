<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function endorse(Request $req)
    {
        $endorser = auth()->id();
        $request = $req->validate([
            'user_id' => 'required|integer|exists:users,id',
            'endorse' => 'required|boolean'
        ]);
        $skill = User::find($endorser)->endorse()->where('user_id', $request['user_id']);
        if ($skill->get()->isEmpty())
        {
            $s = Skill::create([
                'user_id' => $request['user_id'],
                'endorser_id' => $endorser,
                'endorse' => $request['endorse']
                ]);
            return response()->json([$s] ? 'Sucessfully Endorsed' : 'Endorsement Failed', 200);
        }else {
            $s = $skill->update([
                'user_id' => $request['user_id'],
                'endorser_id' => $endorser,
                'endorse' => $request['endorse']
                ]);
            return response()->json([$s] ? 'Sucessfully Updated Endorsed' : 'Endorsement Failed', 200);
        }
    }

    public function endorsed()
    {
        $endorser = auth()->id();
        $endorsed_users = [];
        $s = Skill::where('endorser_id', $endorser)->get();
        foreach($s as $v)
        {
            array_push($endorsed_users,['player_id' => $v['user_id'], 'endorse' => $v['endorse']]);
        }
        return response()->json($endorsed_users, 200);
    }
}
