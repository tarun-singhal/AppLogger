# AppLogger
AppLogger is basically used to log Rest-API and has ability to connect with the Apigility

* Steps to Enable and usage of AppLogger Module

Step 1 :To enable the Module in ZF2, Include the module name inside application.config.php file
```php
return array(
  ...,
  ...,
  'AppLogger'
);
```

Step 2: Please check the log directory exist at module root level, 
Please create api_log/ inside log dir. and should have full permission to write and create files inside api_log dir.

Step 3: If you want to use in ZF2-ApiGility, then enable the API-LOG button on home view of Apigility.
Place the below code inside vendor/zfcampus/zf-apigility-welcome/view/zf-apigility-welcome/welcome.phtml and 
locate "API Documentation" key.
```php
    <?php if (class_exists('AppLogger\Module', false)): ?>
        <a href="<?php echo $this->url('applog') ?>" class="btn btn-lg ag-welcome-btn-outline">API Log</a>
    <?php endif; ?>
```
Step 4: If you are not using it in Apigility then you can call the Log view via:
http://<domain-name>/applog

I hope it will help you to monitor the API Calls.


