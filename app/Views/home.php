<section class="relative overflow-hidden bg-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,#dceee6,transparent_30%),radial-gradient(circle_at_bottom_right,#fde68a,transparent_25%)]"></div>
    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-28">
        <div>
            <span class="rounded-full bg-estate-50 px-4 py-2 text-sm font-black text-estate-700">LHT Estate · Real estate classified marketplace</span>
            <h1 class="mt-8 max-w-3xl text-5xl font-black tracking-tight text-slate-950 sm:text-6xl">
                Find trusted homes, land, rentals, and investment spaces.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-600">
                Search verified public listings, post properties for admin review, save favorites, and manage your own classifieds in one simple PHP MVP.
            </p>
            <form action="<?= e(url('/listings')) ?>" method="get" class="mt-8 grid gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-xl sm:grid-cols-[1fr_180px_auto]">
                <input name="q" class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-estate-600" placeholder="Search by title, address, keyword">
                <select name="province_id" class="rounded-2xl border border-slate-200 px-4 py-3 outline-none focus:border-estate-600">
                    <option value="">All provinces</option>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?= e($province['id']) ?>"><?= e($province['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="rounded-2xl bg-estate-700 px-6 py-3 font-black text-white hover:bg-estate-900" type="submit">Search</button>
            </form>
            <div class="mt-8 grid gap-4 text-sm font-semibold text-slate-600 sm:grid-cols-3">
                <div class="rounded-2xl bg-slate-50 p-4">Pending review protects quality</div>
                <div class="rounded-2xl bg-slate-50 p-4">Saved listings for signed-in users</div>
                <div class="rounded-2xl bg-slate-50 p-4">Admin approval workflow</div>
            </div>
        </div>
        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-4 shadow-2xl">
            <div class="rounded-[1.5rem] bg-estate-900 p-6 text-white">
                <p class="text-sm font-bold text-estate-100">Featured marketplace categories</p>
                <div class="mt-6 grid gap-4">
                    <?php foreach (array_slice($categories, 0, 5) as $category): ?>
                        <a href="<?= e(url('/listings?category_id=' . $category['id'])) ?>" class="flex items-center justify-between rounded-2xl bg-white/10 p-4 hover:bg-white/20">
                            <span class="font-bold"><?= e($category['name']) ?></span>
                            <span aria-hidden="true">→</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-black uppercase tracking-widest text-estate-700">Fresh approvals</p>
            <h2 class="mt-2 text-3xl font-black text-slate-950">Latest approved listings</h2>
        </div>
        <a href="<?= e(url('/listings')) ?>" class="rounded-full border border-slate-300 px-5 py-3 text-sm font-black text-slate-700 hover:bg-white">View all listings</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($listings as $listing): ?>
            <?php require BASE_PATH . '/app/Views/partials/listing-card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>
