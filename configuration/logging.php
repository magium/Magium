<?php

return [
    'instance'  => [
        Magium\Util\Log\Logger::class   => [
            'parameters'    => [
                'options'   => [
                    'writers' => [
                        [
                            'name' => \Zend\Log\Writer\Stream::class,
                            'options' => [
                                'stream' => '/tmp/magium.log'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
