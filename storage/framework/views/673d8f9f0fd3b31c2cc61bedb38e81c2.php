<?php echo $__env->make('teams.partials.team-dashboard', [
    'teamColor'     => 'green',
    'teamIcon'      => '🏗',
    'projectsRoute' => route('new-building.projects'),
    'membersRoute'  => route('new-building.members'),
    'groupsRoute'   => route('new-building.groups'),
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/new-building/dashboard.blade.php ENDPATH**/ ?>