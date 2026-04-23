@include('teams.partials.team-dashboard', [
    'teamColor'     => 'green',
    'teamIcon'      => '🏗',
    'projectsRoute' => route('new-building.projects'),
    'membersRoute'  => route('new-building.members'),
    'groupsRoute'   => route('new-building.groups'),
])
