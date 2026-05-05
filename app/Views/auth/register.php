<section class="mx-auto max-w-md px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="text-3xl font-black text-slate-950">Create your account</h1>
        <p class="mt-2 text-sm text-slate-600">Join LHT Estate to publish and save property listings.</p>
        <form action="<?= e(url('/register')) ?>" method="post" class="mt-8 grid gap-5">
            <?= csrf_field() ?>
            <label class="grid gap-2">
                <span class="text-sm font-bold text-slate-700">Name</span>
                <input name="name" value="<?= e(old('name')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                <?php if (!empty($errors['name'])): ?><span class="text-sm text-red-700"><?= e($errors['name']) ?></span><?php endif; ?>
            </label>
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
            <label class="grid gap-2">
                <span class="text-sm font-bold text-slate-700">Confirm password</span>
                <input type="password" name="password_confirmation" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                <?php if (!empty($errors['password_confirmation'])): ?><span class="text-sm text-red-700"><?= e($errors['password_confirmation']) ?></span><?php endif; ?>
            </label>
            <button class="rounded-2xl bg-estate-700 px-5 py-3 font-black text-white hover:bg-estate-900" type="submit">Create account</button>
        </form>
        <p class="mt-6 text-sm text-slate-600">Already registered? <a href="<?= e(url('/login')) ?>" class="font-bold text-estate-700">Sign in</a>.</p>
    </div>
</section>
