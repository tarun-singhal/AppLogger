<?php

namespace AppLogger\Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class ExceptionErrorLogEvent implements EventManagerAwareInterface
{
    protected $events;
    
    /**
     * Setting the Event
     * 
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }
    
    /**
     * Getting the Event
     * 
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
    public function getEventManager()
    {
        if (!$this->events) {
            $this->setEventManager(new EventManager(__CLASS__));
        }
        return $this->events;
    }
    
    /**
     *  Triggering exception Event
     * @param Array $params            
     */
    public function setExceptions($params)
    {
        $this->getEventManager()->trigger(__FUNCTION__, $this, $params);
    }
    
    
}
