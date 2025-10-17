<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use App\Helpers\TranslationHelper;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    // public function handle($request, Closure $next)
    // {
    //     if (session()->has('locale')) {
    //         app()->setLocale(session('locale'));
    //     }
    //     return $next($request);
    // }

    public function handle($request, Closure $next)
    {
        // Assurez-vous que le répertoire de logs existe
        if (!File::exists(storage_path('logs'))) {
            File::makeDirectory(storage_path('logs'), 0755, true);
        }
        
        // Écrire directement dans le fichier de log
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Middleware SetLocale appelé - URL: " . $request->fullUrl() . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Langues supportées
        $supportedLocales = ['fr', 'en', 'de'];
        $defaultLocale = 'fr';
        
        $locale = null;
        
        // Vérifier si la langue est spécifiée dans l'URL (priorité la plus haute)
        if ($request->segment(1) === 'lang' && $request->segment(2) && in_array($request->segment(2), $supportedLocales)) {
            $locale = $request->segment(2);
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Locale trouvée dans l'URL: " . $locale . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        // 1. Ensuite, vérifier la session car elle est prioritaire après l'URL
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Locale trouvée dans la session: " . $locale . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        // 2. Ensuite, vérifier le cookie
        elseif ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Locale trouvée dans le cookie: " . $locale . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        
        // Vérifier si la locale est valide, sinon utiliser la locale par défaut
        if (!in_array($locale, $supportedLocales)) {
            $locale = $defaultLocale;
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Locale non valide ou non définie, utilisation de la locale par défaut: " . $locale . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        
        // IMPORTANT: Définir la locale dans l'application avant tout
        App::setLocale($locale);
        
        // Synchroniser la session
        Session::put('locale', $locale);
        
        // Forcer la persistance de la session
        Session::save();
        
        // Définir un cookie qui expire dans 1 an (si pas déjà défini ou différent)
        if (!$request->hasCookie('locale') || $request->cookie('locale') !== $locale) {
            Cookie::queue('locale', $locale, 60 * 24 * 365);
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Cookie locale défini/mis à jour: " . $locale . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
        
        // Forcer le chargement des traductions
        $this->loadTranslations($locale);
        
        // Ajouter la locale actuelle au log pour déboguer
        $logMessage = "[" . date('Y-m-d H:i:s') . "] App::getLocale(): " . App::getLocale() . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Ajouter la locale de session au log pour déboguer
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Session locale: " . Session::get('locale', 'non définie') . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Vérifier si le cookie est bien défini
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Cookie locale: " . ($request->hasCookie('locale') ? $request->cookie('locale') : 'non défini') . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Obtenir la réponse
        $response = $next($request);
        
        // Si c'est une réponse HTML, vérifier que la langue est bien définie dans le HTML
        if (is_object($response) && method_exists($response, 'getContent')) {
            $content = $response->getContent();
            
            // Si c'est une réponse HTML, essayer de remplacer l'attribut lang
            if (is_string($content) && strpos($content, '<html') !== false) {
                // Remplacer l'attribut lang avec la locale actuelle
                $content = preg_replace('/<html[^>]*lang=["\']([^"\']*)["\'][^>]*>/', '<html lang="' . App::getLocale() . '">', $content);
                
                // Mettre à jour le contenu de la réponse
                $response->setContent($content);
                
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Attribut HTML lang mis à jour avec: " . App::getLocale() . "\n";
                File::append(storage_path('logs/locale.log'), $logMessage);
            }
        }
        
        return $response;
    }
    
    /**
     * Force le chargement des traductions pour une langue donnée.
     *
     * @param string $locale
     * @return void
     */
    protected function loadTranslations($locale)
    {
        $path = base_path('lang/' . $locale . '.json');
        
        if (File::exists($path)) {
            $translations = json_decode(File::get($path), true);
            
            // Log pour déboguer
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Chargement des traductions pour " . $locale . ", nombre de traductions: " . count($translations) . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
            
            // Tester quelques traductions
            if (isset($translations['Home'])) {
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Traduction 'Home' en " . $locale . ": " . $translations['Home'] . "\n";
                File::append(storage_path('logs/locale.log'), $logMessage);
            }
        } else {
            $logMessage = "[" . date('Y-m-d H:i:s') . "] Fichier de traduction non trouvé pour " . $locale . " à " . $path . "\n";
            File::append(storage_path('logs/locale.log'), $logMessage);
        }
    }
}
