<?php
/** @var \App\Model\ReviewCard $card */
/** @var callable(?string): string $e escape helper defined in reviews.php */
$image = $card->primaryImage();
$extra = count($card->images) - 1;
?>
<article class="review-card flex h-full flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:shadow-lg" data-review-id="<?= $card->id ?>">
  <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-100">
    <?php if ($image !== null): ?>
      <img src="<?= $e($image->url) ?>" alt="<?= $e($image->alt) ?>" loading="lazy" decoding="async"
           class="h-full w-full object-cover" data-review-image>
      <div class="absolute inset-0 hidden" data-review-placeholder><?php include __DIR__ . '/placeholder.php'; ?></div>
      <?php if ($extra > 0): ?>
        <span class="absolute bottom-3 right-3 rounded-full bg-slate-900/70 px-2.5 py-1 text-xs font-semibold text-white backdrop-blur">+<?= $extra ?> <?= $extra === 1 ? 'Foto' : 'Fotos' ?></span>
      <?php endif; ?>
    <?php else: ?>
      <?php include __DIR__ . '/placeholder.php'; ?>
    <?php endif; ?>
  </div>

  <div class="flex flex-1 flex-col gap-4 p-6">
    <div class="flex items-center justify-between gap-3">
      <?php $rating = $card->rating; include __DIR__ . '/stars.php'; ?>
      <span class="text-sm font-semibold text-slate-700"><?= $card->rating->value ?>,0</span>
    </div>

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
