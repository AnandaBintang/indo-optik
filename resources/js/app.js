import "./bootstrap";

import Alpine from "alpinejs";
import "@fortawesome/fontawesome-free/css/all.min.css";

window.Alpine = Alpine;

Alpine.start();

/* =========================================
   IndoOptik — Scroll Animations
   ========================================= */
function initScrollAnimations() {
    const els = document.querySelectorAll("[data-animate]");
    if (!els.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animated");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1, rootMargin: "0px 0px -40px 0px" },
    );

    els.forEach((el) => observer.observe(el));
}

/* =========================================
   IndoOptik — Stagger Animations
   ========================================= */
function initStaggerAnimations() {
    document.querySelectorAll("[data-stagger]").forEach((container) => {
        const children = container.children;
        Array.from(children).forEach((child, i) => {
            child.setAttribute("data-animate", "");
            child.style.transitionDelay = `${i * 0.08}s`;
        });
    });
}

/* =========================================
   IndoOptik — Counter Animations
   ========================================= */
function animateCounter(el, target, duration = 1500) {
    const step = 16;
    const increment = target / (duration / step);
    let current = 0;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        el.textContent = Math.round(current).toLocaleString("id-ID");
    }, step);
}

function initCounters() {
    const counters = document.querySelectorAll("[data-count]");
    if (!counters.length) return;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const target = parseInt(
                        entry.target.getAttribute("data-count"),
                        10,
                    );
                    animateCounter(entry.target, target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 },
    );

    counters.forEach((el) => observer.observe(el));
}

/* =========================================
   IndoOptik — Navbar Scroll Effect
   ========================================= */
function initNavbarScroll() {
    const navbar = document.getElementById("main-navbar");
    if (!navbar) return;

    window.addEventListener(
        "scroll",
        () => {
            navbar.classList.toggle("scrolled", window.scrollY > 8);
        },
        { passive: true },
    );
}

/* =========================================
   IndoOptik — Mobile Drawer
   ========================================= */
function initMobileDrawer() {
    const hamburgerBtn = document.getElementById("hamburger-btn");
    const hamburgerIcon = document.getElementById("hamburger-icon");
    const mobileDrawer = document.getElementById("mobile-drawer");
    const drawerClose = document.getElementById("drawer-close");
    const drawerOverlay = document.getElementById("drawer-overlay");

    if (!hamburgerBtn || !mobileDrawer) return;

    const DRAWER_CLOSE_MS = 240;
    let unlockTimer = null;

    function lockBodyScroll() {
        if (unlockTimer) {
            clearTimeout(unlockTimer);
            unlockTimer = null;
        }
        const scrollCompensation = Math.max(
            window.innerWidth - document.documentElement.clientWidth,
            0,
        );
        document.body.style.setProperty(
            "--scrollbar-compensation",
            `${scrollCompensation}px`,
        );
        document.body.classList.add("drawer-open");
    }

    function unlockBodyScroll() {
        document.body.classList.remove("drawer-open");
        document.body.style.removeProperty("--scrollbar-compensation");
    }

    function openDrawer() {
        mobileDrawer.classList.add("open");
        hamburgerBtn.setAttribute("aria-expanded", "true");
        if (hamburgerIcon) hamburgerIcon.className = "fa-solid fa-xmark";
        lockBodyScroll();
    }

    function closeDrawer() {
        mobileDrawer.classList.remove("open");
        hamburgerBtn.setAttribute("aria-expanded", "false");
        if (hamburgerIcon) hamburgerIcon.className = "fa-solid fa-bars";

        if (unlockTimer) clearTimeout(unlockTimer);
        unlockTimer = window.setTimeout(() => {
            unlockBodyScroll();
            unlockTimer = null;
        }, DRAWER_CLOSE_MS);
    }

    function toggleDrawer() {
        if (mobileDrawer.classList.contains("open")) {
            closeDrawer();
        } else {
            openDrawer();
        }
    }

    hamburgerBtn.addEventListener("click", toggleDrawer);
    if (drawerClose) drawerClose.addEventListener("click", closeDrawer);
    if (drawerOverlay) drawerOverlay.addEventListener("click", closeDrawer);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeDrawer();
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth >= 1024) closeDrawer();
    });

    mobileDrawer
        .querySelectorAll(".mobile-drawer-nav a, .mobile-drawer-footer a")
        .forEach((link) => {
            link.addEventListener("click", closeDrawer);
        });
}

/* =========================================
   IndoOptik — Cart Badge
   ========================================= */
function initCartBadge() {
    try {
        const qty = parseInt(
            localStorage.getItem("indooptik_cart_qty") || "0",
            10,
        );
        const badge = document.getElementById("cart-badge");
        if (badge && qty > 0) {
            badge.textContent = qty;
            badge.style.display = "flex";
        }
    } catch (e) {
        /* ignore */
    }
}

/* =========================================
   IndoOptik — Catalog Tabs
   ========================================= */
function initCatalogTabs() {
    const tabBtns = document.querySelectorAll("[data-tab-btn]");
    const tabPanels = document.querySelectorAll("[data-tab-panel]");

    if (!tabBtns.length) return;

    tabBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const target = btn.getAttribute("data-tab-btn");

            tabBtns.forEach((b) => {
                const isActive = b.getAttribute("data-tab-btn") === target;
                b.classList.toggle("border-indigo-500", isActive);
                b.classList.toggle("text-indigo-600", isActive);
                b.classList.toggle("border-transparent", !isActive);
                b.classList.toggle("text-gray-500", !isActive);
                if (isActive) {
                    b.classList.remove("font-medium");
                    b.classList.add("font-semibold");
                } else {
                    b.classList.add("font-medium");
                    b.classList.remove("font-semibold");
                }
            });

            tabPanels.forEach((panel) => {
                const show = panel.getAttribute("data-tab-panel") === target;
                if (show) {
                    panel.removeAttribute("hidden");
                } else {
                    panel.setAttribute("hidden", "");
                }
            });
        });
    });
}

/* =========================================
   IndoOptik — Toast Notification
   ========================================= */
const ToastIcons = {
    success: "fa-solid fa-circle-check",
    error: "fa-solid fa-circle-xmark",
    warning: "fa-solid fa-triangle-exclamation",
    info: "fa-solid fa-circle-info",
};

let _container = null;

window.showToast = function (
    type = "info",
    title = "",
    msg = "",
    duration = 4000,
) {
    if (!_container) {
        _container = document.createElement("div");
        _container.className = "toast-container";
        document.body.appendChild(_container);
    }

    const container = _container;
    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-icon"><i class="${ToastIcons[type] || ToastIcons.info}"></i></div>
        <div class="toast-body">
            ${title ? `<div class="toast-title">${title}</div>` : ""}
            ${msg ? `<div class="toast-msg">${msg}</div>` : ""}
        </div>
        <button class="toast-close" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
    `;

    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add("show"));

    const dismiss = () => {
        toast.classList.add("hide");
        toast.addEventListener("transitionend", () => toast.remove(), {
            once: true,
        });
    };

    toast.querySelector(".toast-close").addEventListener("click", dismiss);
    if (duration > 0) setTimeout(dismiss, duration);
};

/* =========================================
   IndoOptik — Product Page
   ========================================= */
const COLOR_VARIANTS = {
    hitam: {
        label: "Hitam",
        color: "#1a1a1a",
        images: [
            "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&q=80&w=800",
        ],
    },
    coklat: {
        label: "Coklat",
        color: "#8B5E3C",
        images: [
            "https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&q=80&w=800",
        ],
    },
    biru: {
        label: "Biru",
        color: "#3b5bdb",
        images: [
            "https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&q=80&w=800",
        ],
    },
    tortoise: {
        label: "Tortoise",
        color: "#c1440e",
        images: [
            "https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&q=80&w=800",
            "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&q=80&w=800",
        ],
    },
};

const LENS_VARIANTS = {
    standard: {
        label: "Standar",
        desc: "Lensa bening standar",
        priceAddon: 0,
        icon: "fa-solid fa-eye",
    },
    bluelight: {
        label: "Anti Blue Light",
        desc: "Proteksi sinar biru layar",
        priceAddon: 150000,
        icon: "fa-solid fa-display",
    },
    antirad: {
        label: "Anti Radiasi",
        desc: "Coating anti silau premium",
        priceAddon: 100000,
        icon: "fa-solid fa-shield-halved",
    },
    photochromic: {
        label: "Photochromic",
        desc: "Otomatis gelap di sinar matahari",
        priceAddon: 200000,
        icon: "fa-solid fa-sun",
    },
};

function formatIDR(n) {
    return "Rp " + Math.round(n).toLocaleString("id-ID");
}

function initProductPage() {
    const mainImg = document.getElementById("main-product-img");
    const colorContainer = document.getElementById("color-swatches");
    const thumbContainer = document.getElementById("thumb-container");
    const lensContainer = document.getElementById("lens-options");
    const priceEl = document.getElementById("product-price");
    const priceAddonEl = document.getElementById("price-addon");
    const promoInput = document.getElementById("promo-code-input");
    const promoApplyBtn = document.getElementById("apply-promo-btn");
    const promoRemoveBtn = document.getElementById("remove-promo-btn");
    const promoFeedback = document.getElementById("promo-feedback");
    const promoDiscountEl = document.getElementById("promo-discount");

    if (!mainImg) return;

    // Read base price from data attribute or default
    const BASE_PRICE = parseInt(
        mainImg.closest("main")?.dataset.basePrice || "299000",
        10,
    );
    const ORIG_PRICE = parseInt(
        mainImg.closest("main")?.dataset.origPrice || "399000",
        10,
    );

    // Product state
    let state = {
        selectedColor: "hitam",
        selectedLens: "standard",
        selectedThumb: 0,
        delivery: "pickup",
        appliedPromo: null,
    };

    // Load saved state
    try {
        const saved = JSON.parse(
            localStorage.getItem("indooptik_product_state") || "{}",
        );
        if (saved.selectedColor && COLOR_VARIANTS[saved.selectedColor])
            state.selectedColor = saved.selectedColor;
        if (saved.selectedLens && LENS_VARIANTS[saved.selectedLens])
            state.selectedLens = saved.selectedLens;
    } catch (e) {
        /* ignore */
    }

    function saveState() {
        try {
            localStorage.setItem(
                "indooptik_product_state",
                JSON.stringify({
                    selectedColor: state.selectedColor,
                    selectedLens: state.selectedLens,
                }),
            );
        } catch (e) {
            /* ignore */
        }
    }

    function updateMainImage(src) {
        if (!mainImg) return;
        mainImg.classList.add("loading");
        const tmp = new Image();
        tmp.onload = () => {
            mainImg.src = src;
            mainImg.classList.remove("loading");
        };
        tmp.src = src;
    }

    function renderThumbnails() {
        if (!thumbContainer) return;
        const images = COLOR_VARIANTS[state.selectedColor].images;
        thumbContainer.innerHTML = images
            .map(
                (src, i) => `
            <button class="aspect-square rounded-xl overflow-hidden border-2 ${i === state.selectedThumb ? "border-indigo-500 shadow-md" : "border-zinc-200"} transition-all cursor-pointer hover:border-indigo-400" data-thumb="${i}">
                <img src="${src}" alt="Thumbnail ${i + 1}" class="w-full h-full object-cover" loading="lazy" />
            </button>
        `,
            )
            .join("");

        thumbContainer.querySelectorAll("[data-thumb]").forEach((btn) => {
            btn.addEventListener("click", () => {
                state.selectedThumb = parseInt(
                    btn.getAttribute("data-thumb"),
                    10,
                );
                updateMainImage(images[state.selectedThumb]);
                renderThumbnails();
            });
        });
    }

    function renderColorSwatches() {
        if (!colorContainer) return;
        colorContainer.innerHTML = Object.entries(COLOR_VARIANTS)
            .map(
                ([key, v]) => `
            <button
                class="color-swatch ${state.selectedColor === key ? "selected" : ""}"
                style="background:${v.color};"
                data-color="${key}"
                title="${v.label}"
                aria-label="${v.label}"
            ></button>
        `,
            )
            .join("");

        const lbl = document.getElementById("selected-color-label");
        if (lbl) lbl.textContent = COLOR_VARIANTS[state.selectedColor].label;

        colorContainer.querySelectorAll("[data-color]").forEach((btn) => {
            btn.addEventListener("click", () => {
                state.selectedColor = btn.getAttribute("data-color");
                state.selectedThumb = 0;
                saveState();
                renderColorSwatches();
                renderThumbnails();
                updateMainImage(COLOR_VARIANTS[state.selectedColor].images[0]);
            });
        });
    }

    function renderLensOptions() {
        if (!lensContainer) return;
        lensContainer.innerHTML = Object.entries(LENS_VARIANTS)
            .map(
                ([key, v]) => `
            <label class="lens-option ${state.selectedLens === key ? "selected" : ""}" data-lens="${key}">
                <input type="radio" name="lens" value="${key}" ${state.selectedLens === key ? "checked" : ""} />
                <span class="lens-option-icon"><i class="${v.icon}"></i></span>
                <span class="lens-option-body">
                    <span class="lens-option-name">${v.label}</span>
                    <span class="lens-option-desc">${v.desc}</span>
                </span>
                ${v.priceAddon > 0 ? `<span class="lens-option-price">+${formatIDR(v.priceAddon)}</span>` : ""}
            </label>
        `,
            )
            .join("");

        lensContainer.querySelectorAll("[data-lens]").forEach((opt) => {
            opt.addEventListener("click", () => {
                state.selectedLens = opt.getAttribute("data-lens");
                saveState();
                renderLensOptions();
                updatePrice();
            });
        });
    }

    function getPricingSummary() {
        const lensAddon = LENS_VARIANTS[state.selectedLens]?.priceAddon || 0;
        const subtotal = BASE_PRICE + lensAddon;
        let promoDiscount = 0;
        if (state.appliedPromo) {
            if (state.appliedPromo.type === "percentage") {
                promoDiscount = subtotal * (state.appliedPromo.value / 100);
                if (state.appliedPromo.maxDiscount)
                    promoDiscount = Math.min(
                        promoDiscount,
                        state.appliedPromo.maxDiscount,
                    );
            } else {
                promoDiscount = state.appliedPromo.value;
            }
        }
        const total = Math.max(0, subtotal - promoDiscount);
        const discountPercent =
            ORIG_PRICE > BASE_PRICE
                ? Math.round(((ORIG_PRICE - total) / ORIG_PRICE) * 100)
                : 0;
        return { lensAddon, subtotal, promoDiscount, total, discountPercent };
    }

    function updatePrice() {
        const pricing = getPricingSummary();

        if (priceEl) priceEl.textContent = formatIDR(pricing.total);
        if (priceAddonEl) {
            if (pricing.lensAddon > 0) {
                priceAddonEl.style.display = "";
                priceAddonEl.innerHTML = `Frame: ${formatIDR(BASE_PRICE)} + Lensa: <span>${formatIDR(pricing.lensAddon)}</span>`;
            } else {
                priceAddonEl.style.display = "none";
            }
        }
        if (promoDiscountEl) {
            if (pricing.promoDiscount > 0) {
                promoDiscountEl.style.display = "";
                promoDiscountEl.textContent = `Diskon promo: -${formatIDR(pricing.promoDiscount)}`;
            } else {
                promoDiscountEl.style.display = "none";
            }
        }

        const badge = document.getElementById("discount-badge");
        if (badge) {
            if (pricing.discountPercent > 0) {
                badge.textContent = `-${pricing.discountPercent}%`;
                badge.style.display = "";
            } else {
                badge.style.display = "none";
            }
        }
    }

    // Promo handling
    const PROMO_CODES = {
        HEMAT10: {
            label: "Hemat 10%",
            type: "percentage",
            value: 10,
            minSubtotal: 0,
            maxDiscount: 100000,
            description: "Diskon 10% maksimal Rp 100.000",
        },
        LENSA50: {
            label: "Lensa 50rb",
            type: "fixed",
            value: 50000,
            minSubtotal: 300000,
            description: "Potongan Rp 50.000 untuk pembelian min. Rp 300.000",
        },
        BARU30: {
            label: "Pengguna Baru 30%",
            type: "percentage",
            value: 30,
            minSubtotal: 0,
            maxDiscount: 200000,
            description: "Diskon 30% khusus pengguna baru, maks. Rp 200.000",
        },
    };

    function setPromoFeedback(msg, isError = false) {
        if (!promoFeedback) return;
        promoFeedback.style.display = "";
        promoFeedback.textContent = msg;
        promoFeedback.style.color = isError
            ? "var(--color-danger)"
            : "var(--color-success)";
    }

    function clearPromoFeedback() {
        if (!promoFeedback) return;
        promoFeedback.style.display = "none";
        promoFeedback.textContent = "";
    }

    function refreshPromoUI() {
        if (state.appliedPromo) {
            if (promoApplyBtn) promoApplyBtn.style.display = "none";
            if (promoRemoveBtn) promoRemoveBtn.style.display = "";
            if (promoInput) promoInput.disabled = true;
        } else {
            if (promoApplyBtn) promoApplyBtn.style.display = "";
            if (promoRemoveBtn) promoRemoveBtn.style.display = "none";
            if (promoInput) promoInput.disabled = false;
        }
    }

    if (promoApplyBtn) {
        promoApplyBtn.addEventListener("click", () => {
            const code = promoInput?.value.trim().toUpperCase();
            if (!code) {
                setPromoFeedback("Masukkan kode promo terlebih dahulu.", true);
                return;
            }

            const promo = PROMO_CODES[code];
            if (!promo) {
                setPromoFeedback("Kode promo tidak valid.", true);
                return;
            }

            const pricing = getPricingSummary();
            if (promo.minSubtotal && pricing.subtotal < promo.minSubtotal) {
                setPromoFeedback(
                    `Minimum pembelian ${formatIDR(promo.minSubtotal)} untuk kode ini.`,
                    true,
                );
                return;
            }

            state.appliedPromo = { ...promo, code };
            setPromoFeedback(
                `Kode "${code}" berhasil diterapkan! ${promo.description}`,
                false,
            );
            refreshPromoUI();
            updatePrice();
        });
    }

    if (promoRemoveBtn) {
        promoRemoveBtn.addEventListener("click", () => {
            state.appliedPromo = null;
            if (promoInput) {
                promoInput.value = "";
                promoInput.disabled = false;
            }
            clearPromoFeedback();
            refreshPromoUI();
            updatePrice();
        });
    }

    // Delivery options
    document.querySelectorAll("[data-delivery]").forEach((opt) => {
        opt.addEventListener("click", () => {
            state.delivery = opt.getAttribute("data-delivery");
            document.querySelectorAll("[data-delivery]").forEach((o) => {
                o.classList.toggle("selected", o === opt);
            });
        });
    });

    // Add to cart
    const addToCartBtn = document.getElementById("add-to-cart-btn");
    if (addToCartBtn) {
        addToCartBtn.addEventListener("click", () => {
            const pricing = getPricingSummary();
            const color =
                COLOR_VARIANTS[state.selectedColor]?.label ||
                state.selectedColor;
            const lens =
                LENS_VARIANTS[state.selectedLens]?.label || state.selectedLens;

            try {
                localStorage.setItem("indooptik_cart_qty", "1");
                localStorage.setItem(
                    "indooptik_cart",
                    JSON.stringify({
                        name:
                            document.querySelector("h1")?.textContent?.trim() ||
                            "Produk",
                        color,
                        lens,
                        delivery: state.delivery,
                        total: pricing.total,
                        promoCode: state.appliedPromo?.code || null,
                    }),
                );
            } catch (e) {
                /* ignore */
            }

            const badge = document.getElementById("cart-badge");
            if (badge) {
                badge.textContent = "1";
                badge.style.display = "flex";
            }

            showToast(
                "success",
                "Ditambahkan!",
                `${color} + Lensa ${lens} berhasil masuk keranjang.`,
            );
        });
    }

    // WhatsApp order
    const waBtn = document.getElementById("wa-order-btn");
    const whatsappNumber =
        document.getElementById("main-navbar")?.dataset.waNumber ||
        "6281234567890";
    if (waBtn) {
        waBtn.addEventListener("click", () => {
            const color =
                COLOR_VARIANTS[state.selectedColor]?.label ||
                state.selectedColor;
            const lens =
                LENS_VARIANTS[state.selectedLens]?.label || state.selectedLens;
            const delivery =
                state.delivery === "pickup"
                    ? "Ambil di Toko"
                    : "Antar ke Rumah";
            const pricing = getPricingSummary();
            const productName =
                document.querySelector("h1")?.textContent?.trim() || "Produk";
            const promoLine = state.appliedPromo
                ? `\n🏷️ Promo: ${state.appliedPromo.code} (-${formatIDR(pricing.promoDiscount)})`
                : "";

            const msg = `Halo IndoOptik! Saya ingin memesan:\n\n🕶️ ${productName}\n🎨 Warna: ${color}\n🔍 Lensa: ${lens}\n📦 Pengiriman: ${delivery}${promoLine}\n\n💰 Total: ${formatIDR(pricing.total)}\n\nMohon konfirmasinya. Terima kasih!`;
            window.open(
                `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(msg)}`,
                "_blank",
            );
        });
    }

    // Upload zone
    const uploadZone = document.getElementById("upload-zone");
    if (uploadZone) {
        const input = document.createElement("input");
        input.type = "file";
        input.accept = "image/*,.pdf";
        uploadZone.addEventListener("click", () => input.click());
        input.addEventListener("change", () => {
            if (input.files[0]) {
                uploadZone.innerHTML = `<i class="fa-solid fa-check-circle text-3xl text-green-500 mb-2"></i><p class="text-sm font-medium text-neutral-900">${input.files[0].name}</p><p class="text-xs text-gray-500 mt-1">Klik untuk ganti file</p>`;
            }
        });
    }

    // Lazy images
    document.querySelectorAll('img[loading="lazy"]').forEach((img) => {
        if ("loading" in HTMLImageElement.prototype) return;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    img.src = img.dataset.src || img.src;
                    observer.unobserve(img);
                }
            });
        });
        observer.observe(img);
    });

    // Initialize
    renderColorSwatches();
    renderThumbnails();
    renderLensOptions();
    updateMainImage(COLOR_VARIANTS[state.selectedColor].images[0]);
    updatePrice();
    refreshPromoUI();
}

/* =========================================
   IndoOptik — Appointment / Calendar
   ========================================= */
function initCalendar() {
    const calDays = document.querySelectorAll("[data-cal-day]");
    const timeBtns = document.querySelectorAll("[data-time-slot]");

    calDays.forEach((day) => {
        day.addEventListener("click", () => {
            calDays.forEach((d) =>
                d.classList.remove("bg-indigo-600", "text-white", "selected"),
            );
            day.classList.add("bg-indigo-600", "text-white", "selected");
        });
    });

    timeBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            timeBtns.forEach((b) =>
                b.classList.remove(
                    "border-indigo-500",
                    "bg-indigo-50",
                    "text-indigo-600",
                    "selected",
                ),
            );
            btn.classList.add(
                "border-indigo-500",
                "bg-indigo-50",
                "text-indigo-600",
                "selected",
            );
        });
    });
}

/* =========================================
   IndoOptik — WhatsApp Booking (services page)
   ========================================= */
function initWhatsAppBooking() {
    const form = document.getElementById("booking-form");
    const btn = document.getElementById("booking-wa-btn");
    if (!form || !btn) return;

    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const name =
            form.querySelector('[name="customer_name"]')?.value?.trim() || "";
        const phone =
            form.querySelector('[name="customer_phone"]')?.value?.trim() || "";
        const service =
            form.querySelector(
                '[name="service_type"] input:checked + div [data-service-label]',
            )?.textContent ||
            form.querySelector('[name="service_type"]')?.value ||
            "";
        const selectedDay =
            form
                .querySelector("[data-cal-day].selected")
                ?.textContent?.trim() || "";
        const selectedTime =
            form
                .querySelector("[data-time-slot].selected")
                ?.textContent?.trim() || "";
        const whatsappNumber =
            document.getElementById("main-navbar")?.dataset.waNumber ||
            "6281234567890";

        if (!name || !phone) {
            showToast(
                "error",
                "Form Tidak Lengkap",
                "Mohon isi nama dan nomor telepon.",
            );
            return;
        }

        const msg = `Halo IndoOptik! Saya ingin membuat janji:\n\n👤 Nama: ${name}\n📞 Telepon: ${phone}\n🔧 Layanan: ${service}\n📅 Tanggal: ${selectedDay}\n⏰ Waktu: ${selectedTime}\n\nMohon konfirmasinya. Terima kasih!`;
        window.open(
            `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(msg)}`,
            "_blank",
        );
    });
}

/* =========================================
   INIT
   ========================================= */
document.addEventListener("DOMContentLoaded", () => {
    initStaggerAnimations();
    initScrollAnimations();
    initCounters();
    initNavbarScroll();
    initMobileDrawer();
    initCartBadge();
    initCatalogTabs();
    initProductPage();
    initCalendar();
    initWhatsAppBooking();
});
