<?php echo $__env->make('teams.partials.team-dashboard', [
    'teamColor'     => 'orange',
    'teamIcon'      => '🌍',
    'projectsRoute' => route('site.projects'),
    'membersRoute'  => route('site.members'),
    'groupsRoute'   => route('site.groups'),
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /home/runner/work/team_project/team_project/resources/views/teams/site/dashboard.blade.php ENDPATH**/ ?>