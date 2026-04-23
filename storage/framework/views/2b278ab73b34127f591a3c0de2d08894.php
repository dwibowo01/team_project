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
                🏢 <?php echo e(__('Management Dashboard')); ?>

            </h2>
            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                Super Admin
            </span>
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

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold text-gray-800"><?php echo e($team->name); ?></h3>
                                <span class="bg-indigo-100 text-indigo-700 text-xs px-2 py-1 rounded-full">
                                    <?php echo e($team->users_count); ?> <?php echo e(Str::plural('member', $team->users_count)); ?>

                                </span>
                            </div>
                            <?php if($team->description): ?>
                                <p class="text-gray-500 text-sm mb-4"><?php echo e($team->description); ?></p>
                            <?php endif; ?>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('management.view-team', $team)); ?>"
                                   class="text-sm text-indigo-600 hover:underline">
                                    View Dashboard →
                                </a>
                                <a href="<?php echo e(route('management.team-members', $team)); ?>"
                                   class="text-sm text-gray-500 hover:underline ml-3">
                                    Manage Members
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <?php if($managementTeam): ?>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            Management Team Members
                        </h3>
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
                                    <?php $__currentLoopData = $managementTeam->allUsers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($member->name); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($member->email); ?></td>
                                            <td class="px-4 py-3 text-sm">
                                                <?php $role = $managementTeam->userRole($member); ?>
                                                <?php if($role): ?>
                                                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-1 rounded">
                                                        <?php echo e($role->name ?? $role->code); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-400 text-xs">—</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/management/dashboard.blade.php ENDPATH**/ ?>