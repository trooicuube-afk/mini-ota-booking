<?php $isEdit = $listing !== null; ?>
<section class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <h1 class="text-3xl font-black text-slate-950"><?= $isEdit ? 'Edit listing' : 'Post a property' ?></h1>
        <p class="mt-2 text-slate-600">Submitted listings enter pending status until an admin approves them.</p>

        <form action="<?= e(url($action)) ?>" method="post" enctype="multipart/form-data" class="mt-8 grid gap-6">
            <?= csrf_field() ?>
            <div class="grid gap-6 md:grid-cols-2">
                <label class="grid gap-2 md:col-span-2">
                    <span class="text-sm font-bold text-slate-700">Title</span>
                    <input name="title" value="<?= e(old('title', $listing['title'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['title'])): ?><span class="text-sm text-red-700"><?= e($errors['title']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Category</span>
                    <select name="category_id" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                        <option value="">Choose category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= e($category['id']) ?>" <?= selected(old('category_id', $listing['category_id'] ?? ''), $category['id']) ?>><?= e($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['category_id'])): ?><span class="text-sm text-red-700"><?= e($errors['category_id']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Province</span>
                    <select name="province_id" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                        <option value="">Choose province</option>
                        <?php foreach ($provinces as $province): ?>
                            <option value="<?= e($province['id']) ?>" <?= selected(old('province_id', $listing['province_id'] ?? ''), $province['id']) ?>><?= e($province['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['province_id'])): ?><span class="text-sm text-red-700"><?= e($errors['province_id']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Ward</span>
                    <select name="ward_id" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                        <option value="">Choose ward</option>
                        <?php foreach ($wards as $ward): ?>
                            <option value="<?= e($ward['id']) ?>" <?= selected(old('ward_id', $listing['ward_id'] ?? ''), $ward['id']) ?>><?= e($ward['name']) ?> · <?= e($ward['type']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['ward_id'])): ?><span class="text-sm text-red-700"><?= e($errors['ward_id']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Price (VND)</span>
                    <input type="number" min="100000" step="100000" name="price" value="<?= e(old('price', $listing['price'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['price'])): ?><span class="text-sm text-red-700"><?= e($errors['price']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Area (m²)</span>
                    <input type="number" min="1" step="0.1" name="area" value="<?= e(old('area', $listing['area'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['area'])): ?><span class="text-sm text-red-700"><?= e($errors['area']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2 md:col-span-2">
                    <span class="text-sm font-bold text-slate-700">Address</span>
                    <input name="address" value="<?= e(old('address', $listing['address'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['address'])): ?><span class="text-sm text-red-700"><?= e($errors['address']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Contact name</span>
                    <input name="contact_name" value="<?= e(old('contact_name', $listing['contact_name'] ?? current_user()['name'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['contact_name'])): ?><span class="text-sm text-red-700"><?= e($errors['contact_name']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2">
                    <span class="text-sm font-bold text-slate-700">Contact phone</span>
                    <input name="contact_phone" value="<?= e(old('contact_phone', $listing['contact_phone'] ?? '')) ?>" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required>
                    <?php if (!empty($errors['contact_phone'])): ?><span class="text-sm text-red-700"><?= e($errors['contact_phone']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2 md:col-span-2">
                    <span class="text-sm font-bold text-slate-700">Description</span>
                    <textarea name="description" rows="8" class="rounded-2xl border border-slate-300 px-4 py-3 focus:border-estate-600 focus:outline-none" required><?= e(old('description', $listing['description'] ?? '')) ?></textarea>
                    <?php if (!empty($errors['description'])): ?><span class="text-sm text-red-700"><?= e($errors['description']) ?></span><?php endif; ?>
                </label>
                <label class="grid gap-2 md:col-span-2">
                    <span class="text-sm font-bold text-slate-700">Images (JPG, PNG, WebP, max 3 MB each)</span>
                    <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp" multiple class="rounded-2xl border border-dashed border-slate-300 px-4 py-6">
                    <?php if (!empty($errors['images'])): ?><span class="text-sm text-red-700"><?= e($errors['images']) ?></span><?php endif; ?>
                </label>
            </div>
            <button class="rounded-2xl bg-estate-700 px-6 py-4 font-black text-white hover:bg-estate-900" type="submit"><?= $isEdit ? 'Update and resubmit' : 'Submit for review' ?></button>
        </form>
    </div>
</section>
