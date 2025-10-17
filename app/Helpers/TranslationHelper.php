<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class TranslationHelper
{
    /**
     * Cache des traductions par langue
     */
    protected static $translations = [];
    
    /**
     * Get translation for the given key.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public static function trans($key, array $replace = [], $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        // Log pour déboguer
        if (!File::exists(storage_path('logs'))) {
            File::makeDirectory(storage_path('logs'), 0755, true);
        }
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] TranslationHelper::trans appelé avec clé: " . $key . ", locale: " . $locale . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        // Charger les traductions depuis le cache ou le fichier
        if (!isset(self::$translations[$locale])) {
            self::$translations[$locale] = self::loadTranslationsForLocale($locale);
        }
        
        // Rechercher la traduction
        if (isset(self::$translations[$locale][$key])) {
            $translation = self::$translations[$locale][$key];
            
            // Remplacer les variables
            foreach ($replace as $k => $v) {
                $translation = str_replace(':' . $k, $v, $translation);
            }
            
            return $translation;
        }
        
        // Si la traduction n'existe pas dans la langue actuelle, essayer avec l'anglais
        if ($locale !== 'en') {
            if (!isset(self::$translations['en'])) {
                self::$translations['en'] = self::loadTranslationsForLocale('en');
            }
            
            if (isset(self::$translations['en'][$key])) {
                $translation = self::$translations['en'][$key];
                
                // Remplacer les variables
                foreach ($replace as $k => $v) {
                    $translation = str_replace(':' . $k, $v, $translation);
                }
                
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Traduction non trouvée en " . $locale . ", utilisation de l'anglais pour: " . $key . "\n";
                File::append(storage_path('logs/locale.log'), $logMessage);
                
                return $translation;
            }
        }
        
        // Fallback à la clé originale si aucune traduction n'est trouvée
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Aucune traduction trouvée pour: " . $key . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        return $key;
    }
    
    /**
     * Charge les traductions pour une locale donnée
     * 
     * @param string $locale
     * @return array
     */
    protected static function loadTranslationsForLocale($locale)
    {
        // Essayer de récupérer les traductions depuis le cache
        $cacheKey = 'translations_' . $locale;
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        // Sinon, charger depuis le fichier
        $path = base_path('lang/' . $locale . '.json');
        
        if (File::exists($path)) {
            $translations = json_decode(File::get($path), true);
            
            if (is_array($translations)) {
                // Stocker dans le cache pendant 1 heure
                Cache::put($cacheKey, $translations, 60 * 60);
                
                $logMessage = "[" . date('Y-m-d H:i:s') . "] Traductions chargées pour " . $locale . ": " . count($translations) . " entrées\n";
                File::append(storage_path('logs/locale.log'), $logMessage);
                
                return $translations;
            }
        }
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Fichier de traduction non trouvé ou invalide pour " . $locale . "\n";
        File::append(storage_path('logs/locale.log'), $logMessage);
        
        return [];
    }
    
    /**
     * Traduit une chaîne avec pluralisation
     *
     * @param string $key
     * @param int $count
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public static function transChoice($key, $count, array $replace = [], $locale = null)
    {
        $locale = $locale ?: App::getLocale();
        
        // Ajouter le compte aux remplacements
        $replace['count'] = $count;
        
        // Diviser la chaîne en parties singulier et pluriel
        $parts = explode('|', $key);
        
        if (count($parts) === 2) {
            $selected = ($count === 1) ? $parts[0] : $parts[1];
            return self::trans($selected, $replace, $locale);
        }
        
        // Si pas de format pluriel, utiliser la traduction normale
        return self::trans($key, $replace, $locale);
    }
}

/**
 * Fonction globale pour remplacer la fonction __() de Laravel.
 *
 * @param string $key
 * @param array $replace
 * @param string|null $locale
 * @return string
 */
if (!function_exists('__')) {
    function __($key, array $replace = [], $locale = null)
    {
        return \App\Helpers\TranslationHelper::trans($key, $replace, $locale);
    }
}

/**
 * Fonction globale pour remplacer la fonction trans_choice() de Laravel.
 *
 * @param string $key
 * @param int $count
 * @param array $replace
 * @param string|null $locale
 * @return string
 */
if (!function_exists('trans_choice')) {
    function trans_choice($key, $count, array $replace = [], $locale = null)
    {
        return \App\Helpers\TranslationHelper::transChoice($key, $count, $replace, $locale);
    }
} 