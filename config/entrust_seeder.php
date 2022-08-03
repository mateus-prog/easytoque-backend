<?php

return [
    'role_structure' => [
        'administrator' => [
            'users' => 'c,r,u,d',
            'admin' => 'c,r,u,d'
        ],
        'collaborator' => [
            'users' => 'r'
        ],
        'financial' => [
            'users' => 'r'
        ],
        'partner' => [
            'users' => 'r',
            'logos' => 'c,r,u,d'
        ],
    ],
    'user_roles' => [
        'administrator' => [
            [
                "first_name" => "Admin", 
                "last_name" => "Admin", 
                "email" => "mateus.guizelini@hotmail.com", 
                "password" => "123456",
                "status_user_id" => 1,
                "role_id" => 1
            ],
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];