<?php

function getSystemRoles($role = null)
{
    $data = 'App\Models\Role'::when($role, function ($data) use ($role) {
        if ($role) {
            $data->where('id', '=',  $role);
        }
    })->get();
    return $data;
}

function runTimeChecked($myId, $matchId)
{
    return ((int)$myId === (int)$matchId) ? 'checked' : '';
}


function runTimeSelection($myId, $matchId)
{
    if ($myId == $matchId)
        return 'selected';
}

function modulesList()
{
    return [
        [
            'id' => 1,
            'slug' => 'product'
        ],
        [
            'id' => 2,
            'slug' => 'roles'
        ],
        [
            'id' => 3,
            'slug' => 'users'
        ],
        [
            'id' => 4,
            'slug' => 'settings'
        ],
        [
            'id' => 5,
            'slug' => 'dashboard'
        ],
        [
            'id' => 6,
            'slug' => 'warehouse'
        ],
        [
            'id' => 7,
            'slug' => 'lots'
        ],
        [
            'id' => 8,
            'slug' => 'export'
        ],
    ];
}

if (!function_exists('canPerform')) {
    function canPerform($permission)
    {
        // Ensure it's numeric
        $permission = is_numeric($permission) ? (int)$permission : 0;

        $levels = [
            1 => ['view'],
            2 => ['view', 'edit'],
            3 => ['view', 'add'],
            4 => ['view', 'add', 'edit'],
            5 => ['view', 'add', 'edit', 'delete'],
        ];

        return $levels[$permission] ?? [];
    }
}

