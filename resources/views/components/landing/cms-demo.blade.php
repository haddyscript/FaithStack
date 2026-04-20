<section id="cms-demo" class="py-28 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-16">
            <p class="reveal text-sm font-semibold text-indigo-600 uppercase tracking-widest mb-4">Admin Dashboard</p>
            <h2 class="reveal text-4xl lg:text-5xl font-bold text-slate-900 tracking-tight mb-5" data-delay="1">
                Manage everything<br>from one place
            </h2>
            <p class="reveal text-lg text-slate-500 max-w-xl mx-auto" data-delay="2">
                Intuitive page builder. Click to edit, drag to reorder. Your site updates the moment you save.
            </p>
        </div>

        {{-- App window --}}
        <div x-data="cmsDemo" class="reveal" data-delay="1">
            <div class="relative rounded-2xl overflow-hidden border border-slate-200/80 shadow-[0_32px_80px_rgba(0,0,0,0.10),0_0_0_1px_rgba(0,0,0,0.04)]">

                {{-- Window chrome --}}
                <div class="flex items-center gap-2 px-4 py-3 bg-[#1a1a1e] border-b border-white/[0.05]">
                    <span class="w-3 h-3 rounded-full bg-red-500/70 hover:bg-red-500 transition-colors cursor-pointer"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/70 hover:bg-yellow-500 transition-colors cursor-pointer"></span>
                    <span class="w-3 h-3 rounded-full bg-green-500/70 hover:bg-green-500 transition-colors cursor-pointer"></span>
                    <div class="flex-1 mx-3 px-3 py-1.5 rounded-md bg-white/[0.05] text-[10px] text-white/22 font-mono">
                        admin.faithstack.com — Page Builder
                    </div>
                    {{-- Saving indicator --}}
                    <div x-show="saving"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-90 translate-x-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-end="opacity-0 scale-90"
                         class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/15 border border-emerald-500/25 text-emerald-400 text-[10px] font-semibold flex-shrink-0">
                        <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        Saving…
                    </div>
                </div>

                <div class="flex" style="height: 520px;">

                    {{-- ── Sidebar ── --}}
                    <div class="w-52 flex-shrink-0 bg-[#0f0f11] border-r border-white/[0.05] flex flex-col">

                        {{-- Brand --}}
                        <div class="flex items-center gap-2.5 px-4 py-4 border-b border-white/[0.05]">
                            <div class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4zM8 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H9a1 1 0 01-1-1V4zM15 3a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-white">FaithStack</span>
                        </div>

                        {{-- Nav items --}}
                        <div class="px-2 py-3 space-y-0.5 border-b border-white/[0.05]">
                            @foreach([
                                ['Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['Pages',     'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                ['Media',     'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                ['Donations', 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['Settings',  'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                            ] as [$label, $path])
                            <div class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-white/35 hover:text-white/65 hover:bg-white/[0.04] cursor-pointer transition-all duration-150 cms-sidebar-item">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                                <span class="text-xs font-medium">{{ $label }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Pages list --}}
                        <div class="flex-1 overflow-y-auto px-2 py-3">
                            <p class="text-[9px] font-bold text-white/20 uppercase tracking-widest px-3 mb-2.5">Pages</p>
                            <template x-for="page in pages" :key="page.id">
                                <div @click="selectPage(page.id)"
                                     :class="activePage === page.id
                                        ? 'bg-indigo-600/20 text-white border-indigo-500/30'
                                        : 'text-white/40 hover:text-white/70 hover:bg-white/[0.04] border-transparent'"
                                     class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg cursor-pointer transition-all duration-200 cms-sidebar-item mb-0.5 border">
                                    <span class="text-sm" x-text="page.icon"></span>
                                    <span class="text-xs font-medium flex-1" x-text="page.label"></span>
                                    <div x-show="activePage === page.id"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-75"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="w-1.5 h-1.5 rounded-full bg-indigo-400 flex-shrink-0"></div>
                                </div>
                            </template>
                        </div>

                    </div>

                    {{-- ── Main workspace ── --}}
                    <div class="flex-1 bg-[#f8fafc] flex flex-col overflow-hidden">

                        {{-- Toolbar --}}
                        <div class="flex items-center justify-between px-5 py-3 bg-white border-b border-slate-100 flex-shrink-0 shadow-sm">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-slate-800"
                                      x-text="(pages.find(p => p.id === activePage)?.label || 'Home') + ' Page'"></span>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 text-[11px] font-semibold border border-emerald-100/80">Published</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 border border-slate-200 transition-colors">Preview</button>
                                <button class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">Publish</button>
                            </div>
                        </div>

                        {{-- Section list / page builder --}}
                        <div class="flex-1 overflow-y-auto p-5">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3.5">Page Sections</p>

                            <div class="space-y-2.5">
                                <template x-for="(section, i) in currentSections" :key="section">
                                    <div @click="selectSection(section)"
                                         :class="activeSection === section.toLowerCase()
                                            ? 'border-indigo-200 bg-white shadow-md shadow-indigo-500/[0.06]'
                                            : 'border-slate-100 bg-white hover:border-slate-200 hover:shadow-sm'"
                                         class="relative flex items-center gap-3.5 p-4 rounded-xl border cursor-pointer transition-all duration-200 group">

                                        {{-- Drag handle --}}
                                        <div class="flex flex-col gap-[3px] flex-shrink-0 opacity-20 group-hover:opacity-40 transition-opacity">
                                            <div class="flex gap-[3px]"><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div></div>
                                            <div class="flex gap-[3px]"><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div></div>
                                            <div class="flex gap-[3px]"><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div><div class="w-[3px] h-[3px] rounded-full bg-slate-400"></div></div>
                                        </div>

                                        {{-- Thumbnail --}}
                                        <div class="w-16 h-10 rounded-lg bg-slate-100 flex-shrink-0 overflow-hidden relative">
                                            <div class="absolute inset-0 flex flex-col gap-1 p-1.5">
                                                <div class="skeleton h-2 w-full rounded-sm"></div>
                                                <div class="skeleton h-1.5 w-3/4 rounded-sm"></div>
                                                <div class="flex gap-1 mt-0.5">
                                                    <div class="h-2.5 flex-1 rounded-sm bg-indigo-200/70"></div>
                                                    <div class="h-2.5 flex-1 rounded-sm bg-slate-200/70"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-slate-800" x-text="section"></div>
                                            <div class="text-xs text-slate-400 mt-0.5">Click to edit</div>
                                        </div>

                                        {{-- Active badge --}}
                                        <div x-show="activeSection === section.toLowerCase()"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-90 translate-x-2"
                                             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                                             class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[11px] font-bold flex-shrink-0 border border-indigo-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                            Editing
                                        </div>

                                    </div>
                                </template>
                            </div>

                            {{-- Add section --}}
                            <button class="mt-3 w-full flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-dashed border-slate-200 text-slate-400 hover:text-indigo-500 hover:border-indigo-300 hover:bg-indigo-50/40 text-xs font-semibold transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Add Section
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</section>
