<?php
class JH_RemoveRedundantConfig_Model_Observer {
    
	public function removeSettingsCron() {

		if (Mage::getStoreConfig('dev/jh_removeredundantconfig/status') == true) 
		{	
			$this->removeSettings();
		}
	}
	
	
	
	public function removeSettings() {
	
		// Delete obsolete settings in Table core_config_data
		$resource = Mage::getSingleton('core/resource');
		$writeConnection = $resource->getConnection('core_write');
		$tablename = $resource->getTableName('core/config_data');
		
		$obsolete_ids = JH_RemoveRedundantConfig_Model_Observer::findSettings();
		$obsolete_ids_list = implode(', ', $obsolete_ids);
		
		if (!empty($obsolete_ids)) {
		$query = "DELETE FROM  " . $tablename. " where config_id IN (". $obsolete_ids_list .") ";
		$writeConnection->query($query);		
		Mage::log($obsolete_ids_list, null, 'removed_obsolete_config_ids.log');
		}		
	}

	
	public function findSettings()
    {    
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$tablename = $resource->getTableName('core/config_data');
		
		$query = 'SELECT * FROM ' . $resource->getTableName('core/config_data'). ' group by path,value having count(*) >= 2 order by path,value' ;	
		$results = $readConnection->fetchAll($query);
		
		$obsolete_ids = array();
		foreach ($results as $entry){
			
				// Check if path with same value exists in other scopes		
				
				if ($entry['scope'] == 'default'){					
					//	Default and Website Scope													
					$results_check = $readConnection->fetchAll("SELECT config_id, scope FROM " . $tablename. " where scope =  'websites' and path =  '".$entry['path']. "'  and value = '". $entry['value'] ."' ");		
					foreach ($results_check as $entry_check){
						echo $entry_check['scope'] ." - " .$entry['path']. " - ". $entry['value'] ."\n";
						$obsolete_ids[] = $entry_check['config_id'];
					}
								
										
					//	Default and Store Scope											
					$results_check = $readConnection->fetchAll("SELECT config_id,scope FROM " . $tablename. " where scope = 'stores' and path =  '".$entry['path']. "' and value = '". $entry['value'] ."' ");					
					foreach ($results_check as $entry_check){
						$count = "SELECT COUNT(*) as count FROM " . $tablename. " where scope = 'websites' and path =  '".$entry['path']. "' and value != '". $entry['value'] ."'  ";
						if ($readConnection->fetchOne($count) == false) {
						echo $entry_check['scope'] ." - " .$entry['path']. " - ". $entry['value'] ."\n";
						$obsolete_ids[] = $entry_check['config_id'];
						}
					}						
				}
				
				
				if ($entry['scope'] == 'websites'){					
					//	Website and Store Scope											
					$results_check = $readConnection->fetchAll("SELECT config_id,scope FROM " . $tablename. " where scope = 'stores' and path =  '".$entry['path']. "' and value = '". $entry['value'] ."' ");					
					foreach ($results_check as $entry_check){
						echo $entry_check['scope'] ." - " .$entry['path']. " - ". $entry['value'] ."\n";
						$obsolete_ids[] = $entry_check['config_id'];
					}	
				}
				
				if ($entry['scope'] == 'stores'){					
					//	Website and Store Scope											
					$results_check = $readConnection->fetchAll("SELECT config_id,scope FROM " . $tablename. " where scope = 'websites' and path =  '".$entry['path']. "' and value = '". $entry['value'] ."' ");					
					foreach ($results_check as $entry_check){
						echo $entry_check['scope'] ." - " .$entry['path']. " - ". $entry['value'] ."\n";
						$obsolete_ids[] = $entry_check['config_id'];
					}	
				}					
		}
		
	//$settings = Mage::getModel('core/config_data')->getCollection();		
	return $obsolete_ids;

    }
}