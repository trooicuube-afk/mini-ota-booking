<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-black text-slate-950">Saved listings</h1>
    <p class="mt-2 text-slate-600">Your personal shortlist of approved LHT Estate properties.</p>
    <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($listings as $listing): ?>
            <?php require BASE_PATH . '/app/Views/partials/listing-card.php'; ?>
        <?php endforeach; ?>
    </div>
    <?php if ($listings === []): ?>
        <div class="mt-8 rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <p class="text-lg font-black text-slate-900">No saved listings yet.</p>
            <a href="<?= e(url('/listings')) ?>" class="mt-4 inline-flex rounded-full bg-estate-700 px-5 py-3 font-black text-white hover:bg-estate-900">Browse listings</a>
        </div>
    <?php endif; ?>
</section>
