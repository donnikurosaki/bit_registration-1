<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class LanguageController extends Controller
{
    /**
     * Langues supportées par l'application
     */
    protected $supportedLocales = ['fr', 'en', 'de'];
    
    /**
     * Langue par défaut
     */
    protected $defaultLocale = 'fr';
    
    /**
     * Change la langue via une requête GET
     */
    public function switch($lang)
    {
        // Assurez-vous que le répertoire de logs existe
        if (!File::exists(storage_path('logs'))) {
            File::makeDirectory(storage_path('logs'), 0755, true);
        }
        
        // Écrire directement dans le fichier de log
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Switch langue appelé avec: " . $lang . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vérifier si la langue est supportée
        if (!in_array($lang, $this->supportedLocales)) {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Langue non supportée: " . $lang . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
            abort(400);
        }
        
        // FORCER la langue dans l'application
        App::setLocale($lang);
        Config::set('app.locale', $lang);
        
        // Définir la langue dans la session et forcer la sauvegarde
        Session::put('locale', $lang);
        Session::save(); // Force la sauvegarde immédiate de la session
        
        // Définir un cookie qui expire dans 1 an
        $cookie = Cookie::make('locale', $lang, 60 * 24 * 365);
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Langue définie avec succès à: " . $lang . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vider le cache pour s'assurer que les changements sont appliqués
        try {
            Artisan::call('view:clear');
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Cache des vues vidé\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        } catch (\Exception $e) {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Erreur lors du vidage du cache: " . $e->getMessage() . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        
        // Rediriger vers la page précédente avec le cookie
        return redirect()->back()->withCookie($cookie);
    }

    /**
     * Change la langue via une requête POST
     */
    public function switchPost(Request $request)
    {
        $lang = $request->input('locale');
        
        // Assurez-vous que le répertoire de logs existe
        if (!File::exists(storage_path('logs'))) {
            File::makeDirectory(storage_path('logs'), 0755, true);
        }
        
        // Écrire directement dans le fichier de log
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Switch langue (POST) appelé avec: " . $lang . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vérifier si la langue est supportée
        if (!in_array($lang, $this->supportedLocales)) {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Langue non supportée: " . $lang . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
            abort(400);
        }
        
        // FORCER la langue dans l'application
        App::setLocale($lang);
        Config::set('app.locale', $lang);
        
        // Définir la langue dans la session et forcer la sauvegarde
        Session::put('locale', $lang);
        Session::save(); // Force la sauvegarde immédiate de la session
        
        // Définir un cookie qui expire dans 1 an
        $cookie = Cookie::make('locale', $lang, 60 * 24 * 365);
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Langue définie avec succès à: " . $lang . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vider le cache pour s'assurer que les changements sont appliqués
        try {
            Artisan::call('view:clear');
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Cache des vues vidé\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        } catch (\Exception $e) {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Erreur lors du vidage du cache: " . $e->getMessage() . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        
        // Rediriger vers la page précédente avec le cookie et forcer le rechargement
        return redirect()->back()->withCookie($cookie);
    }
}
