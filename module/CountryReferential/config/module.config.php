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
            'api_get_delete' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/country[/:code]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Api',
                        'action'        => 'index',
                    ),
                ),
            ),
            'api_admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin/api',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller/[:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'api_admin_delete' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/api/delete/[:code]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'delete',
                    ),
                ),
            ),
            'api_admin_update' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/api/update/[:code]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'countryUpdate',
                    ),
                ),
            ),
            'api_admin_create' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/api/create',
                    'defaults' => array(
                        '__NAMESPACE__' => 'CountryReferential\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'countryCreate',
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
            'pays-table' => function($sm) {
                $tableGateway = $sm->get('pays-table-gateway');
                $table = new \CountryReferential\Model\PaysTable($tableGateway);
                
                return $table;
            },
            'pays-table-gateway' => function ($sm) {
                $dbAdapter = $sm->get('Zend\db\Adapter\Adapter');
                $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new \CountryReferential\Model\Pays());
                
                return new \Zend\Db\TableGateway\TableGateway('pays', $dbAdapter, null, $resultSetPrototype);
            },
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
            'layout/error'                     => __DIR__ . '/../view/layout/error.phtml',
            'country-referencial/index/index'   => __DIR__ . '/../view/country-referencial/index/index.phtml',
            'country-referencial/api/index'     => __DIR__ . '/../view/country-referencial/api/index.phtml',
            'error/404'                         => __DIR__ . '/../view/error/404.phtml',
            'error/index'                       => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
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