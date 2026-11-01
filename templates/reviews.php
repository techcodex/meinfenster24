<?php
/** @var \App\Model\ReviewPage $page */
$e = [\App\View\Renderer::class, 'e'];
$avg = number_format($page->averageRating, 1, ',', '');
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kundenbewertungen – meinfenster24</title>
  <meta name="description" content="Das sagen unsere Kunden über meinfenster24.">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Crect x='9' y='6' width='30' height='36' rx='2' fill='none' stroke='%231d4ed8' stroke-width='4'/%3E%3Cpath d='M24 6v36M9 24h30' stroke='%231d4ed8' stroke-width='4'/%3E%3C/svg%3E">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css" integrity="sha384-GPAzSuZc0kFvdIev6wm9zg8gnafE8tLso7rsAYQfc9hAdWCpOcpcNI5W9lWkYcsd" crossorigin="anonymous">
  <link rel="stylesheet" href="/assets/app.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
  <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
    <header class="mx-auto mb-10 max-w-2xl text-center lg:mb-14">
      <p class="text-sm font-semibold uppercase tracking-widest text-blue-700">Kundenstimmen</p>
      <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Das sagen unsere Kunden</h1>
      <?php if ($page->ratedCount > 0): ?>
        <p class="mt-4 flex items-center justify-center gap-2 text-slate-600">
          <svg class="h-6 w-6 fill-amber-400" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 1.5l2.6 5.5 6 .8-4.4 4.2 1.1 6-5.3-2.9-5.3 2.9 1.1-6L1.4 7.8l6-.8L10 1.5z"/></svg>
          <span><strong class="text-slate-900"><?= $e($avg) ?></strong> von 5 · <?= $page->ratedCount ?> Bewertungen</span>
        </p>
      <?php endif; ?>
    </header>

    <?php if ($page->cards === []): ?>
      <p class="text-center text-slate-500">Noch keine Bewertungen vorhanden.</p>
    <?php else: ?>
      <section aria-label="Kundenbewertungen" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($page->cards as $card): include __DIR__ . '/partials/review-card.php'; endforeach; ?>
      </section>
    <?php endif; ?>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js" integrity="sha384-/+Fc1LD6ksHYZ+2MChiSfjEXBcl4q2axUWhs6/CdnfqY5aLmrPwtysVzyeP0s60b" crossorigin="anonymous" defer></script>
  <script src="/assets/app.js" defer></script>
</body>
</html>
