<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * As políticas de autorização da aplicação.
     *
     * @var array
     */
    protected $policies = [
        // Defina suas políticas aqui, se necessário
    ];

    /**
     * Registra os serviços de autenticação e autorização.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Registra as rotas do Passport
        Passport::routes();
    }
}

