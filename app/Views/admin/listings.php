<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <h1 class="text-4xl font-black text-slate-950">Manage listings</h1>
            <p class="mt-2 text-slate-600">Approve, reject, or delete submitted properties.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php foreach ([null => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label): ?>
                <a href="<?= e(url('/admin/listings' . ($value ? '?status=' . $value : ''))) ?>" class="rounded-full px-4 py-2 text-sm font-black <?= $status === $value || ($status === null && $value === null) ? 'bg-estate-700 text-white' : 'bg-white text-slate-700 shadow-sm' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Listing</th>
                        <th class="px-5 py-4">Owner</th>
                        <th class="px-5 py-4">Location</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($listings as $listing): ?>
                        <tr>
                            <td class="px-5 py-4">
                                <p class="font-black text-slate-950"><?= e($listing['title']) ?></p>
                                <p class="text-slate-500"><?= e(price_vnd($listing['price'])) ?> · <?= e($listing['category_name']) ?></p>
                            </td>
                            <td class="px-5 py-4 text-slate-600"><?= e($listing['owner_name']) ?><br><span class="text-xs"><?= e($listing['owner_email']) ?></span></td>
                            <td class="px-5 py-4 text-slate-600"><?= e($listing['ward_name']) ?>, <?= e($listing['province_name']) ?></td>
                            <td class="px-5 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black"><?= e($listing['status']) ?></span></td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <form action="<?= e(url('/admin/listings/' . $listing['id'] . '/approve')) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button class="rounded-full bg-emerald-50 px-3 py-2 font-bold text-emerald-700 hover:bg-emerald-100" type="submit">Approve</button>
                                    </form>
                                    <form action="<?= e(url('/admin/listings/' . $listing['id'] . '/reject')) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button class="rounded-full bg-amber-50 px-3 py-2 font-bold text-amber-700 hover:bg-amber-100" type="submit">Reject</button>
                                    </form>
                                    <form action="<?= e(url('/admin/listings/' . $listing['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this listing?');">
                                        <?= csrf_field() ?>
                                        <button class="rounded-full bg-red-50 px-3 py-2 font-bold text-red-700 hover:bg-red-100" type="submit">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($listings === []): ?>
                        <tr><td colspan="5" class="px-5 py-12 text-center text-slate-500">No listings found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
