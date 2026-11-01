<?php
/** @var \App\Service\StarRating $rating */
/** @var callable(?string): string $e */
$star = '<path d="M10 1.5l2.6 5.5 6 .8-4.4 4.2 1.1 6-5.3-2.9-5.3 2.9 1.1-6L1.4 7.8l6-.8L10 1.5z"/>';
?>
<div class="flex items-center gap-0.5" role="img" aria-label="<?= $e($rating->label()) ?>">
  <?php for ($i = 0; $i < $rating->full(); $i++): ?>
    <svg class="h-5 w-5 fill-amber-400" viewBox="0 0 20 20" aria-hidden="true"><?= $star ?></svg>
  <?php endfor; ?>
  <?php for ($i = 0; $i < $rating->empty(); $i++): ?>
    <svg class="h-5 w-5 fill-slate-200" viewBox="0 0 20 20" aria-hidden="true"><?= $star ?></svg>
  <?php endfor; ?>
</div>
