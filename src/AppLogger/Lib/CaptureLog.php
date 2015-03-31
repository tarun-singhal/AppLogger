<?php
/**
 * Capture API and Exception logs
 */
namespace AppLogger\Lib;

use Zend\Http\PhpEnvironment\RemoteAddress;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;
use ZF\Rest\AbstractResourceListener;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class CaptureLog extends AbstractResourceListener implements ServiceLocatorAwareInterface
{
    
    protected $_serviceLocator;
    
    /**
     * Set serviceManager instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->_serviceLocator = $serviceLocator;
    }
    
    /**
     * Retrieve serviceManager instance
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->_serviceLocator;
    }

    /**
     * @desc To capture the exception-log in log file
     * @param array $log
     */
    public function exceptionLog($log)
    {
        $remote = new RemoteAddress();
        $log['ip'] = $remote->getIpAddress();
        $errorLog = implode(' ### ', $log);
        
        $logPath = $this->getLogPath(__FUNCTION__);
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
        $log = new Logger();
        $file = 'exception_log_'.date('d-m-y') . '.txt';
        $writer = new \Zend\Log\Writer\Stream($logPath . $file);
        
        $logger = $log->addWriter($writer);
        $logger->log(\Zend\Log\Logger::ALERT, $errorLog);// Alert: action must be taken immediately
    } 

    /**
     * @desc To capture the exception-log in log file
     * @param array $log
     */
    public function apiLog($log)
    {
        $remote = new RemoteAddress();
        $log['ip'] = $remote->getIpAddress();
        $apiLog = implode(' ### ', $log);
        $apiLog = ' ### '.$apiLog;
        $logPath = $this->getLogPath(__FUNCTION__);
        if (!file_exists($logPath)) {
            mkdir($logPath, 0777, true);
        }
        $log = new Logger();
        $file = 'api_log_'.date('d-m-y') . '.txt';
        $writer = new \Zend\Log\Writer\Stream($logPath . $file);
        
        $logger = $log->addWriter($writer);
        $logger->log(\Zend\Log\Logger::INFO, $apiLog);
    }
    
    /**
     * Fetch proper log path
     * @param string $param log name
     * @return string logPath
     * */
    private function getLogPath($param)
    {
        $config = $this->getServiceLocator()->get('config');
    	switch ($param)
    	{
    		case 'exceptionLog':
    		    return $config['log_paths']['exception_log'];
    		    break;
    		case 'apiLog':
                return $config['log_paths']['api_log'];
    		    break;
    	}
    	
    }

    /**
     * Get API Log
     * @return multitype:
     */
    public function getApiLog()
    {
        $config = $this->getServiceLocator()->get('config');
        $log_size = $config['log_flag']['api_log_size'];
        $logPath = $this->getLogPath('apiLog');
        $file = $logPath.'api_log_'.date('d-m-y'). '.txt';
        return array_reverse(array_slice(file($file), -$log_size, $log_size));
    }
}

?>