{{-- resources/views/partials/footer.blade.php --}}
<footer class="footer-inner" aria-label="Footer IndoOptik" style="background-color:#0f172a;">
  <div class="footer-grid">

    {{-- Brand --}}
    <div>
      <div class="footer-logo">IndoOptik</div>
      <p class="footer-tagline">Solusi optik modern yang praktis dan terpercaya. Kacamata berkualitas untuk semua kalangan di seluruh Indonesia.</p>
      <div class="footer-social-row">
        @if(!empty($settings['facebook_url'] ?? ''))
          <a href="{{ $settings['facebook_url'] }}" class="footer-social-btn" aria-label="Facebook" title="Facebook" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
        @else
          <a href="#" class="footer-social-btn" aria-label="Facebook" title="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
        @endif

        @if(!empty($settings['instagram_url'] ?? ''))
          <a href="{{ $settings['instagram_url'] }}" class="footer-social-btn" aria-label="Instagram" title="Instagram" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-instagram"></i>
          </a>
        @else
          <a href="#" class="footer-social-btn" aria-label="Instagram" title="Instagram">
            <i class="fa-brands fa-instagram"></i>
          </a>
        @endif

        @if(!empty($settings['tiktok_url'] ?? ''))
          <a href="{{ $settings['tiktok_url'] }}" class="footer-social-btn" aria-label="TikTok" title="TikTok" target="_blank" rel="noopener noreferrer">
            <i class="fa-brands fa-tiktok"></i>
          </a>
        @else
          <a href="#" class="footer-social-btn" aria-label="TikTok" title="TikTok">
            <i class="fa-brands fa-tiktok"></i>
          </a>
        @endif
      </div>
    </div>

    {{-- Kontak --}}
    <div>
      <h5 class="footer-heading">Alamat &amp; Kontak</h5>
      <ul class="footer-contact-list">
        <li>
          <span class="fi-icon"><i class="fa-solid fa-location-dot"></i></span>
          <span>{{ $settings['address'] ?? 'Jl. Optik Utama No. 123' }}<br>{{ $settings['city'] ?? 'Jakarta Pusat, 10110' }}</span>
        </li>
        <li>
          <span class="fi-icon"><i class="fa-solid fa-phone"></i></span>
          <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '+6281234567890') }}" style="color:inherit;">
            {{ $settings['phone'] ?? '+62 812-3456-7890' }}
          </a>
        </li>
        <li>
          <span class="fi-icon"><i class="fa-solid fa-envelope"></i></span>
          <a href="mailto:{{ $settings['email'] ?? 'info@indooptik.com' }}" style="color:inherit;">
            {{ $settings['email'] ?? 'info@indooptik.com' }}
          </a>
        </li>
        <li>
          <span class="fi-icon wa"><i class="fa-brands fa-whatsapp"></i></span>
          <a href="https://wa.me/{{ $waNumber ?? $settings['whatsapp_number'] ?? '6281234567890' }}"
             target="_blank"
             rel="noopener noreferrer"
             style="color:inherit;">
            Chat WhatsApp
          </a>
        </li>
      </ul>
    </div>

    {{-- Navigation --}}
    <div>
      <h5 class="footer-heading">Navigasi</h5>
      <ul class="footer-nav-list">
        <li>
          <a href="{{ route('home') }}">
            <i class="fa-solid fa-chevron-right"></i>Beranda
          </a>
        </li>
        <li>
          <a href="{{ route('about') }}">
            <i class="fa-solid fa-chevron-right"></i>Tentang Kami
          </a>
        </li>
        <li>
          <a href="{{ route('catalog.index') }}">
            <i class="fa-solid fa-chevron-right"></i>Katalog
          </a>
        </li>
        <li>
          <a href="{{ route('services.index') }}">
            <i class="fa-solid fa-chevron-right"></i>Layanan
          </a>
        </li>
        <li>
          <a href="{{ route('cart.index') }}">
            <i class="fa-solid fa-chevron-right"></i>Keranjang
          </a>
        </li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <p>&copy; {{ $settings['copyright_year'] ?? date('Y') }} {{ $settings['site_name'] ?? 'IndoOptik' }}. Seluruh hak cipta dilindungi.</p>
  </div>
</footer>
