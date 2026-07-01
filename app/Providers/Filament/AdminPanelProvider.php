<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(\App\Filament\Pages\Auth\Login::class)
            ->brandName('Admin')
            ->brandLogo(fn () => new HtmlString('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="none" class="h-9 w-9"><rect width="40" height="40" rx="12" fill="url(#kg)"/><path d="M12 28V16l8 6 8-6v12" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><defs><linearGradient id="kg" x1="0" y1="0" x2="40" y2="40"><stop stop-color="#059669"/><stop offset="1" stop-color="#10b981"/></linearGradient></defs></svg>'))
            ->favicon(asset('favicon.ico'))
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15.5rem')
            ->collapsedSidebarWidth('4rem')
            ->topbar(true)
            ->colors([
                'primary' => Color::Emerald,
                'gray'    => Color::Slate,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger'  => Color::Rose,
                'info'    => Color::Sky,
            ])
            ->font('Plus Jakarta Sans')
            ->maxContentWidth(MaxWidth::Full)
            ->navigationGroups([
                NavigationGroup::make('Keanggotaan')->icon('heroicon-o-user-group'),
                NavigationGroup::make('Simpan Pinjam')->icon('heroicon-o-banknotes'),
                NavigationGroup::make('Toko & Unit Usaha')->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make('Akuntansi')->icon('heroicon-o-calculator'),
                NavigationGroup::make('SHU & RAT')->icon('heroicon-o-cake'),
                NavigationGroup::make('HR & Asset')->icon('heroicon-o-cube'),
                NavigationGroup::make('Asuransi')->icon('heroicon-o-shield-check'),
                NavigationGroup::make('Laporan')->icon('heroicon-o-document-chart-bar'),
                NavigationGroup::make('Blog & Marketing')->icon('heroicon-o-newspaper'),
                NavigationGroup::make('Pengaturan')->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\KustomDashboard::class,  // Custom premium dashboard (replaces default Filament dashboard)
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => view('filament.theme-switcher')
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn () => Blade::render('<div class="text-center text-xs text-gray-500 py-4">© ' . date('Y') . ' KoperasiApp · Bantuan: <a href="https://wa.me/6281296052010" class="text-emerald-600 hover:underline font-semibold">WhatsApp 0812-9605-2010</a></div>')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
