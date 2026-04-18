@props(['testimonial'])

<div class="bg-white border border-zinc-100 shadow-sm rounded-[28px] p-8 flex flex-col justify-between hover:shadow-xl transition-all duration-300 relative">
  <div class="absolute top-8 right-8 text-indigo-100 text-5xl">
    <i class="fa-solid fa-quote-right"></i>
  </div>
  
  <div class="relative z-10">
    <div class="flex text-indigo-500 text-sm mb-5 gap-0.5">
      @for($i = 1; $i <= 5; $i++)
        <i class="fa-{{ $i <= $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
      @endfor
    </div>
    <p class="text-gray-600 leading-relaxed italic mb-8 font-medium">
      "{{ $testimonial->message }}"
    </p>
  </div>
  
  <div class="flex items-center gap-3 mt-auto">
    <div class="w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm overflow-hidden border border-indigo-100 shrink-0">
      @if($testimonial->photo)
        <img src="{{ $testimonial->image_url }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
      @elseif($testimonial->user && $testimonial->user->avatar)
        <img src="{{ $testimonial->user->avatar }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
      @else
        {{ strtoupper(substr($testimonial->name, 0, 2)) }}
      @endif
    </div>
    
    <div>
      <div class="flex items-center gap-1.5">
         <p class="font-bold text-neutral-900 text-sm leading-tight">{{ $testimonial->name }}</p>
         @if($testimonial->is_verified)
           <i class="fa-solid fa-circle-check text-blue-500 text-[10px]" title="Pelanggan Terverifikasi"></i>
         @endif
      </div>
      <p class="text-gray-500 text-xs mt-0.5">{{ $testimonial->role ?? 'Pelanggan Setia' }}</p>
    </div>
  </div>
</div>
