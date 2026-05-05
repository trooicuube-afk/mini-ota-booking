<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[1fr_380px]">
        <div>
            <div class="grid gap-4 <?= count($images) > 1 ? 'md:grid-cols-2' : '' ?>">
                <?php if ($images === []): ?>
                    <div class="flex h-96 items-center justify-center rounded-[2rem] bg-gradient-to-br from-estate-100 to-amber-100 font-black text-estate-700">LHT Estate</div>
                <?php endif; ?>
                <?php foreach ($images as $image): ?>
                    <img src="<?= e(asset(ltrim((string) $image['path'], '/'))) ?>" alt="<?= e($listing['title']) ?>" class="h-96 w-full rounded-[2rem] object-cover">
                <?php endforeach; ?>
            </div>

            <div class="mt-8 rounded-[2rem] border border-slate-200 bg-white p-8 shadow-sm">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div>
                        <span class="rounded-full bg-estate-50 px-3 py-1 text-xs font-black text-estate-700"><?= e($listing['category_name']) ?></span>
                        <h1 class="mt-4 text-4xl font-black text-slate-950"><?= e($listing['title']) ?></h1>
                        <p class="mt-3 text-slate-600"><?= e($listing['address']) ?>, <?= e($listing['ward_name']) ?>, <?= e($listing['province_name']) ?></p>
                    </div>
                    <p class="text-2xl font-black text-clay"><?= e(price_vnd($listing['price'])) ?></p>
                </div>
                <dl class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-xs font-black uppercase text-slate-500">Area</dt>
                        <dd class="mt-1 font-black"><?= e($listing['area']) ?> m²</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-xs font-black uppercase text-slate-500">Province</dt>
                        <dd class="mt-1 font-black"><?= e($listing['province_name']) ?></dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <dt class="text-xs font-black uppercase text-slate-500">Posted by</dt>
                        <dd class="mt-1 font-black"><?= e($listing['owner_name']) ?></dd>
                    </div>
                </dl>
                <div class="prose prose-slate mt-8 max-w-none">
                    <p class="whitespace-pre-line text-slate-700"><?= e($listing['description']) ?></p>
                </div>
            </div>
        </div>

        <aside class="h-fit rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
            <p class="text-sm font-black uppercase tracking-widest text-estate-700">Contact owner</p>
            <p class="mt-4 text-2xl font-black text-slate-950"><?= e($listing['contact_name']) ?></p>
            <p class="mt-2 text-lg font-bold text-clay"><?= e($listing['contact_phone']) ?></p>
            <div class="mt-6 grid gap-3">
                <?php if (current_user() !== null): ?>
                    <?php if ($saved): ?>
                        <form action="<?= e(url('/listing/' . $listing['id'] . '/unsave')) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="w-full rounded-2xl border border-slate-300 px-5 py-3 font-black text-slate-700 hover:bg-slate-50" type="submit">Unsave listing</button>
                        </form>
                    <?php else: ?>
                        <form action="<?= e(url('/listing/' . $listing['id'] . '/save')) ?>" method="post">
                            <?= csrf_field() ?>
                            <button class="w-full rounded-2xl bg-estate-700 px-5 py-3 font-black text-white hover:bg-estate-900" type="submit">Save listing</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= e(url('/login')) ?>" class="rounded-2xl bg-estate-700 px-5 py-3 text-center font-black text-white hover:bg-estate-900">Login to save</a>
                <?php endif; ?>
                <a href="<?= e(url('/listings')) ?>" class="rounded-2xl bg-slate-100 px-5 py-3 text-center font-black text-slate-700 hover:bg-slate-200">Back to search</a>
            </div>
        </aside>
    </div>
</section>
