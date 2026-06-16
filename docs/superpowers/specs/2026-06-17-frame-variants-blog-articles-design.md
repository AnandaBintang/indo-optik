# Frame Variants and Blog Articles Design

## Goal

Add frame type selection as product-level variants and add admin-managed blog articles with public blog pages.

## Product Frame Variants

Products will store a nullable `frame_variants` JSON column, matching the existing `color_variants` and `lens_variants` pattern. Each frame variant contains a stable key, label, description, price add-on, icon class, and optional images. Admin users can create and update frame variants from product create/edit forms.

On the product detail page, frame type appears before color and lens as "1. Pilih Tipe Frame". The first configured frame option is selected by default. If no frame variants are configured, the page shows a small default set so product ordering still works.

When a customer adds a product to the cart, the selected frame type and frame price are stored on the cart item. Totals, cart display, item uniqueness, and WhatsApp checkout include frame price in addition to base product price and lens price.

## Blog Articles

Blog articles use a new `blog_articles` table and `BlogArticle` model. Fields are title, slug, excerpt, content, cover image, status, published date, meta title, and meta description. Status values are `draft` and `published`.

Admin users can list, create, edit, and delete blog articles under `/admin/blog-articles`. Public users can view `/blog` and `/blog/{slug}`. Public pages show only published articles whose `published_at` is not in the future.

## Data Flow

Product variant admin submissions are validated by product request classes, normalized by the admin product controller, and persisted as JSON. Blog article submissions are validated by dedicated request classes and handled by an admin blog article controller.

Public product detail receives decoded product variants from the model casts. Public blog routes query only published articles and pass them to Blade views.

## Error Handling

Invalid product variant rows with empty labels are ignored. Negative frame or lens prices are rejected by validation. Blog slugs are generated uniquely from titles unless explicitly provided. Public article detail returns 404 for drafts, deleted records, and future-dated articles.

## Testing

Feature tests cover storing and updating frame variants, adding configured frame choices to the cart, cart subtotal and WhatsApp output including frame price, admin blog CRUD, and public blog visibility.
