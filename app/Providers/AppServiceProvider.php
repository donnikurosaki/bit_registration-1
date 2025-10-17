<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer la langue au démarrage
        $this->setApplicationLocale();
        
        View::share('programs', [
            'informatique' => 'Licence Informatique et Entrepreunariat',
            'mecanique agriculture' => 'Licence Mecanique Option Agriculture',
            'mecanique mine' => 'Licence Mecanique Option Mine',
            'energie' => 'Licence Energie Renouvelable',
            'master' => 'Master Informatique Option Intelligence Artificielle',
        ]);
    }
    
    /**
     * Set the application locale based on session or cookie.
     */
    protected function setApplicationLocale(): void
    {
        // Assurez-vous que le répertoire de logs existe
        if (!File::exists(storage_path('logs'))) {
            File::makeDirectory(storage_path('logs'), 0755, true);
        }
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] AppServiceProvider::setApplicationLocale appelé\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vérifier d'abord la session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            
            if (in_array($locale, ['fr', 'en', 'de'])) {
                App::setLocale($locale);
                $logMessage = "[" . date('Y-m-d H:i:s') . "] AppServiceProvider: Langue définie depuis la session: " . $locale . "\n";
                File::append(storage_path('logs/locale.log'), $logMessage);
            }
        } 
        // Sinon, utiliser la langue par défaut
        else {
            App::setLocale('fr');
            $logMessage = "[" . date('Y-m-d H:i:s') . "] AppServiceProvider: Langue par défaut (fr) utilisée\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
    }
}
