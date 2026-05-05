<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-4xl font-black text-slate-950">My listings</h1>
            <p class="mt-2 text-slate-600">Create, edit, delete, and track moderation status for your properties.</p>
        </div>
        <a href="<?= e(url('/post')) ?>" class="rounded-full bg-estate-700 px-5 py-3 text-sm font-black text-white hover:bg-estate-900">Post new listing</a>
    </div>
    <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Listing</th>
                        <th class="px-5 py-4">Location</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($listings as $listing): ?>
                        <tr>
                            <td class="px-5 py-4 font-bold text-slate-900"><?= e($listing['title']) ?></td>
                            <td class="px-5 py-4 text-slate-600"><?= e($listing['ward_name']) ?>, <?= e($listing['province_name']) ?></td>
                            <td class="px-5 py-4 font-bold text-clay"><?= e(price_vnd($listing['price'])) ?></td>
                            <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black"><?= e($listing['status']) ?></span></td>
                            <td class="px-5 py-4">
                                <div class="flex gap-2">
                                    <a href="<?= e(url('/listing/' . $listing['id'] . '/edit')) ?>" class="rounded-full bg-slate-100 px-3 py-2 font-bold hover:bg-slate-200">Edit</a>
                                    <form action="<?= e(url('/listing/' . $listing['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this listing?');">
                                        <?= csrf_field() ?>
                                        <button class="rounded-full bg-red-50 px-3 py-2 font-bold text-red-700 hover:bg-red-100" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($listings === []): ?>
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">You have not posted any listings yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
