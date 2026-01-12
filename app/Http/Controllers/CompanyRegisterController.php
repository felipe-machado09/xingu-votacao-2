<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRegisterRequest;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyRegisterController extends Controller
{
    public function show()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('category_group')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($category) {
                return $category->category_group ?? 'Outras';
            });
        
        return view('company.register', compact('categories'));
    }

    public function store(CompanyRegisterRequest $request)
    {
        // Limpar CNPJ, telefone e CEP
        $cnpj = preg_replace('/\D/', '', $request->cnpj);
        $telefone = preg_replace('/\D/', '', $request->telefone);
        $whatsapp = $request->whatsapp_number ? preg_replace('/\D/', '', $request->whatsapp_number) : null;
        $zipcode = preg_replace('/\D/', '', $request->address_zipcode);

        // Criar empresa
        $company = Company::create([
            'legal_name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'cnpj' => $cnpj,
            'responsible_name' => $request->name, // Pode ser ajustado depois
            'responsible_phone' => $telefone,
            'telefone' => $telefone,
            'whatsapp_number' => $whatsapp,
            'address_street' => $request->address_street,
            'address_number' => $request->address_number,
            'address_complement' => $request->address_complement,
            'address_neighborhood' => $request->address_neighborhood,
            'address_city' => $request->address_city,
            'address_state' => strtoupper($request->address_state),
            'address_zipcode' => $zipcode,
            'lgpd_accepted' => true,
            'role_name' => 'Empresa',
            'registration_complete' => false,
        ]);

        // Upload do logo se fornecido
        if ($request->hasFile('logo_path')) {
            $logoPath = $request->file('logo_path')->store('company-logos', 'public');
            $company->logo_path = $logoPath;
            $company->save();
        }

        // Associar categorias
        $company->categories()->attach($request->categoria_id);

        // Login automático
        session(['company_id' => $company->id]);

        return redirect()->route('home')
            ->with('success', 'Cadastro realizado com sucesso! Complete seu perfil para liberar todas as funcionalidades.');
    }
}
