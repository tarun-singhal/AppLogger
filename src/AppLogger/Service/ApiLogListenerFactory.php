<?php 
/**
 * ApiLogListner factory 
 * @author Tarun Singhal
 * @date 27 March 2015
 */
namespace AppLogger\Service;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
 
class ApiLogListenerFactory implements FactoryInterface {
 
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new ApiLogListener($serviceLocator);
    }
}