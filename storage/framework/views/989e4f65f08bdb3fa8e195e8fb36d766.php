
<?php
    $color = $teamColor ?? 'indigo';
    $icon  = $teamIcon  ?? '🏗';
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
                <?php echo e($icon); ?> <?php echo e($team->name); ?> Dashboard
            </h2>
            <?php if($userRole): ?>
                <span class="bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-800 text-xs font-semibold px-3 py-1 rounded-full">
                    <?php echo e($userRole->name ?? $userRole->code); ?>

                </span>
            <?php endif; ?>
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

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="<?php echo e($projectsRoute); ?>"
                   class="block p-4 bg-<?php echo e($color); ?>-50 border border-<?php echo e($color); ?>-200 rounded-lg hover:bg-<?php echo e($color); ?>-100 transition text-center">
                    <p class="text-2xl mb-1">📋</p>
                    <p class="text-sm font-medium text-<?php echo e($color); ?>-800">Projects</p>
                </a>
                <a href="<?php echo e($membersRoute); ?>"
                   class="block p-4 bg-<?php echo e($color); ?>-50 border border-<?php echo e($color); ?>-200 rounded-lg hover:bg-<?php echo e($color); ?>-100 transition text-center">
                    <p class="text-2xl mb-1">👥</p>
                    <p class="text-sm font-medium text-<?php echo e($color); ?>-800">
                        Members <span class="text-xs">(<?php echo e($members->count()); ?>)</span>
                    </p>
                </a>
                <a href="<?php echo e($groupsRoute); ?>"
                   class="block p-4 bg-<?php echo e($color); ?>-50 border border-<?php echo e($color); ?>-200 rounded-lg hover:bg-<?php echo e($color); ?>-100 transition text-center">
                    <p class="text-2xl mb-1">🗂</p>
                    <p class="text-sm font-medium text-<?php echo e($color); ?>-800">
                        Groups <span class="text-xs">(<?php echo e($groups->count()); ?>)</span>
                    </p>
                </a>
                <div class="block p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <p class="text-2xl mb-1">🔑</p>
                    <p class="text-sm font-medium text-gray-600">
                        Roles <span class="text-xs">(<?php echo e($roles->count()); ?>)</span>
                    </p>
                </div>
            </div>

            
            <?php if($userGroups->isNotEmpty()): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-base font-semibold text-gray-800 mb-3">My Groups</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $userGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700 text-sm px-3 py-1 rounded-full">
                                    <?php echo e($group->name ?? $group->code); ?>

                                    <?php if($group->pivot->global ?? false): ?>
                                        <span class="text-xs ml-1 text-gray-500">(global)</span>
                                    <?php endif; ?>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-gray-800">Team Members</h3>
                        <a href="<?php echo e($membersRoute); ?>" class="text-sm text-<?php echo e($color); ?>-600 hover:underline">View all →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $members->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($member->name); ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($member->email); ?></td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php $role = $team->userRole($member); ?>
                                            <?php if($role): ?>
                                                <span class="bg-<?php echo e($color); ?>-100 text-<?php echo e($color); ?>-700 text-xs px-2 py-1 rounded">
                                                    <?php echo e($role->name ?? $role->code); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="px-4 py-4 text-center text-gray-500 text-sm">No members yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/partials/team-dashboard.blade.php ENDPATH**/ ?>