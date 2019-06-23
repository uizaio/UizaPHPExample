<?php

return array(
    'rootLogger' => array(
        'appenders' => array('web_log')
    ),
    'appenders' => array(
        'web_log' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%date %logger %-5level %msg%n'
                )
            ),
            'params' => array(
                'datePattern' => 'Y-m-d',
                'file' => '/var/log/topica/web-%s.log',
            ),
        )
    )
);


