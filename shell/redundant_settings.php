<?php

require_once 'abstract.php';


class JH_RemoveRedundantConfig_Shell_Scheduler extends Mage_Shell_Abstract

{
	
	public function run()
	{
	
		try {
			$action = $this->getArg('action');
            if (empty($action)) {
                echo $this->usageHelp();
            } else {
	            $actionMethodName = $action . 'Action';
                if (method_exists($this, $actionMethodName)) {
                    $this->$actionMethodName();
                } else {
                    echo "Action $action not found!\n";
                    echo $this->usageHelp();
                    exit(1);
                }
	               
            }
		}
		
		catch (Exception $e) {
			 
		 }
			
	}
	
	
	
	public function usageHelp()
    {
        $help = 'Available actions: ' . "\n";
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (substr($method, -6) == 'Action') {
                $help .= '    --action ' . substr($method, 0, -6);
                $helpMethod = $method . 'Help';
                if (method_exists($this, $helpMethod)) {
                    $help .= ' ' . $this->$helpMethod();
                }
                $help .= "\n";
            }
        }
        return $help;
    }
        
    public function findSettingsAction()
    {    
	   $data = JH_RemoveRedundantConfig_Model_Observer::findSettings();	
       var_dump($data);	   
    }
    
    
     public function findSettingsActionHelp()
    {
	    return "Find all obsolete settings in table core_config_data";
	    
    }
    
     public function removeSettingsAction()
    {	    
	   JH_RemoveRedundantConfig_Model_Observer::removeSettings();
    }
    
    
     public function removeSettingsActionHelp()
    {
	    return "Remove all obsolete settings in table core_config_data";
	    
    }
	
}


$shell = new JH_RemoveRedundantConfig_Shell_Scheduler();
$shell->run();