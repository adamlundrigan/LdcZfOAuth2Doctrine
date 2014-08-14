<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'LdcZfOAuth2Doctrine\\DoctrineOrmAdapter' => 'LdcZfOAuth2Doctrine\\DoctrineOrmAdapterFactory',
        ),
    ),
    'zf-oauth2' => array(
        'storage' => 'LdcZfOAuth2Doctrine\\DoctrineOrmAdapter',
        'object_manager' => 'Doctrine\\ORM\\EntityManager',
    ),
    'doctrine' => array(
        'driver' => array(
            'ldc-zf-oauth2-doctrine_driver' => array(
                'class' => 'Doctrine\\ORM\\Mapping\\Driver\\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'LdcZfOAuth2Doctrine\\Entity' => 'ldc-zf-oauth2-doctrine_driver',
                ),
            ),
        ),
    ),

);