# meinfenster24 – Review Cards

Responsive customer review cards. Data is read from the supplied SQLite database (`data/rezensionen.sqlite`)
with SQL in the PHP backend and rendered server-side with Tailwind CSS.

**Stack:** PHP 8.3 · PDO SQLite · Tailwind CSS 3 · PHPUnit 11 · Docker (Apache) · PHPStan

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

## Architecture (service pattern)

```
public/index.php              front controller – wiring only
src/Repository/               ReviewRepositoryInterface + SqliteReviewRepository (ALL SQL lives here)
src/Service/ReviewService     orchestrates: repository -> presentation-ready ReviewCard
src/Service/NameFormatter     "Max Mustermann" -> "Max M."
src/Service/StarRating        clamps 0..5, full/empty star counts
src/Service/DateFormatter     "2026-02" / "2026-04-20" -> "Februar 2026"
src/Model/                    readonly DTOs (Review, ReviewImage, ReviewCard, ReviewPage)
templates/                    plain PHP views, every output escaped
```

## Design decisions & data edge cases

| Case found in the DB | Handling |
|---|---|
| Review 9 has `active = 0` | Excluded in SQL (`WHERE active = 1`) |
| Review 10: no text, no image URL | Placeholder image + "no text" hint |
| 1–3 images per review (`rezensionen_grafiken`) | First by `position` shown, "+N Fotos" badge; loaded with one `IN (...)` query (no N+1) |
| Names with middle names / hyphens / umlauts | Last word's initial, multibyte-safe: "Rene Bernd Holgerson" → "Rene H." |
| Text length 38–559 chars | Equal-height cards, 5-line clamp, "Mehr lesen" only when text really overflows |
| Mixed date formats (`2026-02`, `2026-04-20`) | Normalised to month + year |
| Remote image fails to load | JS falls back to the placeholder |

Extras: responsive grid (1 / 2 / 3 columns), average rating + schema.org `AggregateRating` JSON-LD,
ARIA labels on stars, keyboard-accessible toggle, XSS-safe output, prepared statements, GitHub Actions CI.

The compiled `public/assets/app.css` is committed; rebuild with `npm install && npm run build`.

## Local development

`docker-compose.override.yml` is picked up automatically and mounts `src/`, `templates/` and `public/`
into the container, so code changes (including `die()` / `var_dump()` debugging) appear on browser refresh
without rebuilding. To run the plain production setup instead: `docker compose -f docker-compose.yml up --build`.
Rebuild the CSS after changing Tailwind classes: `npm run build`.
