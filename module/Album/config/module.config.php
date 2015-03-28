<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'Album_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Album/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Album\Entity' =>  'Album_driver'
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'album_home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/album',
                    'defaults' => array(
                        'controller' => 'Album\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Album\Controller\Index' => 'Album\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/album/layout/layout.phtml',
            'album/index/index' => __DIR__ . '/../view/album/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/album/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/album/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);