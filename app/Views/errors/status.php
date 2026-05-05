<section class="mx-auto max-w-3xl px-4 py-24 text-center sm:px-6 lg:px-8">
    <p class="text-sm font-black uppercase tracking-widest text-estate-700"><?= e($status) ?></p>
    <h1 class="mt-4 text-4xl font-black text-slate-950"><?= e($message) ?></h1>
    <p class="mt-4 text-slate-600">Return to the marketplace or browse approved listings.</p>
    <a href="<?= e(url('/listings')) ?>" class="mt-8 inline-flex rounded-full bg-estate-700 px-6 py-3 font-black text-white hover:bg-estate-900">Browse listings</a>
</section>
