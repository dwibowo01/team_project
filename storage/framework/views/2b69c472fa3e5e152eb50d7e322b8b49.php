<?php echo $__env->make('teams.partials.team-dashboard', [
    'teamColor'    => 'blue',
    'teamIcon'     => '⚓',
    'projectsRoute' => route('docking.projects'),
    'membersRoute'  => route('docking.members'),
    'groupsRoute'   => route('docking.groups'),
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/docking/dashboard.blade.php ENDPATH**/ ?>