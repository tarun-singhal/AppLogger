<?php
/**
 * AppLogger Module
 * @author Tarun Singhal
 * @version 1.0
 * @date-created 30 March, 2015
 */
namespace AppLogger;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use AppLogger\Event\ExceptionErrorLogEvent;
class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__. '/src/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'captureLog' => 'AppLogger\Lib\CaptureLog',
                'ExceptionErrorLogEvent' => 'AppLogger\Event\ExceptionErrorLogEvent',
            ),
        );
    }

    /**
     * todo 
     * 1. log controller and action name - done
     * 2. action profilling
     * 
     * @param MvcEvent $ev
     */
    public function onBootstrap(MvcEvent $ev)
    {
        $sm = $ev->getApplication()->getServiceManager();
        $eventManager = $ev->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $resp = $ev->getApplication()->getRequest();
        $config = $sm->get('config');

        // Handle Api-Log
        if ($config['log_flag']['api_log']) {
            // get the api-log listener service
            $apiLogListener = $ev->getApplication()->getServiceManager()->get('ApiLogListener');
            // attach the listeners to the event manager
            $ev->getApplication()->getEventManager()->attach($apiLogListener);
        } //End of Api-Log
        
        // Handle data exception log
        if ($config['log_flag']['exception_log']) {
            
            $exceptionEvent = $sm->get('ExceptionErrorLogEvent');
            /**
             * Attached New Application Event *
             */
            $exceptionEvent->getEventManager()
                ->getSharedManager()
                ->attach('AppLogger\Event\ExceptionErrorLogEvent', 'setExceptions', function ($e) use($sm)
            {
                $objListener = $sm->get('captureLog');
                $objListener->exceptionLog($e->getParams());
            });
        } //End of Exception Log
        
    } //End of onBootrap() method
} //End of Class
