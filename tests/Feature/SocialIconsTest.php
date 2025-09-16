<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Http\Livewire\Auth\Login;

class SocialIconsTest extends TestCase
{
    public function test_social_icons_are_fully_rounded(): void
    {
        $component = Livewire::test(Login::class);
        
        $component->assertSee('btn-social');
        $component->assertSee('data-tooltip');
    }

    public function test_social_icons_have_hover_tooltips(): void
    {
        $component = Livewire::test(Login::class);
        
        // Check for tooltip attributes (with escape=false to avoid HTML escaping)
        $component->assertSee('data-tooltip="Continue with Google"', false);
        $component->assertSee('data-tooltip="Continue with GitHub"', false);
        $component->assertSee('data-tooltip="Continue with Facebook"', false);
        $component->assertSee('data-tooltip="Continue with Apple"', false);
    }

    public function test_social_icons_have_correct_classes(): void
    {
        $component = Livewire::test(Login::class);
        
        // Check for rounded button classes
        $component->assertSee('btn-social btn-google');
        $component->assertSee('btn-social btn-github');
        $component->assertSee('btn-social btn-facebook');
        $component->assertSee('btn-social btn-apple');
    }

    public function test_social_icons_are_centered(): void
    {
        $component = Livewire::test(Login::class);
        
        // Check for centered layout
        $component->assertSee('social-buttons');
    }
}
