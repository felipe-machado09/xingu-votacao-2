<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRegisterRequest;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyRegisterController extends Controller
{
    public function show()
    {
        abort_unless(\App\Models\LandingPageSection::campaignOption('company_registration_enabled'), 404);
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
        abort_unless(\App\Models\LandingPageSection::campaignOption('company_registration_enabled'), 404);
        $data = $request->validated();
        $logoPath = $request->file('logo_path')->store('company-logos', 'public');
        try {
            DB::transaction(function () use ($data, $logoPath) {
                $company = Company::create([
                    'trade_name' => $data['trade_name'],
                    'cnpj' => $data['cnpj'],
                    'whatsapp_number' => $data['whatsapp_number'],
                    'instagram_url' => $data['instagram_url'] ?? null,
                    'logo_path' => $logoPath,
                    'lgpd_accepted' => true,
                    'registration_complete' => true,
                ]);
                $company->categories()->attach($data['categoria_id']);
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($logoPath);
            throw $exception;
        }

        return redirect()->route('home')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }
}
