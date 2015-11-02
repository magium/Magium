<?php

return [
    'instance'  => [
        'Zend\Log\Logger'   => [
            'parameters'    => [
                'options'   => [
                    'writers' => [
                        [
                            'name' => 'Zend\Log\Writer\Stream',
                            'options' => ['stream' => '/temp/log.log']
                        ]
                    ]
                ]
            ]
        ]
    ]

];