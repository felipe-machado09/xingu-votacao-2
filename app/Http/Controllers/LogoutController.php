<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->session()->forget(['audience_id', 'audience_name']);
        $request->session()->flush();

        return redirect()->route('home')->with('success', 'Você foi desconectado.');
    }
}
