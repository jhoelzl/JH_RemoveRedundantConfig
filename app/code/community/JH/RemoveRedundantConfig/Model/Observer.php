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
		
		$settings = Mage::getSingleton('core/config_data')->getCollection();
		$settings->getSelect()->group(array('path','value'));
		$settings->getSelect()->having('count(*) >= 2');
		$settings->getSelect()->order('path','value');
		
		$obsolete_ids = array();
		
		foreach($settings as $p) {
			
				// Check if path with same value exists in other scopes		
				
				if ($p->getScope() == 'default'){	
				
					//	Default and Website Scope			
						$settings_check = Mage::getSingleton('core/config_data')->getCollection()
						->addFieldToFilter('scope', 'websites')
						->addFieldToFilter('path', $p->getPath())
						->addFieldToFilter('value', $p->getValue());
						$settings_check->getSelect();
						
						foreach($settings_check as $s) {
							echo $s->getScope() ." - " .$p->getPath(). " - ". $p->getValue() ."\n";
							$obsolete_ids[] = $s->getId();						
						}
							
					//	Default and Store Scope	
						$settings_check = Mage::getSingleton('core/config_data')->getCollection()
						->addFieldToFilter('scope', 'stores')
						->addFieldToFilter('path', $p->getPath())
						->addFieldToFilter('value', $p->getValue());
						$settings_check->getSelect();
						
						foreach($settings_check as $s) {
							
							// Check if website scopes exists with another value
							$settings_check2 = Mage::getSingleton('core/config_data')->getCollection()
							->addFieldToFilter('scope', 'websites')
							->addFieldToFilter('path', $p->getPath())
							->addFieldToFilter('value', array('neq' => $p->getValue()) );
							
							if ($settings_check2->count() == 0) {								
								echo $s->getScope() ." - " .$p->getPath(). " - ". $p->getValue() ."\n";
								$obsolete_ids[] = $s->getId();
							}							
						}									
				}
				
				
				if ($p->getScope() == 'websites'){				
				
					//	Website and Store Scope		
					$settings_check = Mage::getSingleton('core/config_data')->getCollection()
						->addFieldToFilter('scope', 'stores')
						->addFieldToFilter('path', $p->getPath())
						->addFieldToFilter('value', $p->getValue());
						$settings_check->getSelect();
						
						foreach($settings_check as $s) {
							echo $s->getScope() ." - " .$p->getPath(). " - ". $p->getValue() ."\n";
							$obsolete_ids[] = $s->getId();						
						}
				}
				
				if ($p->getScope() == 'stores'){
					
					//	Website and Store Scope		
					$settings_check = Mage::getSingleton('core/config_data')->getCollection()
						->addFieldToFilter('scope', 'websites')
						->addFieldToFilter('path', $p->getPath())
						->addFieldToFilter('value', $p->getValue());
						$settings_check->getSelect();
						
						foreach($settings_check as $s) {
							echo $s->getScope() ." - " .$p->getPath(). " - ". $p->getValue() ."\n";
							$obsolete_ids[] = $s->getId();						
						}
				}					
		}
		
	
	return $obsolete_ids;

    }
}