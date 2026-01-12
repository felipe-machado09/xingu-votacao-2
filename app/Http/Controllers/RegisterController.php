<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Mail\MagicLinkMail;
use App\Models\Audience;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $audience = Audience::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
            ]
        );

        $this->sendMagicLink($audience);

        return redirect()->route('home')->with('success', 'Verifique seu e-mail para o link de acesso.');
    }

    protected function sendMagicLink(Audience $audience): void
    {
        $url = URL::temporarySignedRoute(
            'auth.magic',
            now()->addMinutes(30),
            ['audience' => $audience->id]
        );

        Mail::to($audience->email)->send(new MagicLinkMail($url));
    }
}
