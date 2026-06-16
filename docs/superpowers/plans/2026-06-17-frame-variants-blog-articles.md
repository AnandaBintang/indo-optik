# Frame Variants and Blog Articles Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build selectable product frame variants and full admin-managed blog articles.

**Architecture:** Extend the existing product variant JSON pattern with `frame_variants`, then carry selected frame data through product detail JavaScript, cart service pricing, and checkout output. Add a conventional Laravel blog resource with model, migration, admin controller, public controller, request validation, routes, and Blade views.

**Tech Stack:** Laravel, Pest, Blade, Vite, Alpine CSP, Font Awesome, PNPM for frontend commands.

---

## File Structure

- Modify `database/migrations/*add_product_variants_columns.php` or add a new migration for `products.frame_variants`.
- Modify `app/Models/Product.php` to cast and allow `frame_variants`.
- Modify `app/Http/Requests/StoreProductRequest.php` and `app/Http/Requests/UpdateProductRequest.php` for frame variant validation.
- Modify `app/Http/Controllers/Admin/ProductController.php` to normalize frame variants.
- Modify `resources/views/admin/products/create.blade.php` and `resources/views/admin/products/edit.blade.php` to add frame variant rows.
- Modify `resources/views/pages/catalog/show.blade.php` and `resources/js/app.js` to render and submit frame choices.
- Modify `app/Http/Controllers/CartController.php`, `app/Services/CartService.php`, and `resources/views/pages/cart/index.blade.php` to store and price frame choices.
- Create `database/migrations/*create_blog_articles_table.php`, `app/Models/BlogArticle.php`, request classes, controllers, views, and tests for blog articles.

## Tasks

### Task 1: Frame Variant Persistence and Cart Pricing

- [ ] Write failing Pest tests in `tests/Feature/AdminProductVariantsTest.php` and `tests/Feature/CartFrameVariantTest.php` for storing frame variants and including frame price in cart subtotal.
- [ ] Run `composer test -- --filter=FrameVariant` and confirm failures reference missing `frame_variants` behavior.
- [ ] Add migration/model/request/controller support for `frame_variants`.
- [ ] Update cart controller/service to accept `frame_type` and `frame_price`.
- [ ] Run `composer test -- --filter=FrameVariant` and confirm pass.

### Task 2: Product and Cart UI

- [ ] Add frame variant sections to admin create/edit product forms.
- [ ] Add "Pilih Tipe Frame" to product detail and include `product-frame-variants` JSON.
- [ ] Update `resources/js/app.js` to render frame options, update price add-ons, and submit selected frame data.
- [ ] Update cart Blade to show frame type and price composition.
- [ ] Run `composer test -- --filter=CartFrameVariantTest`.
- [ ] Run `pnpm run build`.

### Task 3: Blog Article Backend

- [ ] Write failing tests in `tests/Feature/BlogArticleTest.php` for admin CRUD and public visibility.
- [ ] Add blog migration, model, factory, requests, admin/public controllers, and routes.
- [ ] Run `composer test -- --filter=BlogArticleTest` and confirm pass.

### Task 4: Blog Article Views and Navigation

- [ ] Add admin blog article index/create/edit Blade views.
- [ ] Add public blog index/show Blade views.
- [ ] Add blog links to public/admin navigation where consistent with existing layout.
- [ ] Run full `composer test`.
- [ ] Run `pnpm run build`.

## Self-Review

The plan covers frame persistence, frontend selection, cart totals, blog admin CRUD, public blog pages, routes, and tests. There are no placeholder tasks or mismatched names.
