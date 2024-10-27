<?php

// config for rono516/EnumsToJson
return [
    'enum_locations' => [
        app_path(),
    ],

    // the disk defined in filesystem.php  where json files will be stored
    'disk'=> 'public',

    // folder on which the json file will be generated
    'path' => '/shared',
];
