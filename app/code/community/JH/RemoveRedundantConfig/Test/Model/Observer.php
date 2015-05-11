<?php

class JH_RemoveRedundantConfig_Test_Model_Observer extends EcomDev_PHPUnit_Test_Case_Config
{
	 /**
     * @test
     */
     
	public function testCodePool()
	{
		$this->assertModuleCodePool('community');	
	}
	
	 /**
     * @test
     */
	public function testfindSettings()
	{	
		$this->assertNotNull($this->Mage::getModel('jh_removeredundantconfig/observer')->findSettings());	
	}
}