<?php
/** @var \App\Model\ReviewCard $card */
/** @var callable(?string): string $e escape helper defined in reviews.php */
$image = $card->primaryImage();
$count = count($card->images);
$imagesJson = json_encode(
    array_map(static fn (\App\Model\ReviewImage $i): array => ['url' => $i->url, 'alt' => $i->alt], $card->images),
    JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES
);
?>
<article class="review-card flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-lg" data-review-id="<?= $card->id ?>">
  <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
    <?php if ($image !== null): ?>
      <button type="button" class="group block h-full w-full cursor-zoom-in focus:outline-none focus-visible:ring-4 focus-visible:ring-inset focus-visible:ring-blue-600"
              aria-haspopup="dialog" aria-label="<?= $count > 1 ? "$count Fotos ansehen" : 'Foto vergrößern' ?>"
              data-lightbox-open data-images="<?= $e($imagesJson) ?>">
        <img src="<?= $e($image->url) ?>" alt="<?= $e($image->alt) ?>" loading="lazy" decoding="async"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-105" data-review-image>
      </button>
      <div class="absolute inset-0 hidden" data-review-placeholder><?php include __DIR__ . '/placeholder.php'; ?></div>
      <?php if ($count > 1): ?>
        <span class="pointer-events-none absolute bottom-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-slate-900/70 px-2.5 py-1 text-xs font-semibold text-white backdrop-blur">
          <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M4 5a2 2 0 012-2h8a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm-2 9V7.5A2 2 0 000 9.5V14a3 3 0 003 3h6.5a2 2 0 002-2H5a3 3 0 01-3-1z"/></svg>
          <?= $count ?> Fotos
        </span>
      <?php endif; ?>
    <?php else: ?>
      <?php include __DIR__ . '/placeholder.php'; ?>
    <?php endif; ?>
  </div>

  <div class="flex flex-1 flex-col gap-4 p-6">
    <?php if ($card->rating->isRated()): ?>
      <div class="flex items-center justify-between gap-3">
        <?php $rating = $card->rating; include __DIR__ . '/stars.php'; ?>
        <span class="text-sm font-semibold text-slate-700" aria-hidden="true"><?= $card->rating->value ?>,0</span>
      </div>
    <?php endif; ?>

    <?php if ($card->text !== null): ?>
      <div class="flex-1">
        <blockquote class="line-clamp-5 whitespace-pre-line break-words text-base leading-relaxed text-slate-700" data-review-text><?= $e($card->text) ?></blockquote>
        <button type="button" class="mt-2 hidden rounded text-sm font-semibold text-blue-700 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600" aria-expanded="false" data-review-toggle>Mehr lesen</button>
      </div>
    <?php else: ?>
      <p class="flex-1 text-base italic text-slate-400">Der Kunde hat keinen Text hinterlassen.</p>
    <?php endif; ?>

    <footer class="flex items-center gap-3 border-t border-slate-100 pt-4">
      <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-sm font-bold text-blue-800" aria-hidden="true"><?= $e(mb_substr($card->displayName, 0, 1)) ?></span>
      <div class="min-w-0">
        <p class="truncate font-semibold text-slate-900"><?= $e($card->displayName) ?></p>
        <?php if ($card->dateLabel !== null): ?><p class="text-sm text-slate-500"><?= $e($card->dateLabel) ?></p><?php endif; ?>
      </div>
    </footer>
  </div>
</article>
