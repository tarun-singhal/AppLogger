<?php
/**
 * API Logger
 */
namespace AppLogger\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{
    /**
     * Showlogs of all API hits
     * @return \Zend\View\Model\ViewModel
     */
    public function showLogAction()
    {
        $log = $this->getServiceLocator()->get('captureLog');
        $data = $log->getApiLog();
        return new ViewModel(array('logs' => $data));
    }    
}