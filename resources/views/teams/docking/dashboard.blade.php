@include('teams.partials.team-dashboard', [
    'teamColor'    => 'blue',
    'teamIcon'     => '⚓',
    'projectsRoute' => route('docking.projects'),
    'membersRoute'  => route('docking.members'),
    'groupsRoute'   => route('docking.groups'),
])
