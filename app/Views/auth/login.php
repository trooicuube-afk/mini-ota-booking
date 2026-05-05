<section class="mx-auto max-w-md px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-black text-slate-950">Welcome back</h1>
        <p class="mt-2 text-sm text-slate-600">Sign in to post, save, and manage listings.</p>
        <form action="<?= e(url('/login')) ?>" method="post" class="mt-8 grid gap-5">
            <?= csrf_field() ?>
            <label class="grid gap-2">
                <span class="text-sm font-bold text-slate-700">Email</span>
                <input type="email" name="email" value="<?= e(old('email')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                <?php if (!empty($errors['email'])): ?><span class="text-sm text-red-700"><?= e($errors['email']) ?></span><?php endif; ?>
            </label>
            <label class="grid gap-2">
                <span class="text-sm font-bold text-slate-700">Password</span>
                <input type="password" name="password" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                <?php if (!empty($errors['password'])): ?><span class="text-sm text-red-700"><?= e($errors['password']) ?></span><?php endif; ?>
            </label>
            <button class="rounded-2xl bg-estate-700 px-5 py-3 font-black text-white hover:bg-estate-900" type="submit">Sign in</button>
        </form>
        <p class="mt-6 text-sm text-slate-600">No account? <a href="<?= e(url('/register')) ?>" class="font-bold text-estate-700">Create one</a>.</p>
    </div>
</section>
