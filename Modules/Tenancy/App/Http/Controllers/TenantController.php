<?php

declare(strict_types=1);

namespace Modules\Tenancy\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Tenancy\App\Http\Requests\StoreTenantRequest;
use Modules\Tenancy\App\Http\Requests\UpdateTenantRequest;
use Modules\Tenancy\App\Http\Requests\StoreTenantDomainRequest;
use Modules\Tenancy\App\Models\Tenant;
use Modules\Tenancy\App\Models\TenantDomain;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tenants = Tenant::with('domains')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tenancy::tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tenant = new Tenant();
        return view('tenancy::tenants.form', compact('tenant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Gerar slug automaticamente se não fornecido
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Decodificar JSON de configurações se fornecido
        if (isset($validated['settings']) && is_string($validated['settings'])) {
            $validated['settings'] = json_decode($validated['settings'], true);
        }

        // Criar tenant com dados no formato correto
        $tenant = Tenant::create([
            'id' => (string) Str::ulid(),
            'data' => [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'status' => $validated['status'],
                'settings' => $validated['settings'] ?? null,
            ],
        ]);

        // Criar domínio padrão automaticamente
        $defaultDomain = $validated['slug'] . '.localhost';
        $tenant->domains()->create([
            'domain' => $defaultDomain,
        ]);

        return redirect()
            ->route('superadmin.tenants.index')
            ->with('success', 'Unidade criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant): View
    {
        $tenant->load('domains');
        return view('tenancy::tenants.form', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validated();
        
        // Gerar slug automaticamente se não fornecido
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Decodificar JSON de configurações se fornecido
        if (isset($validated['settings']) && is_string($validated['settings'])) {
            $validated['settings'] = json_decode($validated['settings'], true);
        }

        // Atualizar dados do tenant no formato correto
        $tenant->update([
            'data' => [
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'status' => $validated['status'],
                'settings' => $validated['settings'] ?? null,
            ],
        ]);

        return redirect()
            ->route('superadmin.tenants.index')
            ->with('success', 'Unidade atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string|Tenant $tenant): RedirectResponse
    {
        try {
            // Se recebeu string, buscar o tenant
            if (is_string($tenant)) {
                $tenant = Tenant::findOrFail($tenant);
            }

            $tenantName = $tenant->getName();
            
            // Deletar domínios relacionados
            $tenant->domains()->delete();
            
            // Deletar o tenant
            $tenant->delete();

            return redirect()
                ->route('superadmin.tenants.index')
                ->with('success', "Unidade '{$tenantName}' excluída com sucesso!");
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir tenant', [
                'tenant' => is_object($tenant) ? $tenant->id : $tenant,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()
                ->route('superadmin.tenants.index')
                ->with('error', 'Erro ao excluir unidade: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created domain for the tenant.
     */
    public function storeDomain(StoreTenantDomainRequest $request, Tenant $tenant): RedirectResponse
    {
        $tenant->domains()->create($request->validated());

        return redirect()
            ->route('superadmin.tenants.index')
            ->with('success', 'Domínio adicionado com sucesso!');
    }

    /**
     * Remove the specified domain from storage.
     */
    public function destroyDomain(Tenant $tenant, TenantDomain $domain): RedirectResponse
    {
        try {
            $domain->delete();

            return redirect()
                ->route('superadmin.tenants.index')
                ->with('success', 'Domínio removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->route('superadmin.tenants.index')
                ->with('error', 'Erro ao remover domínio: ' . $e->getMessage());
        }
    }
}
