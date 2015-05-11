<?php

class JH_RemoveRedundantConfig_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case_Config
{
	
	public function removeSettingsCron()
	{
		$this->assertModuleCodePool('community');	
	}
	
	public function removeSettings()
	{
		$this->assertModuleCodePool('community');	
	}
	
	
	public function findSettings()
	{
		
		$this->assertNotNull($obsolete_ids);	
	}
	
	
	
	
	
}