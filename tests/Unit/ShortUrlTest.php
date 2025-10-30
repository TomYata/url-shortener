<?php

use App\Models\ShortUrl;
use App\Models\User;

describe('ShortUrl', function () {
    it('génère un short code unique à chaque appel', function () {
        $url = new ShortUrl(['original_url' => 'https://laravel.com']);
        $code1 = $url->generateShortUrl($url->original_url);
        sleep(1); // Pour garantir un timestamp différent
        $code2 = $url->generateShortUrl($url->original_url);
        expect($code1)->not->toBe($code2);
    });

    // Helper de validation d'URL
    it('valide une URL correcte', function () {
        $valid = filter_var('https://laravel.com', FILTER_VALIDATE_URL);
        expect($valid)->not->toBeFalse();
    });

    it('refuse une URL incorrecte', function () {
        $valid = filter_var('not-a-url', FILTER_VALIDATE_URL);
        expect($valid)->toBeFalse();
    });

    // Edge case : URL très longue
    it('accepte une URL très longue', function () {
        $longUrl = 'https://laravel.com/' . str_repeat('a', 2000);
        $valid = filter_var($longUrl, FILTER_VALIDATE_URL);
        expect($valid)->not->toBeFalse();
    });

});
