@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('breadcrumb')
  <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Admin</a>
  <i class="fa-solid fa-chevron-right text-[10px]"></i>
  <span class="text-neutral-900 font-semibold">Pengaturan</span>
@endsection

@section('content')
<div class="flex flex-col md:flex-row gap-6 relative" x-data="{ activeTab: 'general' }">
  
  <!-- Sidebar Navigation Menu Settings -->
  <div class="w-full md:w-64 shrink-0">
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm p-3 sticky top-24">
      <nav class="flex flex-col gap-1">
        @foreach($groupLabels as $groupKey => $groupLabel)
          <button 
            type="button"
            @click="activeTab = '{{ $groupKey }}'"
            :class="activeTab === '{{ $groupKey }}' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-neutral-600 hover:bg-neutral-50 font-medium'"
            class="text-left px-4 py-3 rounded-xl transition-all flex items-center justify-between group"
          >
            <span class="flex items-center gap-3">
              @php
                 $icon = match($groupKey) {
                    'general' => 'fa-sliders',
                    'contact' => 'fa-address-book',
                    'social'  => 'fa-hashtag',
                    'seo'     => 'fa-magnifying-glass-chart',
                    'homepage'=> 'fa-house-chimney-window',
                    'shipping'=> 'fa-truck-fast',
                    default   => 'fa-gear'
                 };
              @endphp
              <i class="fa-solid {{ $icon }} w-4 text-center" :class="activeTab === '{{ $groupKey }}' ? 'text-indigo-500' : 'text-gray-400 group-hover:text-indigo-400'"></i>
              {{ $groupLabel }}
            </span>
            <i class="fa-solid fa-chevron-right text-[10px] opacity-0 -translate-x-2 transition-all duration-200" :class="activeTab === '{{ $groupKey }}' ? 'opacity-100 translate-x-0' : 'group-hover:opacity-100 group-hover:translate-x-0'"></i>
          </button>
        @endforeach
      </nav>
    </div>
  </div>

  <!-- Content Area -->
  <div class="flex-1">
    <form action="{{ route('admin.settings.update') }}" method="POST">
      @csrf
      @method('PUT')

      @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm border border-red-200">
           <ul class="list-disc pl-5 space-y-1">
              @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
              @endforeach
           </ul>
        </div>
      @endif

      <div class="bg-white rounded-2xl border border-zinc-200 shadow-sm overflow-hidden">
        
        @foreach($groupLabels as $groupKey => $groupLabel)
          <div x-show="activeTab === '{{ $groupKey }}'" class="p-6 md:p-8" style="display: none;">
             
             <div class="mb-8 border-b border-zinc-100 pb-4">
                <h2 class="text-xl font-bold text-neutral-900">{{ $groupLabel }}</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola preferensi dan pengaturan fungsionalitas untuk {{ strtolower($groupLabel) }}.</p>
             </div>

             <div class="space-y-6 max-w-3xl">
                @if(isset($grouped[$groupKey]))
                  @foreach($grouped[$groupKey] as $setting)
                    @php
                       $key = $setting->key;
                       $value = $setting->value;
                       // Determine generic label by replacing underscores and capitalizing
                       $label = ucwords(str_replace('_', ' ', $key));
                       
                       // Specific UI rules based on key heuristics
                       $isTextarea = Str::contains($key, ['description', 'address', 'script', 'tags', 'rules', 'history']);
                       $isEmail = Str::contains($key, ['email']);
                       $isPhone = Str::contains($key, ['phone', 'whatsapp']);
                       $isUrl = Str::contains($key, ['url', 'link', 'facebook', 'instagram', 'twitter']);
                       
                       $inputType = 'text';
                       if ($isEmail) $inputType = 'email';
                       if ($isPhone) $inputType = 'tel';
                       if ($isUrl) $inputType = 'url';

                       $fieldValue = old('settings.'.$key, $value);
                       if ($isUrl && trim((string) $fieldValue) === '#') {
                           $fieldValue = '';
                       }
                    @endphp

                    <div>
                      <label for="setting_{{ $key }}" class="block text-sm font-bold text-neutral-900 mb-2">{{ $label }} <span class="text-xs text-gray-400 font-normal ml-2 font-mono">({{ $key }})</span></label>
                      
                      @if($isTextarea)
                        <textarea 
                          id="setting_{{ $key }}" 
                          name="settings[{{ $key }}]" 
                          rows="4" 
                          class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200"
                        >{{ $fieldValue }}</textarea>
                      @else
                        <input 
                          type="{{ $inputType }}" 
                          id="setting_{{ $key }}" 
                          name="settings[{{ $key }}]" 
                          value="{{ $fieldValue }}"
                          @if($isUrl) placeholder="https://example.com" @endif
                          class="w-full bg-neutral-50 rounded-xl py-3 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500 border border-zinc-200"
                        >
                      @endif

                      <!-- Include hidden input for preserving group association -->
                      <input type="hidden" name="groups[{{ $key }}]" value="{{ $groupKey }}">
                    </div>
                  @endforeach
                @else
                  <div class="p-8 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                     <i class="fa-solid fa-puzzle-piece text-gray-400 text-3xl mb-3"></i>
                     <p class="font-bold text-neutral-900">Kosong</p>
                     <p class="text-sm text-gray-500">Tidak ada pengaturan (database key) dalam kategori ini.</p>
                  </div>
                @endif
             </div>
          </div>
        @endforeach

        <!-- Action Bar -->
        <div class="px-6 md:px-8 py-5 border-t border-zinc-100 bg-neutral-50 flex items-center justify-end">
           <button type="submit" class="btn btn-primary shadow-md shadow-indigo-500/20">
             <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Pengaturan
           </button>
        </div>

      </div>

    </form>
  </div>
</div>
@endsection
