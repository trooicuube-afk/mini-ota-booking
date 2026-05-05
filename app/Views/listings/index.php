<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] bg-estate-900 px-6 py-10 text-white sm:px-10">
        <p class="text-sm font-black uppercase tracking-widest text-estate-100">Search marketplace</p>
        <h1 class="mt-3 text-4xl font-black">Browse approved LHT Estate listings</h1>
        <p class="mt-3 max-w-2xl text-estate-100">Filter by keyword, category, location, and price. Only admin-approved listings appear publicly.</p>
    </div>

    <form action="<?= e(url('/listings')) ?>" method="get" class="mt-8 grid gap-4 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-3 lg:grid-cols-6">
        <input name="q" value="<?= e($filters['q']) ?>" placeholder="Keyword" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-estate-600 focus:outline-none lg:col-span-2">
        <select name="category_id" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-estate-600 focus:outline-none">
            <option value="">Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= e($category['id']) ?>" <?= selected($filters['category_id'], $category['id']) ?>><?= e($category['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="province_id" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-estate-600 focus:outline-none">
            <option value="">Province</option>
            <?php foreach ($provinces as $province): ?>
                <option value="<?= e($province['id']) ?>" <?= selected($filters['province_id'], $province['id']) ?>><?= e($province['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input name="min_price" type="number" min="0" value="<?= e($filters['min_price'] > 0 ? $filters['min_price'] : '') ?>" placeholder="Min price" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-estate-600 focus:outline-none">
        <input name="max_price" type="number" min="0" value="<?= e($filters['max_price'] > 0 ? $filters['max_price'] : '') ?>" placeholder="Max price" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-estate-600 focus:outline-none">
        <button class="rounded-2xl bg-estate-700 px-5 py-3 text-sm font-black text-white hover:bg-estate-900 md:col-span-3 lg:col-span-6" type="submit">Apply filters</button>
    </form>

    <div class="mt-8 flex items-center justify-between">
        <p class="text-sm font-semibold text-slate-600"><?= e($result['total']) ?> listing(s) found</p>
        <a href="<?= e(url('/post')) ?>" class="rounded-full bg-white px-5 py-3 text-sm font-black text-estate-700 shadow-sm hover:bg-estate-50">Post a property</a>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($result['items'] as $listing): ?>
            <?php require BASE_PATH . '/app/Views/partials/listing-card.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php if ($result['items'] === []): ?>
        <div class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <p class="text-lg font-black text-slate-900">No approved listings match your search.</p>
            <p class="mt-2 text-slate-600">Try a wider keyword or remove price filters.</p>
        </div>
    <?php endif; ?>
</section>
