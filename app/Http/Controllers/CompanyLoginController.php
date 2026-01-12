<?php

namespace App\Http\Controllers;

use App\Mail\CompanyCodeMail;
use App\Models\Company;
use App\Models\CompanyCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CompanyLoginController extends Controller
{
    public function show()
    {
        return view('company.login');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:companies,email'],
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.exists' => 'Este e-mail não está cadastrado.',
        ]);

        $company = Company::where('email', $request->email)->first();

        if (!$company) {
            return back()->withErrors(['email' => 'Este e-mail não está cadastrado.']);
        }

        // Gerar código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidar códigos anteriores não usados
        CompanyCode::where('company_id', $company->id)
            ->where('used', false)
            ->update(['used' => true]);

        // Criar novo código
        CompanyCode::create([
            'company_id' => $company->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'used' => false,
        ]);

        // Enviar e-mail
        Mail::to($company->email)->send(new CompanyCodeMail($code, $company->legal_name));

        return back()->with('code_sent', 'Código enviado para seu e-mail! Verifique sua caixa de entrada.');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:6'],
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'code.required' => 'O código é obrigatório.',
            'code.size' => 'O código deve ter 6 dígitos.',
        ]);

        $company = Company::where('email', $request->email)->first();

        if (!$company) {
            return back()->withErrors(['code' => 'E-mail ou código inválido.']);
        }

        $companyCode = CompanyCode::where('company_id', $company->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$companyCode) {
            return back()->withErrors(['code' => 'Código inválido ou expirado.']);
        }

        // Marcar código como usado
        $companyCode->update(['used' => true]);

        // Criar sessão
        session(['company_id' => $company->id]);

        return redirect()->route('home')
            ->with('success', 'Login realizado com sucesso!');
    }
}
