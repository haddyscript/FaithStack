@extends('admin.layouts.app')

@section('title', 'Themes')
@section('heading', 'Theme Gallery')
@section('breadcrumbs')
    <x-breadcrumb :items="[['label'=>'Dashboard','url'=>route('admin.dashboard')],['label'=>'Themes']]" />
@endsection

@section('content')

<div x-data="{
    activeCategory: 'all',
    previewOpen: false,
    previewTheme: null,
    openPreview(theme) { this.previewTheme = theme; this.previewOpen = true; },
    closePreview() { this.previewOpen = false; setTimeout(() => this.previewTheme = null, 300); }
}">

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <p class="text-gray-500 text-sm">Choose a theme that reflects your organization's style.</p>
    @if($tenant->theme)
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 border border-emerald-200 flex-shrink-0">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold text-emerald-700">Active: {{ $tenant->theme->name }}</span>
        </div>
    @endif
</div>

{{-- Category tabs --}}
<div class="flex items-center gap-2 mb-8 overflow-x-auto pb-1" style="-ms-overflow-style:none;scrollbar-width:none;">
    <button @click="activeCategory = 'all'"
            :class="activeCategory === 'all' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 whitespace-nowrap flex-shrink-0">
        All Themes
        <span class="ml-1.5 opacity-70 text-xs">({{ $themes->flatten()->count() }})</span>
    </button>
    @foreach($themes->keys() as $cat)
        <button @click="activeCategory = '{{ $cat }}'"
                :class="activeCategory === '{{ $cat }}' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:border-indigo-300 hover:text-indigo-600'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-150 whitespace-nowrap flex-shrink-0 capitalize">
            {{ ucfirst($cat) }}
            <span class="ml-1 opacity-60 text-xs">({{ $themes[$cat]->count() }})</span>
        </button>
    @endforeach
</div>

{{-- Grid per category --}}
@foreach($themes as $category => $categoryThemes)
<div x-show="activeCategory === 'all' || activeCategory === '{{ $category }}'"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="mb-10">

    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest capitalize">{{ $category }}</h2>
        <div class="flex-1 h-px bg-gray-100"></div>
        <span class="text-xs text-gray-300">{{ $categoryThemes->count() }}</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($categoryThemes as $theme)
            @php
                $isActive  = $tenant->theme_id === $theme->id;
                $primary   = $theme->config['primary_color']   ?? '#4f46e5';
                $secondary = $theme->config['secondary_color'] ?? '#7c3aed';
                $navStyle  = $theme->config['nav_style']       ?? 'solid';
                $cardStyle = $theme->config['card_style']      ?? 'shadow';
                $btnRadius = $theme->config['button_radius']   ?? 'rounded-lg';
                $heroVar   = $theme->config['hero_variant']    ?? 'centered';
                $fontHead  = $theme->config['font_heading']    ?? 'Inter';
                $fontBody  = $theme->config['font_body']       ?? 'Inter';
                $layout    = $theme->config['layout']          ?? 'classic';
                $spacing   = $theme->config['section_spacing'] ?? 'normal';
                $footer    = $theme->config['footer_style']    ?? 'multi-column';
                $desc      = $theme->config['description']     ?? ucfirst($category) . ' theme';

                // Nav background
                $navBg          = in_array($navStyle, ['floating','bordered']) ? '#ffffff' : $primary;
                $navLogoColor   = in_array($navStyle, ['floating','bordered']) ? $primary   : '#ffffff';
                $navItemColor   = in_array($navStyle, ['floating','bordered']) ? 'rgba(75,85,99,0.7)' : 'rgba(255,255,255,0.65)';
                $navBorder      = $navStyle === 'bordered'  ? '1px solid rgba(0,0,0,0.08)' : 'none';
                $navBoxShadow   = $navStyle === 'floating'  ? '0 1px 8px rgba(0,0,0,0.09)' : 'none';

                // Button radius → px
                $radiusPx = match($btnRadius) {
                    'rounded-sm'  => '3px',
                    'rounded-md'  => '6px',
                    'rounded-lg'  => '8px',
                    'rounded-xl'  => '12px',
                    'rounded-2xl' => '16px',
                    'rounded-full'=> '999px',
                    default       => '8px',
                };

                // Card style
                $cardBg     = match($cardStyle) {
                    'glass'  => 'rgba(255,255,255,0.12)',
                    'flat'   => '#f1f5f9',
                    default  => '#ffffff',
                };
                $cardBorder = match($cardStyle) {
                    'outline'=> '1px solid rgba(0,0,0,0.12)',
                    'glass'  => '1px solid rgba(255,255,255,0.18)',
                    default  => 'none',
                };
                $cardShadow = $cardStyle === 'shadow' ? '0 2px 8px rgba(0,0,0,0.09)' : 'none';

                // Is the primary color dark?
                $rr = hexdec(substr(ltrim($primary,'#'), 0, 2));
                $gg = hexdec(substr(ltrim($primary,'#'), 2, 2));
                $bb = hexdec(substr(ltrim($primary,'#'), 4, 2));
                $isDark = (0.299*$rr + 0.587*$gg + 0.114*$bb)/255 < 0.55;
                $contentBg    = $isDark ? '#111827'           : '#f8fafc';
                $miniTextDark = $isDark ? 'rgba(255,255,255,0.55)' : 'rgba(0,0,0,0.4)';
                $miniHeadDark = $isDark ? 'rgba(255,255,255,0.85)' : 'rgba(0,0,0,0.65)';

                // Payload for Alpine modal
                $tj = json_encode([
                    'id'        => $theme->id,
                    'name'      => $theme->name,
                    'category'  => $theme->category,
                    'isActive'  => $isActive,
                    'primary'   => $primary,
                    'secondary' => $secondary,
                    'navStyle'  => $navStyle,
                    'cardStyle' => $cardStyle,
                    'btnRadius' => $btnRadius,
                    'heroVar'   => $heroVar,
                    'fontHead'  => $fontHead,
                    'fontBody'  => $fontBody,
                    'layout'    => $layout,
                    'spacing'   => $spacing,
                    'footer'    => $footer,
                    'desc'      => $desc,
                    'radiusPx'  => $radiusPx,
                ], JSON_HEX_QUOT | JSON_HEX_TAG);
            @endphp

            <div class="group relative flex flex-col rounded-2xl overflow-hidden border transition-all duration-200 cursor-pointer
                        hover:-translate-y-1 hover:shadow-xl
                        {{ $isActive
                            ? 'border-indigo-400 ring-2 ring-indigo-500/20 shadow-lg shadow-indigo-100'
                            : 'border-gray-100 hover:border-indigo-200 shadow-sm' }}"
                 @click="openPreview({{ $tj }})">

                {{-- ═══════════════════════════════════════════════
                     MINI PREVIEW  (all inline styles, no Tailwind)
                ════════════════════════════════════════════════ --}}
                <div class="relative overflow-hidden flex-shrink-0" style="height:168px; background:{{ $contentBg }};">

                    @if($theme->preview_image)
                        <img src="{{ asset('storage/'.$theme->preview_image) }}"
                             alt="{{ $theme->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="absolute inset-0 flex flex-col">

                            {{-- Navbar --}}
                            <div style="
                                background: {{ $navBg }};
                                border-bottom: {{ $navBorder }};
                                box-shadow: {{ $navBoxShadow }};
                                padding: 6px 10px;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                                flex-shrink: 0;
                            ">
                                <div style="width:18px;height:18px;border-radius:5px;background:{{ $navLogoColor }};opacity:0.9;flex-shrink:0;"></div>
                                <div style="flex:1;display:flex;gap:5px;align-items:center;">
                                    <div style="width:20px;height:3px;border-radius:2px;background:{{ $navItemColor }};"></div>
                                    <div style="width:20px;height:3px;border-radius:2px;background:{{ $navItemColor }};"></div>
                                    <div style="width:20px;height:3px;border-radius:2px;background:{{ $navItemColor }};"></div>
                                </div>
                                <div style="padding:3px 7px;border-radius:{{ $radiusPx }};background:{{ $secondary }};color:#fff;font-size:7px;font-weight:700;flex-shrink:0;">CTA</div>
                            </div>

                            {{-- Hero --}}
                            @if(in_array($heroVar, ['split','image-right']))
                                <div style="display:flex;flex:1;overflow:hidden;">
                                    <div style="flex:1;background:linear-gradient(135deg,{{ $primary }},{{ $secondary }});padding:10px;display:flex;flex-direction:column;justify-content:center;gap:4px;">
                                        <div style="width:65%;height:5px;border-radius:3px;background:rgba(255,255,255,0.9);"></div>
                                        <div style="width:45%;height:3px;border-radius:2px;background:rgba(255,255,255,0.5);"></div>
                                        <div style="width:45%;height:3px;border-radius:2px;background:rgba(255,255,255,0.5);"></div>
                                        <div style="margin-top:6px;padding:3px 8px;border-radius:{{ $radiusPx }};background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.4);display:inline-block;width:fit-content;color:#fff;font-size:7px;font-weight:600;">Start</div>
                                    </div>
                                    <div style="width:40%;background:linear-gradient(160deg,{{ $secondary }}cc,{{ $primary }}88);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
                                        <div style="width:44px;height:34px;border-radius:6px;background:rgba(255,255,255,0.14);border:1px solid rgba(255,255,255,0.22);"></div>
                                        <div style="position:absolute;bottom:5px;right:5px;width:16px;height:16px;border-radius:50%;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.18);"></div>
                                    </div>
                                </div>
                            @else
                                {{-- Centered hero --}}
                                <div style="background:linear-gradient(135deg,{{ $primary }},{{ $secondary }});padding:10px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;flex-shrink:0;min-height:50px;">
                                    <div style="width:68%;height:5px;border-radius:3px;background:rgba(255,255,255,0.9);"></div>
                                    <div style="width:48%;height:3px;border-radius:2px;background:rgba(255,255,255,0.5);"></div>
                                    <div style="margin-top:5px;padding:3px 10px;border-radius:{{ $radiusPx }};background:{{ $secondary }};border:1px solid rgba(255,255,255,0.25);color:#fff;font-size:7px;font-weight:700;">Get Started</div>
                                </div>
                                {{-- Cards row --}}
                                <div style="background:{{ $contentBg }};padding:8px;flex:1;display:flex;flex-direction:column;gap:5px;overflow:hidden;">
                                    <div style="display:flex;gap:4px;">
                                        @for($ci = 0; $ci < 3; $ci++)
                                        <div style="flex:1;background:{{ $cardBg }};border-radius:5px;padding:5px;border:{{ $cardBorder }};box-shadow:{{ $cardShadow }};">
                                            <div style="width:12px;height:12px;border-radius:3px;background:{{ $secondary }};opacity:0.72;margin-bottom:3px;"></div>
                                            <div style="width:80%;height:3px;border-radius:2px;background:{{ $miniHeadDark }};margin-bottom:2px;"></div>
                                            <div style="width:58%;height:2px;border-radius:1px;background:{{ $miniTextDark }};"></div>
                                        </div>
                                        @endfor
                                    </div>
                                    <div style="padding:0 2px;">
                                        <div style="width:90%;height:2px;border-radius:1px;background:{{ $miniTextDark }};margin-bottom:2px;"></div>
                                        <div style="width:72%;height:2px;border-radius:1px;background:{{ $miniTextDark }};opacity:0.6;"></div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif

                    {{-- Active badge --}}
                    @if($isActive)
                        <div class="absolute top-2 left-2 z-10">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-600 text-white shadow-md ring-1 ring-indigo-400/30">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Active
                            </span>
                        </div>
                    @endif

                    {{-- Layout badge --}}
                    <div class="absolute top-2 right-2 z-10">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-black/40 backdrop-blur-sm text-white/90 capitalize leading-none">
                            {{ $layout }}
                        </span>
                    </div>

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/35 transition-all duration-200 flex items-center justify-center z-10">
                        <span class="opacity-0 group-hover:opacity-100 transition-all duration-200 scale-95 group-hover:scale-100
                                     bg-white text-gray-900 text-xs font-bold px-4 py-2 rounded-xl shadow-xl flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Preview
                        </span>
                    </div>
                </div>

                {{-- Card body --}}
                <div class="p-4 bg-white flex flex-col gap-3 flex-1">

                    {{-- Name + badge --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $theme->name }}</h3>
                            <p class="text-[11px] text-gray-400 mt-0.5 truncate">{{ $desc }}</p>
                        </div>
                        <span class="flex-shrink-0 px-2 py-0.5 rounded-md bg-gray-100 text-gray-500 text-[10px] font-semibold capitalize leading-snug">
                            {{ $theme->category }}
                        </span>
                    </div>

                    {{-- Color palette --}}
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            <div class="w-5 h-5 rounded-full ring-2 ring-white shadow-sm" style="background:{{ $primary }}"></div>
                            <div class="w-5 h-5 rounded-full ring-2 ring-white shadow-sm -ml-1.5" style="background:{{ $secondary }}"></div>
                        </div>
                        <span class="text-[10px] font-mono text-gray-400 leading-none">{{ $primary }} / {{ $secondary }}</span>
                    </div>

                    {{-- Design tokens --}}
                    <div class="flex flex-wrap gap-1">
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-medium">{{ $fontHead }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-medium capitalize">{{ $navStyle }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-medium capitalize">{{ $cardStyle }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-slate-100 text-slate-500 text-[10px] font-medium capitalize">{{ $spacing }}</span>
                    </div>

                    {{-- Action row --}}
                    @if($isActive)
                        <div class="mt-auto flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-indigo-50 text-indigo-600 text-xs font-semibold">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Currently Active
                        </div>
                    @else
                        <div class="mt-auto flex gap-2">
                            <button type="button"
                                    @click.stop="openPreview({{ $tj }})"
                                    class="flex-1 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600
                                           hover:border-indigo-300 hover:text-indigo-600 transition-colors">
                                Preview
                            </button>
                            <form method="POST" action="{{ route('admin.themes.activate') }}" @click.stop>
                                @csrf
                                <input type="hidden" name="theme_id" value="{{ $theme->id }}">
                                <button type="submit"
                                        class="py-2 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-colors">
                                    Apply
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
@endforeach


{{-- ════════════════════════════════════════════════════════════
     LIVE PREVIEW MODAL
════════════════════════════════════════════════════════════ --}}
<div x-show="previewOpen" x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/65 backdrop-blur-sm" @click="closePreview()"></div>

    <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
        <div class="relative w-full max-w-3xl bg-white rounded-3xl shadow-2xl overflow-hidden"
             x-show="previewOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-end="opacity-0 scale-95"
             @click.stop>

            {{-- Close button --}}
            <button @click="closePreview()"
                    class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-black/25 hover:bg-black/40 flex items-center justify-center transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <template x-if="previewTheme">
                <div>

                    {{-- ── Large mock preview ──────────────────────── --}}
                    <div class="relative overflow-hidden" style="height: 280px;"
                         :style="`background: ${previewTheme.primary}`">

                        {{-- Decorative orbs --}}
                        <div :style="`position:absolute;width:220px;height:220px;border-radius:50%;background:${previewTheme.secondary};opacity:0.18;top:-60px;right:-40px;filter:blur(40px);pointer-events:none;`"></div>
                        <div :style="`position:absolute;width:140px;height:140px;border-radius:50%;background:${previewTheme.primary};opacity:0.25;bottom:-20px;left:8%;filter:blur(24px);pointer-events:none;`"></div>

                        {{-- Navbar --}}
                        <div class="absolute top-0 inset-x-0 z-10"
                             :style="`background:${previewTheme.primary};padding:12px 22px;display:flex;align-items:center;gap:14px;`">
                            <div :style="`width:26px;height:26px;border-radius:6px;background:${previewTheme.secondary};flex-shrink:0;`"></div>
                            <div style="display:flex;gap:10px;align-items:center;flex:1;">
                                <div style="width:32px;height:4px;border-radius:2px;background:rgba(255,255,255,0.45);"></div>
                                <div style="width:32px;height:4px;border-radius:2px;background:rgba(255,255,255,0.45);"></div>
                                <div style="width:32px;height:4px;border-radius:2px;background:rgba(255,255,255,0.45);"></div>
                                <div style="width:32px;height:4px;border-radius:2px;background:rgba(255,255,255,0.45);"></div>
                            </div>
                            <div :style="`padding:7px 18px;border-radius:${previewTheme.radiusPx};background:${previewTheme.secondary};color:#fff;font-size:11px;font-weight:700;flex-shrink:0;`">Get Started</div>
                        </div>

                        {{-- Hero content --}}
                        <div class="absolute inset-0 flex items-center justify-center pt-16"
                             :style="`background:linear-gradient(135deg,${previewTheme.primary},${previewTheme.secondary})`">
                            <div class="text-center px-8">
                                <div style="width:200px;height:8px;border-radius:4px;background:rgba(255,255,255,0.92);margin:0 auto 8px;"></div>
                                <div style="width:150px;height:5px;border-radius:3px;background:rgba(255,255,255,0.55);margin:0 auto 5px;"></div>
                                <div style="width:170px;height:5px;border-radius:3px;background:rgba(255,255,255,0.4);margin:0 auto 18px;"></div>
                                <div style="display:flex;gap:10px;justify-content:center;">
                                    <div :style="`padding:9px 24px;border-radius:${previewTheme.radiusPx};background:rgba(255,255,255,0.18);border:1.5px solid rgba(255,255,255,0.5);color:#fff;font-size:11px;font-weight:700;`">Primary CTA</div>
                                    <div :style="`padding:9px 24px;border-radius:${previewTheme.radiusPx};background:transparent;border:1.5px solid rgba(255,255,255,0.28);color:rgba(255,255,255,0.75);font-size:11px;font-weight:600;`">Learn More</div>
                                </div>
                            </div>
                        </div>

                        {{-- Active label overlay --}}
                        <template x-if="previewTheme.isActive">
                            <div class="absolute bottom-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-indigo-600 text-white text-xs font-bold shadow-lg">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Currently Active
                                </span>
                            </div>
                        </template>
                    </div>

                    {{-- Info panel --}}
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-5 gap-6">

                        {{-- Left col: identity + action --}}
                        <div class="sm:col-span-2">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="flex">
                                    <div class="w-7 h-7 rounded-full ring-2 ring-white shadow" :style="`background:${previewTheme.primary}`"></div>
                                    <div class="w-7 h-7 rounded-full ring-2 ring-white shadow -ml-2" :style="`background:${previewTheme.secondary}`"></div>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 leading-snug" x-text="previewTheme.name"></h3>
                                    <p class="text-xs text-gray-400 capitalize" x-text="previewTheme.category + ' · ' + previewTheme.layout + ' layout'"></p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mb-5 leading-relaxed" x-text="previewTheme.desc"></p>

                            <template x-if="!previewTheme.isActive">
                                <form method="POST" action="{{ route('admin.themes.activate') }}">
                                    @csrf
                                    <input type="hidden" name="theme_id" :value="previewTheme.id">
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Apply This Theme
                                    </button>
                                </form>
                            </template>
                            <template x-if="previewTheme.isActive">
                                <div class="flex items-center justify-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-semibold px-5 py-2.5 rounded-xl">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Active Theme
                                </div>
                            </template>
                        </div>

                        {{-- Right col: design tokens --}}
                        <div class="sm:col-span-3">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">Design Tokens</p>
                            <div class="grid grid-cols-2 gap-x-5 gap-y-3 text-xs">
                                <div>
                                    <p class="text-gray-400 mb-0.5">Heading Font</p>
                                    <p class="font-semibold text-gray-800 truncate" x-text="previewTheme.fontHead"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Body Font</p>
                                    <p class="font-semibold text-gray-800 truncate" x-text="previewTheme.fontBody"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Nav Style</p>
                                    <p class="font-semibold text-gray-800 capitalize" x-text="previewTheme.navStyle"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Hero Variant</p>
                                    <p class="font-semibold text-gray-800 capitalize" x-text="previewTheme.heroVar"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Card Style</p>
                                    <p class="font-semibold text-gray-800 capitalize" x-text="previewTheme.cardStyle"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Section Spacing</p>
                                    <p class="font-semibold text-gray-800 capitalize" x-text="previewTheme.spacing"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Primary Color</p>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-1 ring-gray-200" :style="`background:${previewTheme.primary}`"></div>
                                        <p class="font-mono font-semibold text-gray-800" x-text="previewTheme.primary"></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Secondary Color</p>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-1 ring-gray-200" :style="`background:${previewTheme.secondary}`"></div>
                                        <p class="font-mono font-semibold text-gray-800" x-text="previewTheme.secondary"></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Button Radius</p>
                                    <p class="font-semibold text-gray-800" x-text="previewTheme.btnRadius"></p>
                                </div>
                                <div>
                                    <p class="text-gray-400 mb-0.5">Footer Style</p>
                                    <p class="font-semibold text-gray-800 capitalize" x-text="previewTheme.footer"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </template>

        </div>
    </div>
</div>

</div>{{-- /Alpine root --}}

@endsection
