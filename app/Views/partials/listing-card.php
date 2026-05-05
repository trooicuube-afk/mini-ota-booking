<article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    <a href="<?= e(url('/listing/' . $listing['slug'])) ?>" class="block">
        <?php if (!empty($listing['cover_image'])): ?>
            <img src="<?= e(asset(ltrim((string) $listing['cover_image'], '/'))) ?>" alt="<?= e($listing['title']) ?>" class="h-52 w-full object-cover">
        <?php else: ?>
            <div class="flex h-52 w-full items-center justify-center bg-gradient-to-br from-estate-100 to-amber-100 text-sm font-bold text-estate-700">LHT Estate</div>
        <?php endif; ?>
    </a>
    <div class="p-5">
        <div class="mb-3 flex items-center justify-between gap-3">
            <span class="rounded-full bg-estate-50 px-3 py-1 text-xs font-bold text-estate-700"><?= e($listing['category_name'] ?? 'Property') ?></span>
            <span class="text-sm font-black text-clay"><?= e(price_vnd($listing['price'])) ?></span>
        </div>
        <h3 class="line-clamp-2 text-lg font-black text-slate-950">
            <a href="<?= e(url('/listing/' . $listing['slug'])) ?>" class="hover:text-estate-700"><?= e($listing['title']) ?></a>
        </h3>
        <p class="mt-2 text-sm text-slate-600"><?= e($listing['ward_name'] ?? '') ?>, <?= e($listing['province_name'] ?? '') ?></p>
        <p class="mt-2 text-sm text-slate-500"><?= e($listing['area']) ?> m² · <?= e($listing['address']) ?></p>
    </div>
</article>
