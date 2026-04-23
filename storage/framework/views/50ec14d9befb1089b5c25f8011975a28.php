
<?php
    $color = $teamColor ?? 'indigo';
    $icon  = $teamIcon  ?? '🗂';
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e($icon); ?> <?php echo e($team->name); ?> — Groups
            </h2>
            <a href="<?php echo e($backRoute); ?>" class="text-sm text-<?php echo e($color); ?>-600 hover:underline">← Dashboard</a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            
            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 rounded p-4"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 rounded p-4"><?php echo e(session('error')); ?></div>
            <?php endif; ?>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Create New Group</h3>
                    <form method="POST" action="<?php echo e(route('teams.groups.store', $team)); ?>" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                                <input type="text" name="name" placeholder="e.g. Team Leads"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Group Code</label>
                                <input type="text" name="code" placeholder="e.g. leads"
                                       class="w-full border-gray-300 rounded-md shadow-sm text-sm" required />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Permissions <span class="text-gray-400 text-xs">(comma-separated, e.g. projects.view,projects.edit)</span>
                            </label>
                            <input type="text" name="permissions_raw" placeholder="projects.view, projects.edit"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm" />
                            <p class="text-xs text-gray-400 mt-1">Note: Use wildcard <code>projects.*</code> to grant all project permissions.</p>
                        </div>
                        <button type="submit" class="bg-<?php echo e($color); ?>-600 text-white text-sm px-4 py-2 rounded hover:bg-<?php echo e($color); ?>-700">
                            Create Group
                        </button>
                    </form>
                </div>
            </div>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">
                        Groups (<?php echo e($groups->count()); ?>)
                    </h3>
                    <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-800"><?php echo e($group->name ?? $group->code); ?></h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Code: <code><?php echo e($group->code); ?></code></p>
                                </div>
                                <form method="POST" action="<?php echo e(route('teams.groups.destroy', [$team, $group->code])); ?>"
                                      onsubmit="return confirm('Delete group <?php echo e($group->name ?? $group->code); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>

                            
                            <?php if($group->permissions && $group->permissions->isNotEmpty()): ?>
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Permissions:</p>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $group->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">
                                                <?php echo e($perm->name ?? $perm->code ?? $perm); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <?php if($group->users && $group->users->isNotEmpty()): ?>
                                <div class="mt-3">
                                    <p class="text-xs font-medium text-gray-600 mb-1">Members:</p>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $group->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="bg-<?php echo e($color); ?>-50 text-<?php echo e($color); ?>-700 text-xs px-2 py-0.5 rounded-full">
                                                <?php echo e($gUser->name); ?>

                                                <?php if($gUser->pivot->global ?? false): ?>
                                                    <span class="text-gray-400">(global)</span>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            
                            <div class="mt-3 flex gap-2">
                                <form method="POST" action="<?php echo e(route('teams.groups.users.store', [$team, $group->code])); ?>"
                                      class="flex gap-2">
                                    <?php echo csrf_field(); ?>
                                    <input type="number" name="user_id" placeholder="User ID"
                                           class="w-28 border-gray-300 rounded text-xs py-1" />
                                    <button type="submit" class="text-xs text-<?php echo e($color); ?>-600 border border-<?php echo e($color); ?>-300 px-2 py-1 rounded hover:bg-<?php echo e($color); ?>-50">
                                        + Add Member
                                    </button>
                                </form>
                                <form method="POST" action="<?php echo e(route('teams.groups.global-users.store', [$team, $group->code])); ?>"
                                      class="flex gap-2">
                                    <?php echo csrf_field(); ?>
                                    <input type="number" name="user_id" placeholder="User ID"
                                           class="w-28 border-gray-300 rounded text-xs py-1" />
                                    <button type="submit" class="text-xs text-orange-600 border border-orange-300 px-2 py-1 rounded hover:bg-orange-50">
                                        + Add Global
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-sm">No groups yet. Create one above.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/partials/groups-view.blade.php ENDPATH**/ ?>