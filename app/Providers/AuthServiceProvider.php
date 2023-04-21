<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->email == 'dreyes@exponentedigital.mx' ?? null;
        });

        // Define la habilidad "exportar ventas"
        Gate::define('exportar-ventas', function ($user) {
            return in_array($user->getRoleNames()->first(), ['Supervisor', 'Coordinador', 'Administrador']);
        });
        
        // Define la habilidad Ver campos Agente de ventas
        Gate::define('agente-ventas', function ($user) {
            return in_array($user->getRoleNames()->first(), ['Agente Ventas Nuevas', 'Administrador']);
        });

        // Define la habilidad Ver campos
        Gate::define('ver-campos-botones', function ($user) {
            return in_array($user->getRoleNames()->first(), ['Supervisor', 'Coordinador', 'Administrador']);
        });
    }
}
