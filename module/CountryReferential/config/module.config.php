<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'CountryReferential\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'api_get' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/api/country',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Api',
                        'action'        => 'get',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:code]',
                            'constraints' => array(
                                'code' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'CountryReferential\Controller\Index' => 'CountryReferential\Controller\IndexController',
            'CountryReferential\Controller\Api' => 'CountryReferential\Controller\ApiController',
            'CountryReferential\Controller\Admin' => 'CountryReferential\Controller\AdminController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'                     => __DIR__ . '/../view/layout/layout.phtml',
            'country-referencial/index/index'   => __DIR__ . '/../view/country-referencial/index/index.phtml',
            'country-referencial/api/index'     => __DIR__ . '/../view/country-referencial/api/index.phtml',
            'error/404'                         => __DIR__ . '/../view/error/404.phtml',
            'error/index'                       => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);