<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SOCKET_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 2,
            'reload_async' => true,
            'max_wait_time'=>3,
            'open_http_protocol' => true
        ],
        'TASK'=>[
            'workerNum'=>1,
            'maxRunningNum'=>128,
            'timeout'=>15
        ]
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    'MYSQL'  => [
        'host'          => '192.168.8.102',
        'port'          => 3306,
        'user'          => 'root',
        'password'      => 'root',
        'database'      => 'my_project',
        'timeout'       => 5,
        'charset'       => 'utf8mb4',
    ],
    'REDIS'  => [
        'host'          => '127.0.0.1',
        'port'          => 6379,
        'auth'          =>'easyswoole'
    ]
];
