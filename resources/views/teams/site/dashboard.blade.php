@include('teams.partials.team-dashboard', [
    'teamColor'     => 'orange',
    'teamIcon'      => '🌍',
    'projectsRoute' => route('site.projects'),
    'membersRoute'  => route('site.members'),
    'groupsRoute'   => route('site.groups'),
])
