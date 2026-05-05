<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-black text-slate-950">Manage users</h1>
    <p class="mt-2 text-slate-600">Update roles and account status.</p>
    <div class="mt-8 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-black uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">User</th>
                        <th class="px-5 py-4">Email</th>
                        <th class="px-5 py-4">Created</th>
                        <th class="px-5 py-4">Role/status</th>
                        <th class="px-5 py-4">Save</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-5 py-4 font-black text-slate-950"><?= e($user['name']) ?></td>
                            <td class="px-5 py-4 text-slate-600"><?= e($user['email']) ?></td>
                            <td class="px-5 py-4 text-slate-600"><?= e($user['created_at']) ?></td>
                            <td class="px-5 py-4">
                                <form id="user-<?= e($user['id']) ?>" action="<?= e(url('/admin/users/' . $user['id'] . '/update')) ?>" method="post" class="flex gap-2">
                                    <?= csrf_field() ?>
                                    <select name="role" class="rounded-xl border border-slate-300 px-3 py-2">
                                        <option value="user" <?= selected($user['role'], 'user') ?>>User</option>
                                        <option value="admin" <?= selected($user['role'], 'admin') ?>>Admin</option>
                                    </select>
                                    <select name="status" class="rounded-xl border border-slate-300 px-3 py-2">
                                        <option value="active" <?= selected($user['status'], 'active') ?>>Active</option>
                                        <option value="disabled" <?= selected($user['status'], 'disabled') ?>>Disabled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-5 py-4">
                                <button form="user-<?= e($user['id']) ?>" class="rounded-full bg-estate-700 px-4 py-2 font-black text-white hover:bg-estate-900" type="submit">Update</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
