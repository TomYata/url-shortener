<?php
use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;
use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\delete;

pest()->use(RefreshDatabase::class);

// Test création URL courte
it('un utilisateur peut créer une URL courte valide', function () {
    $user = User::factory()->create();
    actingAs($user);
    $response = post('/shorturl/store', [
        'original_url' => 'https://laravel.com',
    ]);
    $response->assertRedirect(route('dashboard'));
    expect(ShortUrl::where('original_url', 'https://laravel.com')->exists())->toBeTrue();
});

it('la validation empêche la création avec une URL invalide', function () {
    $user = User::factory()->create();
    actingAs($user);
    $response = post('/shorturl/store', [
        'original_url' => 'not-a-url',
    ]);
    $response->assertSessionHasErrors('original_url');
});

// Test affichage dashboard
it('le dashboard affiche uniquement les URLs actives', function () {
    $user = User::factory()->create();
    $active = ShortUrl::factory()->create(['user_id' => $user->id, 'is_active' => true]);
    $inactive = ShortUrl::factory()->create(['user_id' => $user->id, 'is_active' => false]);
    actingAs($user);
    $response = get('/dashboard');
    $response->assertSee($active->original_url);
    $response->assertDontSee($inactive->original_url);
});

// Test redirection URL courte
it('redirige vers l\'URL originale si active', function () {
    $url = ShortUrl::factory()->create(['is_active' => true]);
    $response = get('/' . $url->short_url);
    $response->assertRedirect($url->original_url);
});

it('affiche la page inactive si l\'URL est désactivée', function () {
    $url = ShortUrl::factory()->create(['is_active' => false]);
    $response = get('/' . $url->short_url);
    $response->assertSee('est plus valide');
});

// Test désactivation URL
it('désactive l\'URL au lieu de la supprimer', function () {
    $user = User::factory()->create();
    $url = ShortUrl::factory()->create(['user_id' => $user->id, 'is_active' => true]);
    actingAs($user);
    $response = delete('/shorturl/' . $url->id);
    $response->assertRedirect(route('dashboard'));
    expect((bool) ShortUrl::find($url->id)->is_active)->toBeFalse();
});

it('une URL désactivée n\'apparaît plus dans le dashboard', function () {
    $user = User::factory()->create();
    $url = ShortUrl::factory()->create(['user_id' => $user->id, 'is_active' => true]);
    actingAs($user);
    delete('/shorturl/' . $url->id);
    $response = get('/dashboard');
    $response->assertDontSee($url->original_url);
});

// Test édition URL courte
it('l\'utilisateur peut modifier l\'URL originale', function () {
    $user = User::factory()->create();
    $url = ShortUrl::factory()->create(['user_id' => $user->id, 'original_url' => 'https://old.com']);
    actingAs($user);
    $response = put('/shorturl/' . $url->id, [
        'original_url' => 'https://new.com',
    ]);
    $response->assertRedirect(route('dashboard'));
    expect(ShortUrl::find($url->id)->original_url)->toBe('https://new.com');
});

// Test sécurité URLs
it('un utilisateur ne peut pas désactiver une URL qui ne lui appartient pas', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $url = ShortUrl::factory()->create(['user_id' => $user2->id, 'is_active' => true]);
    actingAs($user1);
    $response = delete('/shorturl/' . $url->id);
    $response->assertStatus(404);
    expect((bool) ShortUrl::find($url->id)->is_active)->toBeTrue();
});

// Test pagination dashboard
it('le dashboard pagine correctement les URLs', function () {
    $user = User::factory()->create();
    ShortUrl::factory()->count(15)->create(['user_id' => $user->id, 'is_active' => true]);
    actingAs($user);
    $response = get('/dashboard');
    $response->assertSee('pagination');
});

// Relation User -> ShortUrl
it('un utilisateur peut avoir plusieurs ShortUrls', function () {
    $user = User::factory()->create();
    $url1 = ShortUrl::factory()->create(['user_id' => $user->id]);
    $url2 = ShortUrl::factory()->create(['user_id' => $user->id]);
    expect($user->shortUrls)->toHaveCount(2);
});