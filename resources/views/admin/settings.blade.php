@extends('admin.layouts.app')

@section('title', 'Settings')
@section('heading', 'Site Settings')

@section('content')
@php
    $br = $tenant->getBranding();
    $presets = [
        ['label' => 'Indigo Pro',    'sidebar_bg' => '#1e1b4b', 'sidebar_text' => '#a5b4fc', 'primary' => '#6366f1', 'accent' => '#a78bfa'],
        ['label' => 'Dark Mode',     'sidebar_bg' => '#09090b', 'sidebar_text' => '#71717a', 'primary' => '#3b82f6', 'accent' => '#06b6d4'],
        ['label' => 'Forest Green',  'sidebar_bg' => '#052e16', 'sidebar_text' => '#86efac', 'primary' => '#16a34a', 'accent' => '#4ade80'],
    ];
@endphp
<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data"
      x-data="{
          logoPreview: '{{ $tenant->logo ? Storage::url($tenant->logo) : '' }}',
          isDragging: false,
          selectedTheme: '{{ old('theme_id', $tenant->theme_id) }}',
          branding: {
              sidebar_bg:   '{{ $br['sidebar_bg'] }}',
              sidebar_text: '{{ $br['sidebar_text'] }}',
              primary:      '{{ $br['primary'] }}',
              accent:       '{{ $br['accent'] }}',
          },
          defaults: {
              sidebar_bg:   '{{ \App\Models\Tenant::$brandingDefaults['sidebar_bg'] }}',
              sidebar_text: '{{ \App\Models\Tenant::$brandingDefaults['sidebar_text'] }}',
              primary:      '{{ \App\Models\Tenant::$brandingDefaults['primary'] }}',
              accent:       '{{ \App\Models\Tenant::$brandingDefaults['accent'] }}',
          },
          get contrastWarning() {
              function lum(hex) {
                  hex = hex.replace('#','');
                  var r = parseInt(hex.substr(0,2),16)/255,
                      g = parseInt(hex.substr(2,2),16)/255,
                      b = parseInt(hex.substr(4,2),16)/255;
                  return 0.2126*r + 0.7152*g + 0.0722*b;
              }
              var bgL = lum(this.branding.sidebar_bg);
              var txL = lum(this.branding.sidebar_text);
              var ratio = (Math.max(bgL,txL)+0.05)/(Math.min(bgL,txL)+0.05);
              return ratio < 3;
          },
          applyPreset(p) {
              this.branding.sidebar_bg   = p.sidebar_bg;
              this.branding.sidebar_text = p.sidebar_text;
              this.branding.primary      = p.primary;
              this.branding.accent       = p.accent;
              this.applyLive();
          },
          resetBranding() { this.branding = Object.assign({}, this.defaults); this.applyLive(); },
          applyLive() {
              var r = document.documentElement.style;
              r.setProperty('--sb-bg',   this.branding.sidebar_bg);
              r.setProperty('--sb-text', this.branding.sidebar_text);
              r.setProperty('--adm-pri', this.branding.primary);
              r.setProperty('--adm-acc', this.branding.accent);
              var prev = document.getElementById('branding-preview');
              if (prev) {
                  prev.querySelector('.prev-sidebar').style.background = this.branding.sidebar_bg;
                  prev.querySelector('.prev-sidebar-text').style.color  = this.branding.sidebar_text;
                  prev.querySelector('.prev-btn').style.background      = this.branding.primary;
                  prev.querySelector('.prev-accent').style.background   = this.branding.accent;
              }
          },
          handleFile(file) {
              if (!file || !file.type.startsWith('image/')) return;
              this.logoPreview = URL.createObjectURL(file);
              document.getElementById('logo-input').files = (function(f){
                  const dt = new DataTransfer(); dt.items.add(f); return dt.files;
              })(file);
          }
      }" @change.debounce.50ms="applyLive()">
    @csrf @method('PUT')

    {{-- Success banner --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium">
        <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <p class="font-medium mb-1">Please fix the following errors:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        {{-- ── Left column: Identity + Contact ──────────────────────────── --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Identity card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Organization Identity</h3>
                        <p class="text-xs text-slate-400">Name and branding</p>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-5">

                    {{-- Logo uploader --}}
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center transition-colors group-hover:border-indigo-400"
                                 :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave="isDragging = false"
                                 @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0])">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" alt="Logo preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!logoPreview">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                </template>
                            </div>
                            <label for="logo-input"
                                   class="absolute -bottom-2 -right-2 w-7 h-7 rounded-full bg-indigo-600 border-2 border-white flex items-center justify-center cursor-pointer shadow hover:bg-indigo-700 transition-colors">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                            </label>
                        </div>
                        <div class="text-center">
                            <p class="text-xs font-medium text-slate-600">Organization Logo</p>
                            <p class="text-xs text-slate-400 mt-0.5">PNG, JPG or SVG · max 2 MB</p>
                        </div>
                        <input id="logo-input" type="file" name="logo" accept="image/*" class="hidden"
                               @change="handleFile($event.target.files[0])">
                    </div>

                    {{-- Site Name --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Site Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                               placeholder="e.g. Grace Community Church"
                               class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300">
                        <p class="text-xs text-slate-400 mt-1.5">The official name displayed across your site.</p>
                    </div>
                </div>
            </div>

            {{-- Contact card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Contact Information</h3>
                        <p class="text-xs text-slate-400">How visitors can reach you</p>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </span>
                            <input type="email" name="email" value="{{ old('email', $tenant->email) }}" required
                                   placeholder="hello@yourchurch.org"
                                   class="w-full border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Phone</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </span>
                            <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                                   placeholder="+1 (555) 000-0000"
                                   class="w-full border border-slate-200 rounded-xl pl-10 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all
                                          focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Address</label>
                        <textarea name="address" rows="3" placeholder="123 Main Street, City, State 00000"
                                  class="w-full border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 resize-none transition-all
                                         focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent hover:border-slate-300">{{ old('address', $tenant->address) }}</textarea>
                        <p class="text-xs text-slate-400 mt-1.5">Shown on your contact and footer sections.</p>
                    </div>
                </div>
            </div>

            {{-- Save button (mobile / left col) --}}
            <div class="xl:hidden">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all shadow-md shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Save Changes
                </button>
            </div>
        </div>

        {{-- ── Right column: Theme gallery ───────────────────────────────── --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-800">Theme Selection</h3>
                            <p class="text-xs text-slate-400">{{ $themes->count() }} themes available</p>
                        </div>
                    </div>
                    {{-- Search filter --}}
                    <div class="relative hidden sm:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z"/></svg>
                        </span>
                        <input type="text" placeholder="Search themes…" id="theme-search"
                               class="border border-slate-200 rounded-lg pl-7 pr-3 py-1.5 text-xs text-slate-700 placeholder-slate-400 w-36 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4" style="max-height: 640px;">
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3" id="theme-grid">
                        @foreach($themes as $theme)
                        @php
                            $pri  = $theme->config['primary_color']   ?? '#6366f1';
                            $sec  = $theme->config['secondary_color'] ?? '#a78bfa';
                            $cat  = $theme->category ?? 'general';
                        @endphp
                        <label class="theme-card group relative cursor-pointer" data-name="{{ strtolower($theme->name) }}">
                            <input type="radio" name="theme_id" value="{{ $theme->id }}"
                                   {{ old('theme_id', $tenant->theme_id) == $theme->id ? 'checked' : '' }}
                                   x-model="selectedTheme" :value="'{{ $theme->id }}'"
                                   class="sr-only peer">

                            {{-- Card --}}
                            <div class="rounded-xl border-2 border-slate-200 overflow-hidden transition-all duration-200
                                        peer-checked:border-indigo-500 peer-checked:shadow-lg peer-checked:shadow-indigo-100
                                        group-hover:border-slate-300 group-hover:shadow-md">

                                {{-- Mini theme preview --}}
                                <div class="h-20 relative" style="background: {{ $pri }};">
                                    {{-- Fake nav bar --}}
                                    <div class="absolute top-0 inset-x-0 h-5 flex items-center px-2 gap-1" style="background: rgba(0,0,0,0.25);">
                                        <div class="w-8 h-1.5 rounded-full bg-white/60"></div>
                                        <div class="ml-auto flex gap-1">
                                            <div class="w-4 h-1 rounded-full bg-white/50"></div>
                                            <div class="w-4 h-1 rounded-full bg-white/50"></div>
                                            <div class="w-6 h-1 rounded-full" style="background: {{ $sec }};"></div>
                                        </div>
                                    </div>
                                    {{-- Fake hero text --}}
                                    <div class="absolute top-7 left-2 space-y-1">
                                        <div class="h-2 w-20 rounded-full bg-white/80"></div>
                                        <div class="h-1.5 w-14 rounded-full bg-white/50"></div>
                                    </div>
                                    {{-- Accent blob --}}
                                    <div class="absolute bottom-0 right-0 w-12 h-12 rounded-tl-2xl opacity-60" style="background: {{ $sec }};"></div>
                                </div>

                                {{-- Card footer --}}
                                <div class="px-3 py-2.5 bg-white flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate">{{ $theme->name }}</p>
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="w-3 h-3 rounded-full border border-white shadow-sm ring-1 ring-slate-200" style="background:{{ $pri }};"></span>
                                            <span class="w-3 h-3 rounded-full border border-white shadow-sm ring-1 ring-slate-200" style="background:{{ $sec }};"></span>
                                            <span class="text-[10px] text-slate-400 ml-0.5 truncate">{{ ucfirst($cat) }}</span>
                                        </div>
                                    </div>
                                    {{-- Checkmark --}}
                                    <span class="shrink-0 w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center transition-all
                                                 peer-checked:border-indigo-500 peer-checked:bg-indigo-500"
                                          :class="selectedTheme == '{{ $theme->id }}' ? 'border-indigo-500 bg-indigo-500' : 'border-slate-200 bg-white'">
                                        <svg class="w-2.5 h-2.5 text-white transition-opacity"
                                             :class="selectedTheme == '{{ $theme->id }}' ? 'opacity-100' : 'opacity-0'"
                                             fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- Empty state --}}
                    <div id="theme-empty" class="hidden text-center py-12 text-slate-400 text-sm">
                        No themes match your search.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Branding & Colors ─────────────────────────────────────────── --}}
    <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-slate-800">Branding & Colors</h3>
                    <p class="text-xs text-slate-400">Customize your admin dashboard appearance</p>
                </div>
            </div>
            <button type="button" @click="resetBranding()"
                    class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 border border-slate-200 hover:border-slate-300 rounded-lg px-3 py-1.5 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Reset to Defaults
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">

            {{-- Color pickers --}}
            <div class="lg:col-span-2 px-6 py-5 space-y-6">

                {{-- Preset palettes --}}
                <div>
                    <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">Quick Presets</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($presets as $preset)
                        <button type="button"
                                @click="applyPreset({{ json_encode($preset) }})"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl border border-slate-200 hover:border-slate-300 hover:shadow-sm bg-white transition-all text-xs font-medium text-slate-700">
                            <span class="flex gap-1">
                                <span class="w-3 h-3 rounded-full ring-1 ring-white shadow" style="background: {{ $preset['sidebar_bg'] }};"></span>
                                <span class="w-3 h-3 rounded-full ring-1 ring-white shadow" style="background: {{ $preset['primary'] }};"></span>
                                <span class="w-3 h-3 rounded-full ring-1 ring-white shadow" style="background: {{ $preset['accent'] }};"></span>
                            </span>
                            {{ $preset['label'] }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Contrast warning --}}
                <div x-show="contrastWarning" x-cloak
                     class="flex items-start gap-2.5 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-xs text-amber-700">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    <span><strong>Low contrast warning:</strong> The sidebar text may be hard to read on this background. WCAG requires a 3:1 ratio minimum.</span>
                </div>

                {{-- Color picker grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @php
                        $pickers = [
                            ['key' => 'sidebar_bg',   'label' => 'Sidebar Background', 'hint' => 'Main nav rail color'],
                            ['key' => 'sidebar_text', 'label' => 'Sidebar Text',       'hint' => 'Nav links & icons'],
                            ['key' => 'primary',      'label' => 'Primary Action',     'hint' => 'Buttons & active states'],
                            ['key' => 'accent',       'label' => 'Accent',             'hint' => 'Badges & highlights'],
                        ];
                    @endphp
                    @foreach($pickers as $pk)
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">{{ $pk['label'] }}</label>
                        <div class="relative group cursor-pointer">
                            <div class="w-full h-14 rounded-xl border-2 border-slate-200 group-hover:border-slate-300 overflow-hidden transition-all shadow-sm"
                                 :style="'background:' + branding['{{ $pk['key'] }}']">
                                <input type="color" name="branding_{{ $pk['key'] }}"
                                       x-model="branding['{{ $pk['key'] }}']"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                            <div class="mt-1.5 flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full border border-slate-200 shadow-sm flex-shrink-0"
                                      :style="'background:' + branding['{{ $pk['key'] }}']"></span>
                                <span class="text-xs text-slate-500 font-mono" x-text="branding['{{ $pk['key'] }}']"></span>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400">{{ $pk['hint'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="px-6 py-5">
                <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-3">Live Preview</p>
                <div id="branding-preview" class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm select-none">
                    {{-- Mini sidebar + content --}}
                    <div class="flex h-40">
                        {{-- Sidebar --}}
                        <div class="prev-sidebar w-14 flex flex-col items-center pt-3 gap-3 flex-shrink-0 transition-colors duration-200"
                             :style="'background:' + branding.sidebar_bg">
                            <div class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center">
                                <div class="w-3 h-3 rounded-sm" :style="'background:' + branding.primary"></div>
                            </div>
                            @foreach([1,2,3,4] as $i)
                            <div class="prev-sidebar-text w-8 h-1.5 rounded-full opacity-60 transition-colors duration-200"
                                 :style="'background:' + branding.sidebar_text"></div>
                            @endforeach
                        </div>
                        {{-- Main area --}}
                        <div class="flex-1 bg-slate-50 p-3 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="h-2 w-20 bg-slate-200 rounded-full"></div>
                                <div class="prev-btn h-5 w-14 rounded-lg flex items-center justify-center transition-colors duration-200"
                                     :style="'background:' + branding.primary">
                                    <div class="h-1 w-8 rounded-full bg-white/70"></div>
                                </div>
                            </div>
                            <div class="h-1.5 w-24 bg-slate-200 rounded-full"></div>
                            <div class="h-1.5 w-16 bg-slate-200 rounded-full"></div>
                            <div class="flex gap-1.5 mt-3">
                                <div class="prev-accent h-4 w-10 rounded-full transition-colors duration-200"
                                     :style="'background:' + branding.accent"></div>
                                <div class="h-4 w-14 bg-slate-200 rounded-full"></div>
                            </div>
                            <div class="h-14 rounded-xl bg-white border border-slate-200 mt-1"></div>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 px-3 py-2 flex items-center gap-1.5 bg-white">
                        <div class="w-2 h-2 rounded-full" :style="'background:' + branding.primary"></div>
                        <p class="text-[10px] text-slate-400">Admin dashboard preview</p>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400 mt-2 text-center">Updates apply to your live admin panel after saving.</p>
            </div>
        </div>
    </div>

    {{-- ── Sticky save bar (desktop) ──────────────────────────────────── --}}
    <div class="hidden xl:block sticky bottom-6 mt-6">
        <div class="max-w-xs ml-auto bg-white rounded-2xl border border-slate-200 shadow-xl shadow-slate-200/60 p-4 flex items-center gap-3">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-700">Ready to save?</p>
                <p class="text-xs text-slate-400 mt-0.5">Changes apply to your live site instantly.</p>
            </div>
            <button type="submit"
                    class="shrink-0 flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all shadow-md shadow-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save
            </button>
        </div>
    </div>
</form>

<script>
document.getElementById('theme-search').addEventListener('input', function () {
    var q = this.value.toLowerCase().trim();
    var cards = document.querySelectorAll('.theme-card');
    var visible = 0;
    cards.forEach(function (card) {
        var match = !q || card.dataset.name.includes(q);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('theme-empty').style.display = visible ? 'none' : '';
});
</script>
@endsection
