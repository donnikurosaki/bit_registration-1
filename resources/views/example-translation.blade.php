@extends('layouts.app')

@section('title', __('Translation Example'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-primary">{{ __('Translation Example') }}</h1>
        
        <div class="mb-8 bg-blue-50 p-4 rounded-lg">
            <p class="text-lg mb-2"><strong>{{ __('Current language') }}:</strong> {{ app()->getLocale() }}</p>
            <p class="text-lg mb-2"><strong>Session locale:</strong> {{ session('locale', 'Not set') }}</p>
            <p class="text-lg mb-2"><strong>Cookie locale:</strong> {{ request()->cookie('locale') ?? 'Not set' }}</p>
            <p class="text-lg mb-2"><strong>HTML lang attribute:</strong> <span id="html-lang-display">{{ str_replace('_', '-', app()->getLocale()) }}</span></p>
            
            <script>
                // Script pour afficher l'attribut lang réel du HTML
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('html-lang-display').textContent = document.documentElement.lang;
                });
            </script>
            
            <!-- Test direct des traductions -->
            <div class="mt-4 p-4 bg-yellow-100 rounded-lg">
                <h3 class="font-bold mb-2">Test direct des traductions:</h3>
                <p><strong>Home (fr):</strong> {{ trans('Home', [], 'fr') }}</p>
                <p><strong>Home (en):</strong> {{ trans('Home', [], 'en') }}</p>
                <p><strong>Home (de):</strong> {{ trans('Home', [], 'de') }}</p>
                <p><strong>__('Home'):</strong> {{ __('Home') }}</p>
                <p><strong>trans('Home'):</strong> {{ trans('Home') }}</p>
                <p><strong>trans_choice:</strong> {{ trans_choice('You have :count apple|You have :count apples', 1, ['count' => 1]) }}</p>
            </div>
            
            <div class="flex space-x-4 mt-4">
                <a href="{{ route('language.switch', 'fr') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Français (GET)
                </a>
                
                <a href="{{ route('language.switch', 'en') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    English (GET)
                </a>
                
                <a href="{{ route('language.switch', 'de') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Deutsch (GET)
                </a>
            </div>
            
            <div class="flex space-x-4 mt-4">
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="fr">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Français (POST)</button>
                </form>
                
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">English (POST)</button>
                </form>
                
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="de">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Deutsch (POST)</button>
                </form>
            </div>
        </div>
        
        <!-- Section 1: Textes simples -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">1. {{ __('Simple texts') }}</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="mb-2">{{ __('This is a simple text that will be translated.') }}</p>
                <p class="mb-2">{{ __('Welcome to our website.') }}</p>
                <p class="mb-2">{{ __('Please select your language.') }}</p>
            </div>
        </div>
        
        <!-- Section 2: Formulaire -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">2. {{ __('Form example') }}</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <form class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">{{ __('Full Name') }}</label>
                        <input type="text" class="w-full px-3 py-2 border rounded-lg" placeholder="{{ __('Enter your full name') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">{{ __('Email Address') }}</label>
                        <input type="email" class="w-full px-3 py-2 border rounded-lg" placeholder="{{ __('Enter your email address') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">{{ __('Message') }}</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg" rows="4" placeholder="{{ __('Write your message here') }}"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Section 3: Contenu dynamique -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">3. {{ __('Dynamic content') }}</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                @php
                    $count = 3;
                    $name = "John Doe";
                    $date = date('Y-m-d');
                @endphp
                
                <p class="mb-2">
                    {!! __('You have :count new messages.', ['count' => '<strong>'.$count.'</strong>']) !!}
                </p>
                <p class="mb-2">
                    {!! __('Hello :name, welcome back!', ['name' => '<strong>'.$name.'</strong>']) !!}
                </p>
                <p class="mb-2">
                    {{ __('Today is :date', ['date' => $date]) }}
                </p>
            </div>
        </div>
        
        <!-- Section 4: Pluralisation -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">4. {{ __('Pluralization') }}</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                @foreach([0, 1, 2, 5] as $count)
                    <p class="mb-2">
                        {{ trans_choice('You have :count apple|You have :count apples', $count, ['count' => $count]) }}
                    </p>
                @endforeach
            </div>
        </div>
        
        <!-- Section 5: Dates et nombres -->
        <div class="mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">5. {{ __('Dates and numbers') }}</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="mb-2">
                    {{ __('Date format') }}: {{ __('Today is :date', ['date' => date('d F Y')]) }}
                </p>
                <p class="mb-2">
                    {{ __('Number format') }}: {{ __('The price is :price', ['price' => number_format(1234.56, 2, ',', ' ')]) }}
                </p>
            </div>
        </div>
        
        <!-- Section 6: Instructions -->
        <div class="mt-8 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h2 class="text-xl font-semibold mb-2 text-yellow-800">{{ __('How to use this example') }}</h2>
            <ol class="list-decimal pl-5 space-y-2 text-gray-700">
                <li>{{ __('Change the language using the buttons at the top of the page.') }}</li>
                <li>{{ __('Observe how all texts are translated automatically.') }}</li>
                <li>{{ __('Check the translation files in the lang/ directory to see how translations are defined.') }}</li>
                <li>{{ __('Use this example as a reference for your own translations.') }}</li>
            </ol>
        </div>
    </div>
</div>
@endsection 