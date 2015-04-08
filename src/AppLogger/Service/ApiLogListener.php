<?php
/**
 * ApiLog-Listner, to handle the cache on the url basis
 * @author Tarun Singhal
 * @date 27 March 2015
 */
namespace AppLogger\Service;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class ApiLogListener extends AbstractListenerAggregate
{

    protected $_listeners = array();

    protected $_service;
    
    protected $_param = array();

    public function __construct($service)
    {
        // We store the service from the service manager
        $this->_service = $service;
    }

    /**
     * attach listners
     * 
     * @param EventManagerInterface $events            
     */
    public function attach(EventManagerInterface $events)
    {
        
        // The AbstractListenerAggregate we are extending from allows us to attach our event listeners
        $this->_listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array(
            $this,
            'getApiLog'
        ));
        
        $this->_listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'getApiResponse'));
    }
    
    public function getApiResponse(MvcEvent $event)
    {
        if(!empty($this->_param['method'])) {
            $objListener = $this->_service->get('captureLog');
            
            
            $this->_param['statusCode'] = $event->getResponse()->getStatusCode();
            
            if($event->getResponse()->getStatusCode() == 200) {
                $content = $event->getResponse()->getContent();
                $jsonContent = json_decode($content);
                if(json_last_error() == JSON_ERROR_NONE) {
                    if(strlen($content) > 200) {
                        $content = substr($content, 0, 200). '...';
                    }
                    $this->_param['respContent'] = $content;
                } else {
                    $this->_param['respContent'] = "HTML Entity";
                }
            } else {
                $this->_param['respContent'] = 'Error Occurs, Please check your API';
            }
            $this->_param['version'] = $event->getApplication()->getRequest()->getVersion();

            //Save Api log into file
            $objListener->apiLog($this->_param);
        }
    }

    /**
     * Get Cache from the called event
     * 
     * @param MvcEvent $event            
     */
    public function getApiLog(MvcEvent $event)
    {
        $match = $event->getRouteMatch();
        if ($match->getParam('controller')) {
            $controller = $match->getParam('controller');
        }
        
        $this->_param = array(
            'method' => $event->getApplication()->getRequest()->getMethod(),
            'uri' => $event->getApplication()->getRequest()->getUriString(),
            'controller' => $controller
        );
        
        if ($event->getApplication()->getRequest()->getMethod() == 'POST') {
            $this->_param['input'] = json_encode($event->getApplication()->getRequest()->getPost());
        } else {
            $this->_param['input'] = json_encode($event->getApplication()->getRequest()->getQuery());
        }
    }
}