<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Mail\MagicLinkMail;
use App\Models\Audience;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $audience = Audience::where('email', $request->email)->first();

        $url = URL::temporarySignedRoute(
            'auth.magic',
            now()->addMinutes(30),
            ['audience' => $audience->id]
        );

        Mail::to($audience->email)->send(new MagicLinkMail($url));

        return redirect()->route('home')->with('success', 'Verifique seu e-mail para o link de acesso.');
    }
}
