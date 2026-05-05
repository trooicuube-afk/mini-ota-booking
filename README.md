# LHT Estate

LHT Estate is a native PHP 8.2+ real estate classified marketplace MVP. It includes public property search, listing detail pages, user authentication, listing CRUD, saved listings, image uploads, admin moderation, and user management.

This implementation is an original brand and UI. Reference sites were used only for functional and business-flow inspiration; no code, logos, trademarks, layouts, or images were copied.

## Tech stack

- PHP 8.2+
- MySQL
- PDO prepared statements
- Composer PSR-4 autoload
- Native PHP MVC structure
- TailwindCSS CDN for styling

## Main routes

- `/` — homepage
- `/listings` — public search/filter page
- `/listing/{slug}` — approved listing detail
- `/post` — create listing, pending review
- `/my-listings` — edit/delete own listings
- `/saved` — saved approved listings
- `/login`, `/register`, `/logout`
- `/admin` — moderation dashboard
- `/admin/listings` — approve/reject/delete listings
- `/admin/users` — manage users

## Database tables

- `users`
- `listings`
- `listing_images`
- `saved_listings`
- `categories`
- `provinces`
- `wards`

## Local setup

1. Copy the environment file:

```bash
cp .env.example .env
```

2. Update `.env` with your MySQL credentials:

```dotenv
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lht_estate
DB_USERNAME=root
DB_PASSWORD=
```

3. Install dependencies:

```bash
composer install
```

4. Import schema and seed demo data:

```bash
composer seed
```

The seed command creates the configured database if it does not exist, imports `database/schema.sql`, and inserts demo categories, provinces, wards, users, and listings.

5. Start the local server:

```bash
composer serve
```

Open http://127.0.0.1:8080.

## Demo accounts

All demo accounts use password `password123`.

- Admin: `admin@lhtestate.test`
- Seller: `seller@lhtestate.test`
- Buyer: `buyer@lhtestate.test`

## Validation and security notes

- Authentication uses `password_hash()` and `password_verify()`.
- SQL access uses PDO prepared statements.
- Views escape dynamic output with `htmlspecialchars()` through the `e()` helper.
- Forms include CSRF tokens.
- Image uploads are validated by extension and MIME type and stored in `public/assets/uploads`.
- Public listing pages only show approved listings.
- Users can only edit/delete their own listings.
- Admin-only routes enforce role-based access control.

## Useful commands

```bash
composer lint
composer seed
composer serve
```
