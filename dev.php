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
        'host'          => '192.168.137.34',
        'port'          => 3306,
        'user'          => 'root',
        'password'      => 'root',
        'database'      => 'my_project',
        'timeout'       => 5,
        'charset'       => 'utf8mb4',
    ],
    'REDIS'  => [
        'host'          => '192.168.8.13',
        'port'          => 6379,
        'auth'          =>'123456',
        'db'            =>0,
        'timeout'       =>10000
    ],
    //调取阿里api，根据ip地址获取实际地址
    'IPAPI' => [
        'app_code'      =>'a17c66063e8b4c1e98d582634a912cd7',
        'host'          =>'http://api01.aliyun.venuscn.com'
    ]
];
