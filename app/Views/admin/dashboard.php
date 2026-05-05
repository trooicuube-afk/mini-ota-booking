<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-black uppercase tracking-widest text-estate-700">Admin</p>
            <h1 class="mt-2 text-4xl font-black text-slate-950">Moderation dashboard</h1>
            <p class="mt-2 text-slate-600">Approve, reject, delete listings, and manage users.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= e(url('/admin/listings')) ?>" class="rounded-full bg-estate-700 px-5 py-3 text-sm font-black text-white hover:bg-estate-900">Listings</a>
            <a href="<?= e(url('/admin/users')) ?>" class="rounded-full bg-white px-5 py-3 text-sm font-black text-slate-700 shadow-sm hover:bg-slate-50">Users</a>
        </div>
    </div>
    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <?php foreach ([
            'Total listings' => $totalListings,
            'Pending' => $pendingListings,
            'Approved' => $approvedListings,
            'Rejected' => $rejectedListings,
            'Users' => $usersCount,
        ] as $label => $value): ?>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wider text-slate-500"><?= e($label) ?></p>
                <p class="mt-3 text-4xl font-black text-slate-950"><?= e($value) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
