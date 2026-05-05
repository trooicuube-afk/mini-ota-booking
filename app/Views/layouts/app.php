<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? config('name')) ?></title>
    <meta name="description" content="LHT Estate is a focused real estate classified marketplace for buying, renting, posting, saving, and approving property listings.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        estate: {
                            50: '#f3f8f6',
                            100: '#dceee6',
                            600: '#0f766e',
                            700: '#0f5f59',
                            900: '#0b2f2d'
                        },
                        clay: '#b45309'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="<?= e(url('/')) ?>" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-estate-700 font-black text-white">LHT</span>
                <span>
                    <span class="block text-lg font-black tracking-tight text-estate-900">LHT Estate</span>
                    <span class="block text-xs font-medium text-slate-500">Verified property classifieds</span>
                </span>
            </a>
            <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-600 md:flex">
                <a class="hover:text-estate-700" href="<?= e(url('/listings')) ?>">Browse</a>
                <a class="hover:text-estate-700" href="<?= e(url('/post')) ?>">Post</a>
                <?php if ($currentUser !== null): ?>
                    <a class="hover:text-estate-700" href="<?= e(url('/my-listings')) ?>">My listings</a>
                    <a class="hover:text-estate-700" href="<?= e(url('/saved')) ?>">Saved</a>
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                        <a class="hover:text-estate-700" href="<?= e(url('/admin')) ?>">Admin</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
            <div class="flex items-center gap-2">
                <?php if ($currentUser === null): ?>
                    <a href="<?= e(url('/login')) ?>" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Login</a>
                    <a href="<?= e(url('/register')) ?>" class="rounded-full bg-estate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-estate-900">Register</a>
                <?php else: ?>
                    <span class="hidden text-sm text-slate-600 sm:inline">Hi, <?= e($currentUser['name']) ?></span>
                    <form action="<?= e(url('/logout')) ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100" type="submit">Logout</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>
        <?php if ($success): ?>
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"><?= e($success) ?></div>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mx-auto mt-4 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800"><?= e($error) ?></div>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <footer class="mt-16 border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 text-sm text-slate-600 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <p class="text-base font-black text-estate-900">LHT Estate</p>
                <p class="mt-2">A native PHP real estate marketplace MVP with user listings, saved homes, and admin moderation.</p>
            </div>
            <div>
                <p class="font-bold text-slate-900">Marketplace</p>
                <div class="mt-2 grid gap-2">
                    <a href="<?= e(url('/listings')) ?>" class="hover:text-estate-700">Browse listings</a>
                    <a href="<?= e(url('/post')) ?>" class="hover:text-estate-700">Post property</a>
                    <a href="<?= e(url('/saved')) ?>" class="hover:text-estate-700">Saved listings</a>
                </div>
            </div>
            <div>
                <p class="font-bold text-slate-900">Trust</p>
                <p class="mt-2">Original brand, original UI, local seed content, no copied code, logos, or media from reference sites.</p>
            </div>
        </div>
    </footer>
</body>
</html>
