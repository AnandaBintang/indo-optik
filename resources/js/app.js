import "./bootstrap";

import Alpine from "@alpinejs/csp";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "trix";
import "trix/dist/trix.css";

window.Alpine = Alpine;

Alpine.data("productImagePicker", (initialPreview = "") => ({
    mode: "file",
    previewUrl: initialPreview || "",
    url: "",

    get fileUploadClasses() {
        return this.previewUrl && !this.url
            ? "border border-zinc-200 bg-gray-50 p-2"
            : "border-2 border-zinc-300 border-dashed px-6 pt-5 pb-6 flex justify-center bg-neutral-50";
    },

    get hasFilePreview() {
        return Boolean(this.previewUrl && !this.url);
    },

    get showFilePlaceholder() {
        return !this.previewUrl || Boolean(this.url);
    },

    selectFileMode() {
        this.mode = "file";
    },

    selectUrlMode() {
        this.mode = "url";
        this.previewUrl = this.url;
    },

    setUrlPreview() {
        this.previewUrl = this.url;
    },

    clearPreview() {
        this.previewUrl = "";
    },

    handleFileChange(event) {
        this.setFilePreview(event?.target?.files?.[0]);
    },

    setFilePreview(file) {
        if (!file) return;

        if (this.previewUrl && this.previewUrl.startsWith("blob:")) {
            URL.revokeObjectURL(this.previewUrl);
        }

        this.url = "";
        this.previewUrl = URL.createObjectURL(file);
    },
}));

Alpine.start();

document.addEventListener("trix-file-accept", (event) => {
    event.preventDefault();
});

/* =========================================
   IndoOptik — Scroll Animations
   ========================================= */
function initScrollAnimations() {
    const els = document.querySelectorAll("[data-animate]");
    if (!els.length) return;

    if (!("IntersectionObserver" in window)) {
        els.forEach((el) => el.classList.add("animated"));
        return;
    }

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

    els.forEach((el) => {
        el.classList.add("will-animate");
        observer.observe(el);
    });
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
        .querySelectorAll(".mobile-drawer-nav a, .mobile-drawer-footer a, .mobile-drawer-footer button")
        .forEach((link) => {
            link.addEventListener("click", closeDrawer);
        });
}

/* =========================================
   IndoOptik — Navbar User Dropdown
   ========================================= */
function initUserDropdown() {
    const btn = document.getElementById("user-dropdown-btn");
    const menu = document.getElementById("user-dropdown-menu");
    if (!btn || !menu) return;

    function close() {
        menu.style.display = "none";
        btn.setAttribute("aria-expanded", "false");
    }

    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const isOpen = menu.style.display === "block";
        menu.style.display = isOpen ? "none" : "block";
        btn.setAttribute("aria-expanded", String(!isOpen));
    });

    menu.addEventListener("click", (e) => e.stopPropagation());
    document.addEventListener("click", close);
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") close();
    });
}

/* =========================================
   IndoOptik — Admin UI
   ========================================= */
function initAdminSidebar() {
    const toggle = document.getElementById("sidebar-toggle");
    const sidebar = document.querySelector(".admin-sidebar");
    const overlay = document.getElementById("sidebar-overlay");
    if (!toggle || !sidebar || !overlay) return;

    function closeSidebar() {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("hidden");
        toggle.setAttribute("aria-expanded", "false");
    }

    toggle.setAttribute("aria-expanded", "false");
    toggle.addEventListener("click", () => {
        const willOpen = sidebar.classList.contains("-translate-x-full");
        sidebar.classList.toggle("-translate-x-full");
        overlay.classList.toggle("hidden");
        toggle.setAttribute("aria-expanded", String(willOpen));
    });

    overlay.addEventListener("click", closeSidebar);
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeSidebar();
    });
}

function initConfirmForms() {
    document.querySelectorAll(".delete-form, [data-confirm-form]").forEach((form) => {
        form.addEventListener("submit", (e) => {
            const message =
                form.getAttribute("data-confirm") ||
                "Apakah Anda yakin ingin melanjutkan?";
            if (!window.confirm(message)) {
                e.preventDefault();
            }
        });
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
const DEFAULT_COLOR_VARIANTS = {
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

const DEFAULT_LENS_VARIANTS = {
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

const DEFAULT_FRAME_VARIANTS = {
    "full-rim": {
        label: "Full Rim",
        desc: "Frame penuh klasik",
        priceAddon: 0,
        icon: "fa-solid fa-glasses",
    },
    "half-rim": {
        label: "Half Rim",
        desc: "Ringan dengan bagian bawah terbuka",
        priceAddon: 50000,
        icon: "fa-regular fa-circle",
    },
    rimless: {
        label: "Rimless",
        desc: "Minimalis tanpa bingkai penuh",
        priceAddon: 100000,
        icon: "fa-solid fa-feather",
    },
};

function formatIDR(n) {
    return "Rp " + Math.round(n).toLocaleString("id-ID");
}

function readJsonFromElement(id) {
    const el = document.getElementById(id);
    if (!el) return null;
    try {
        return JSON.parse(el.textContent || "null");
    } catch (e) {
        return null;
    }
}

function slugifyKey(value) {
    return String(value || "")
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/(^-|-$)/g, "");
}

function normalizeColorVariants(input, fallbackImages) {
    const imagesFallback = Array.isArray(fallbackImages)
        ? fallbackImages.filter(Boolean)
        : [];
    const base = input || DEFAULT_COLOR_VARIANTS;

    if (Array.isArray(base)) {
        const out = {};
        base.forEach((variant, index) => {
            const key =
                variant?.key ||
                slugifyKey(variant?.label) ||
                `color-${index + 1}`;
            const images = Array.isArray(variant?.images)
                ? variant.images.filter(Boolean)
                : imagesFallback;
            out[key] = {
                label: variant?.label || `Warna ${index + 1}`,
                color: variant?.color || "#111827",
                images: images.length ? images : imagesFallback,
            };
        });
        return Object.keys(out).length ? out : DEFAULT_COLOR_VARIANTS;
    }

    return typeof base === "object" && base ? base : DEFAULT_COLOR_VARIANTS;
}

function normalizeLensVariants(input) {
    const base = input || DEFAULT_LENS_VARIANTS;

    if (Array.isArray(base)) {
        const out = {};
        base.forEach((variant, index) => {
            const key =
                variant?.key ||
                slugifyKey(variant?.label) ||
                `lens-${index + 1}`;
            out[key] = {
                label: variant?.label || `Lensa ${index + 1}`,
                desc: variant?.desc || "",
                priceAddon: Number(variant?.priceAddon ?? variant?.price ?? 0),
                icon: variant?.icon || "fa-solid fa-eye",
            };
        });
        return Object.keys(out).length ? out : DEFAULT_LENS_VARIANTS;
    }

    return typeof base === "object" && base ? base : DEFAULT_LENS_VARIANTS;
}

function normalizeFrameVariants(input) {
    const base = input || DEFAULT_FRAME_VARIANTS;

    if (Array.isArray(base)) {
        const out = {};
        base.forEach((variant, index) => {
            const key =
                variant?.key ||
                slugifyKey(variant?.label) ||
                `frame-${index + 1}`;
            out[key] = {
                label: variant?.label || `Frame ${index + 1}`,
                desc: variant?.desc || "",
                priceAddon: Number(variant?.priceAddon ?? variant?.price ?? 0),
                icon: variant?.icon || "fa-solid fa-glasses",
            };
        });
        return Object.keys(out).length ? out : DEFAULT_FRAME_VARIANTS;
    }

    return typeof base === "object" && base ? base : DEFAULT_FRAME_VARIANTS;
}

function initProductPage() {
    const mainImg = document.getElementById("main-product-img");
    const colorContainer = document.getElementById("color-swatches");
    const thumbContainer = document.getElementById("thumb-container");
    const frameContainer = document.getElementById("frame-options");
    const lensContainer = document.getElementById("lens-options");
    const priceEl = document.getElementById("product-price");
    const priceAddonEl = document.getElementById("price-addon");
    const promoInput = document.getElementById("promo-code-input");
    const promoApplyBtn = document.getElementById("apply-promo-btn");
    const promoRemoveBtn = document.getElementById("remove-promo-btn");
    const promoFeedback = document.getElementById("promo-feedback");
    const promoDiscountEl = document.getElementById("promo-discount");

    if (!mainImg) return;

    const galleryImages = readJsonFromElement("product-gallery-images") || [];
    if (galleryImages.length === 0 && mainImg.src) {
        galleryImages.push(mainImg.src);
    }

    const colorVariants = normalizeColorVariants(
        readJsonFromElement("product-color-variants"),
        galleryImages,
    );
    const lensVariants = normalizeLensVariants(
        readJsonFromElement("product-lens-variants"),
    );
    const frameVariants = normalizeFrameVariants(
        readJsonFromElement("product-frame-variants"),
    );

    const colorKeys = Object.keys(colorVariants);
    const lensKeys = Object.keys(lensVariants);
    const frameKeys = Object.keys(frameVariants);

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
        selectedColor: colorKeys[0] || "hitam",
        selectedFrame: frameKeys[0] || "full-rim",
        selectedLens: lensKeys[0] || "standard",
        selectedThumb: 0,
        delivery: "pickup",
        appliedPromo: null,
    };

    // Load saved state
    try {
        const saved = JSON.parse(
            localStorage.getItem("indooptik_product_state") || "{}",
        );
        if (saved.selectedColor && colorVariants[saved.selectedColor])
            state.selectedColor = saved.selectedColor;
        if (saved.selectedFrame && frameVariants[saved.selectedFrame])
            state.selectedFrame = saved.selectedFrame;
        if (saved.selectedLens && lensVariants[saved.selectedLens])
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
                    selectedFrame: state.selectedFrame,
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
        const images = colorVariants[state.selectedColor]?.images?.length
            ? colorVariants[state.selectedColor].images
            : galleryImages;
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
        colorContainer.innerHTML = Object.entries(colorVariants)
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
        if (lbl && colorVariants[state.selectedColor]) {
            lbl.textContent = colorVariants[state.selectedColor].label;
        }

        colorContainer.querySelectorAll("[data-color]").forEach((btn) => {
            btn.addEventListener("click", () => {
                state.selectedColor = btn.getAttribute("data-color");
                state.selectedThumb = 0;
                saveState();
                renderColorSwatches();
                renderThumbnails();
                const nextImages = colorVariants[state.selectedColor]?.images
                    ?.length
                    ? colorVariants[state.selectedColor].images
                    : galleryImages;
                updateMainImage(nextImages[0] || mainImg.src);
            });
        });
    }

    function renderLensOptions() {
        if (!lensContainer) return;
        lensContainer.innerHTML = Object.entries(lensVariants)
            .map(
                ([key, v]) => `
            <label class="lens-option ${state.selectedLens === key ? "selected" : ""}" data-lens="${key}">
                <input type="radio" name="lens_type" value="${key}" ${state.selectedLens === key ? "checked" : ""} />
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

    function renderFrameOptions() {
        if (!frameContainer) return;
        frameContainer.innerHTML = Object.entries(frameVariants)
            .map(
                ([key, v]) => `
            <label class="lens-option ${state.selectedFrame === key ? "selected" : ""}" data-frame="${key}">
                <input type="radio" name="frame_type" value="${key}" ${state.selectedFrame === key ? "checked" : ""} />
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

        const lbl = document.getElementById("selected-frame-label");
        if (lbl && frameVariants[state.selectedFrame]) {
            lbl.textContent = frameVariants[state.selectedFrame].label;
        }

        frameContainer.querySelectorAll("[data-frame]").forEach((opt) => {
            opt.addEventListener("click", () => {
                state.selectedFrame = opt.getAttribute("data-frame");
                saveState();
                renderFrameOptions();
                updatePrice();
            });
        });
    }

    function getPricingSummary() {
        const frameAddon = frameVariants[state.selectedFrame]?.priceAddon || 0;
        const lensAddon = lensVariants[state.selectedLens]?.priceAddon || 0;
        const subtotal = BASE_PRICE + frameAddon + lensAddon;
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
        return { frameAddon, lensAddon, subtotal, promoDiscount, total, discountPercent };
    }

    function updatePrice() {
        const pricing = getPricingSummary();

        if (priceEl) priceEl.textContent = formatIDR(pricing.total);
        if (priceAddonEl) {
            if (pricing.frameAddon > 0 || pricing.lensAddon > 0) {
                priceAddonEl.style.display = "";
                priceAddonEl.innerHTML = `Produk: ${formatIDR(BASE_PRICE)} + Tipe Frame: <span>${formatIDR(pricing.frameAddon)}</span> + Lensa: <span>${formatIDR(pricing.lensAddon)}</span>`;
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
                colorVariants[state.selectedColor]?.label ||
                state.selectedColor;
            const frame =
                frameVariants[state.selectedFrame]?.label ||
                state.selectedFrame;
            const lens =
                lensVariants[state.selectedLens]?.label || state.selectedLens;

            const badge = document.getElementById("cart-badge");
            const cartUrl = addToCartBtn.dataset.cartUrl;
            const productId = addToCartBtn.dataset.productId;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

            if (!cartUrl || !productId || !csrf) {
                showToast(
                    "error",
                    "Gagal",
                    "Data produk belum lengkap untuk disimpan ke keranjang.",
                );
                return;
            }

            addToCartBtn.disabled = true;
            fetch(cartUrl, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrf,
                },
                body: JSON.stringify({
                    product_id: productId,
                    color,
                    frame_type: frame,
                    frame_price: pricing.frameAddon,
                    lens_type: lens,
                    lens_price: pricing.lensAddon,
                    quantity: 1,
                    delivery_type: state.delivery,
                }),
            })
                .then(async (response) => {
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        throw new Error(
                            data?.message ||
                                "Produk belum tersimpan ke keranjang server.",
                        );
                    }
                    return data;
                })
                .then((data) => {
                    try {
                        localStorage.setItem(
                            "indooptik_cart_qty",
                            String(data.cart_count || 1),
                        );
                        localStorage.setItem(
                            "indooptik_cart",
                            JSON.stringify({
                                name:
                                    document.querySelector("h1")?.textContent?.trim() ||
                                    "Produk",
                                color,
                                frame,
                                lens,
                                delivery: state.delivery,
                                total: pricing.total,
                                promoCode: state.appliedPromo?.code || null,
                            }),
                        );
                    } catch (e) {
                        /* ignore */
                    }

                    if (badge) {
                        badge.textContent = String(data.cart_count || 1);
                        badge.style.display = "flex";
                    }

                    showToast(
                        "success",
                        "Ditambahkan!",
                        data.message ||
                            `${frame} + ${color} + Lensa ${lens} berhasil masuk keranjang.`,
                    );
                })
                .catch((error) => {
                    showToast("error", "Gagal", error.message);
                })
                .finally(() => {
                    addToCartBtn.disabled = false;
                });
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
                colorVariants[state.selectedColor]?.label ||
                state.selectedColor;
            const frame =
                frameVariants[state.selectedFrame]?.label ||
                state.selectedFrame;
            const lens =
                lensVariants[state.selectedLens]?.label || state.selectedLens;
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

            const msg = `Halo IndoOptik! Saya ingin memesan:\n\n🕶️ ${productName}\n🧩 Frame: ${frame}\n🎨 Warna: ${color}\n🔍 Lensa: ${lens}\n📦 Pengiriman: ${delivery}${promoLine}\n\n💰 Total: ${formatIDR(pricing.total)}\n\nMohon konfirmasinya. Terima kasih!`;
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
    renderFrameOptions();
    renderLensOptions();
    const initialImages = colorVariants[state.selectedColor]?.images?.length
        ? colorVariants[state.selectedColor].images
        : galleryImages;
    updateMainImage(initialImages[0] || mainImg.src);
    updatePrice();
    refreshPromoUI();
}

/* =========================================
   IndoOptik — Appointment / Calendar
   ========================================= */
function initCalendar() {
    const calendar = document.querySelector(".booking-calendar");
    const grid = document.querySelector("[data-cal-grid]");
    const title = document.querySelector("[data-cal-title]");
    const prevBtn = document.querySelector("[data-cal-prev]");
    const nextBtn = document.querySelector("[data-cal-next]");
    const dateInput = document.getElementById("booking-date");
    const errorEl = document.querySelector("[data-booking-error]");
    const timeBtns = document.querySelectorAll("[data-time-slot], [data-time-btn]");
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const monthLabels = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    let visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let selectedDate = null;

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        return `${year}-${month}-${day}`;
    }

    function selectDate(date) {
        selectedDate = formatDate(date);
        if (dateInput) dateInput.value = selectedDate;
        if (errorEl) errorEl.classList.add("hidden");
        renderCalendar();
    }

    function renderCalendar() {
        if (!calendar || !grid || !title) return;

        title.textContent = `${monthLabels[visibleMonth.getMonth()]} ${visibleMonth.getFullYear()}`;
        grid.innerHTML = "";

        const firstDay = new Date(
            visibleMonth.getFullYear(),
            visibleMonth.getMonth(),
            1,
        );
        const daysInMonth = new Date(
            visibleMonth.getFullYear(),
            visibleMonth.getMonth() + 1,
            0,
        ).getDate();

        for (let i = 0; i < firstDay.getDay(); i++) {
            const spacer = document.createElement("div");
            spacer.className = "calendar-day calendar-day-empty";
            spacer.setAttribute("aria-hidden", "true");
            grid.appendChild(spacer);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(
                visibleMonth.getFullYear(),
                visibleMonth.getMonth(),
                day,
            );
            const isPast = date < today;
            const value = formatDate(date);
            const btn = document.createElement("button");
            btn.type = "button";
            btn.dataset.calDay = value;
            btn.textContent = String(day);
            btn.disabled = isPast;
            btn.className =
                "calendar-day h-9 w-9 mx-auto flex items-center justify-center rounded-full text-sm transition-all duration-150";

            if (isPast) {
                btn.className += " text-gray-300 cursor-not-allowed";
                btn.setAttribute("aria-label", `${value} tidak tersedia`);
            } else if (selectedDate === value) {
                btn.className +=
                    " selected bg-indigo-500 text-white font-bold shadow-md shadow-indigo-200";
                btn.setAttribute("aria-pressed", "true");
            } else {
                btn.className +=
                    " cursor-pointer hover:bg-indigo-50 hover:text-indigo-600 text-neutral-700";
                btn.setAttribute("aria-pressed", "false");
            }

            btn.addEventListener("click", () => {
                if (isPast) {
                    if (errorEl) {
                        errorEl.textContent =
                            "Tanggal booking tidak boleh sebelum hari ini.";
                        errorEl.classList.remove("hidden");
                    }
                    return;
                }
                selectDate(date);
            });

            grid.appendChild(btn);
        }

        if (prevBtn) {
            const prevMonth = new Date(
                visibleMonth.getFullYear(),
                visibleMonth.getMonth() - 1,
                1,
            );
            prevBtn.disabled =
                prevMonth < new Date(today.getFullYear(), today.getMonth(), 1);
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            visibleMonth = new Date(
                visibleMonth.getFullYear(),
                visibleMonth.getMonth() - 1,
                1,
            );
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            visibleMonth = new Date(
                visibleMonth.getFullYear(),
                visibleMonth.getMonth() + 1,
                1,
            );
            renderCalendar();
        });
    }

    timeBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            timeBtns.forEach((b) =>
                b.classList.remove(
                    "border-indigo-500",
                    "bg-indigo-50",
                    "text-indigo-600",
                    "font-bold",
                    "selected",
                ),
            );
            btn.classList.add(
                "border-indigo-500",
                "bg-indigo-50",
                "text-indigo-600",
                "font-bold",
                "selected",
            );
            const timeInput = document.getElementById("booking-time");
            if (timeInput) timeInput.value = btn.textContent.trim();
        });
    });

    selectDate(today);
}

/* =========================================
   IndoOptik — WhatsApp Booking (services page)
   ========================================= */
function initWhatsAppBooking() {
    const form = document.getElementById("booking-form");
    const btn =
        document.getElementById("wa-booking-btn") ||
        document.getElementById("booking-wa-btn");
    if (!form || !btn) return;

    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const name =
            form.querySelector('[name="customer_name"]')?.value?.trim() ||
            form.querySelector('[name="name"]')?.value?.trim() ||
            "";
        const phone =
            form.querySelector('[name="customer_phone"]')?.value?.trim() ||
            form.querySelector('[name="phone"]')?.value?.trim() ||
            "";
        const checkedService = form.querySelector(
            '[name="service"]:checked, [name="service_type"]:checked',
        );
        const service =
            checkedService
                ?.closest("label")
                ?.querySelector(".block.font-bold")
                ?.textContent?.trim() ||
            checkedService?.value ||
            "";
        const selectedDay =
            form.querySelector('[name="booking_date"]')?.value?.trim() ||
            form
                .querySelector("[data-cal-day].selected")
                ?.getAttribute("data-cal-day") ||
            "";
        const selectedTime =
            form
                .querySelector("[data-time-slot].selected, [data-time-btn].selected")
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

        if (!selectedDay) {
            showToast("error", "Tanggal Belum Dipilih", "Pilih tanggal booking.");
            return;
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        const formUrl = form.getAttribute("action");

        fetch(formUrl, {
            method: "POST",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": csrf || "",
            },
            body: JSON.stringify({
                service: checkedService?.value || "exam",
                booking_date: selectedDay,
                booking_time: selectedTime,
                name,
                phone,
            }),
        })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const message =
                        data?.errors?.booking_date?.[0] ||
                        data?.message ||
                        "Jadwal tidak valid.";
                    throw new Error(message);
                }
                return data;
            })
            .then((data) => {
                window.open(data.wa_url, "_blank");
            })
            .catch((error) => {
                showToast("error", "Booking Gagal", error.message);
            });
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
    initUserDropdown();
    initAdminSidebar();
    initConfirmForms();
    initCartBadge();
    initCatalogTabs();
    initProductPage();
    initCalendar();
    initWhatsAppBooking();
});
