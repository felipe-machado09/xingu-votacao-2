<?php

namespace App\Http\Controllers;

use App\Models\Audience;
use Illuminate\Http\Request;

class MagicLinkController extends Controller
{
    public function authenticate(Request $request, Audience $audience)
    {
        if (!$request->hasValidSignature()) {
            abort(401, 'Link inválido ou expirado.');
        }

        $audience->markEmailAsVerified();

        session([
            'audience_id' => $audience->id,
            'audience_name' => $audience->name,
        ]);

        return redirect()->route('vote.index')->with('success', 'Bem-vindo de volta!');
    }
}
