# meinfenster24 – Review Cards

Responsive customer review cards. Data is read from the supplied SQLite database (`data/rezensionen.sqlite`)
with SQL in the PHP backend and rendered server-side with Tailwind CSS.

**Stack:** PHP 8.3 · PDO SQLite · Tailwind CSS 3 · PHPUnit 11 · Docker (Apache) · PHPStan

**Structure (MVC):** `public/index.php` bootstraps → `ReviewController` → `ReviewService` (business logic) →
`SqliteReviewRepository` (SQL) → models (`Review`, `ReviewCard`, `ReviewPage`) → PHP templates in `templates/` (view).
The lightbox uses [baguetteBox.js](https://github.com/feimosi/baguetteBox.js) from the jsDelivr CDN, so it needs internet
access; without it the image link opens the full-size photo instead.

## Run

```bash
docker compose up --build                 # http://localhost:8080
WEB_PORT=8090 docker compose up --build   # if 8080 is taken
```

Without Docker (PHP ≥ 8.3 with `pdo_sqlite`, `mbstring`):

```bash
composer install && php -S localhost:8000 -t public
```

## Tests

```bash
docker compose --profile test run --rm --build test   # or: make test
composer test                                         # local
composer stan                                         # static analysis (level 6)
```

The compiled `public/assets/app.css` is committed; rebuild with `npm install && npm run build`.

## Local development

`docker-compose.override.yml` is picked up automatically and mounts `src/`, `templates/` and `public/`
into the container, so code changes (including `die()` / `var_dump()` debugging) appear on browser refresh
without rebuilding. To run the plain production setup instead: `docker compose -f docker-compose.yml up --build`.
Rebuild the CSS after changing Tailwind classes: `npm run build`.
