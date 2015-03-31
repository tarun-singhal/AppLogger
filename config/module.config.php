<?php 
return array(
    
    'router' => array(
        'routes' => array(
            'applog' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/applog',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action'     => 'showLog',
                    ),
                ),
            ),
        ),
    ),
    
    'controllers' => array(
        'invokables' => array(
            'Index' => 'AppLogger\Controller\IndexController'
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'log_flag' => array(
        'api_log' => 1,
        'exception_log' => 1,
        'api_log_size' => 50,
    ),
    
    'log_paths' => array(
        'api_log' => './log/api_log/',
        'exception_log' => './log/exception_log/',
    ),
);

?>