@extends('layouts.app')

@section('title', __('Language Test'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-3xl font-bold mb-6">{{ __('Language Test') }}</h1>
        
        <div class="mb-4">
            <p class="text-lg mb-2"><strong>{{ __('Current language') }}:</strong> {{ app()->getLocale() }}</p>
            <p class="text-lg mb-2"><strong>{{ __('Session locale') }}:</strong> {{ session('locale', 'Not set') }}</p>
        </div>
        
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4">{{ __('Test translations') }}</h2>
            <ul class="list-disc pl-6 space-y-2">
                <li>{{ __('Home') }}</li>
                <li>{{ __('About') }}</li>
                <li>{{ __('Admission') }}</li>
                <li>{{ __('Track my application') }}</li>
                <li>{{ __('Contact / Help') }}</li>
                <li>{{ __('Dashboard') }}</li>
                <li>{{ __('Login') }}</li>
                <li>{{ __('Logout') }}</li>
                <li>{{ __('Register now') }}</li>
                <li>{{ __('Our Mission') }}</li>
                <li>{{ __('What we offer') }}</li>
            </ul>
        </div>
        
        <div class="mb-6">
            <h2 class="text-2xl font-semibold mb-4">{{ __('Language Switcher') }}</h2>
            <div class="flex space-x-4">
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="fr">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Français</button>
                </form>
                
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">English</button>
                </form>
                
                <form action="{{ route('language.switch.post') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="de">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Deutsch</button>
                </form>
            </div>
        </div>
        
        <div class="mt-8 p-4 bg-gray-100 rounded">
            <pre class="text-sm">{{ json_encode(trans()->get('*'), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</div>
@endsection 