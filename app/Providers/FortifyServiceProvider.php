<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Contracts\LogoutResponse;
use SweetAlert2\Laravel\Traits\WithSweetAlert;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {

        public function toResponse($request)
            {

                return redirect('/');

            }

        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Redirection personnalisée après logout
        $this->app->singleton(LogoutResponse::class, function () {
            return new class implements LogoutResponse {
                public function toResponse($request)
                {
                     Swal::success([
                            'title'  => "Déconnexion réussie",
                        ]);
                    // vers la route nommée 'login' (qui montre la vue auth.login)
                    return redirect()->route('login');
                }
            };
        });
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn($request) => view('auth.reset-password', ['request' => $request]));
        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::authenticateUsing(function (Request $request) {

        
        $user = User::where('email', $request->email_phone)
        ->where('is_actif',1)
        ->Orwhere('phone', $request->email_phone)
        ->where('is_actif',1)
        ->first();
        
        if ($user &&

            Hash::check($request->password, $user->password)) {
            Swal::success([
                'title'  => "Connexion réussie",
                    'text'   => 'Bienvenu! '.$user->lastname.' '.$user->firstname,
            ]);
            return $user;

        }

        Swal::error([
            'title'  => "Echec d'authentification",
                'text'   => 'E-mail (ex: xxx@xxx.xx ou 242xxxxxxx) ou mot de passe incorrecte ',
        ]);

       
    });
        
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
