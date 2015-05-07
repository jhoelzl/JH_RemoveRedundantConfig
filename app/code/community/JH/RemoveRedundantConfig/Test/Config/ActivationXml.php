<?php

class JH_RemoveRedundantConfig_Test_Config_ActivationXml extends EcomDev_PHPUnit_Test_Case_Config
{
	
	public function codePool()
	{
		$this->assertModuleCodePool('local');
	}
	
}