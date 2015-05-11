<?php

class JH_RemoveRedundantConfig_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case_Config
{
	 /**
     * @test
     */
     
	public function removeSettingsCron()
	{
		$this->assertModuleCodePool('community');	
	}
	
	 /**
     * @test
     */
	public function removeSettings()
	{
		$this->assertModuleCodePool('community');	
	}
	
	 /**
     * @test
     */
	public function findSettings()
	{
		
		$this->assertNotNull($obsolete_ids);	
	}
	
	
	
	
	
}