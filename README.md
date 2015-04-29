# JH_RemoveRedundantConfig
Magento Module that finds redundant entries in mysql table core_config_data.


If you have different config settings for default, website or store scope in Magento backend, maybe after some time you will end up in having obsolete and redundant settings for website and store scope that have the same value as the default scope. This module finds the duplicate entries and adds the ability to remove them.

This module could be useful to improve data quality in table core_config_data when you are monitoring your Magento instances (test, dev, production) through an external tool.

## Installation
* Install the extension via GitHub, composer, modman or a similar method.
* Clear the cache, logout from the admin panel and then login again.
* Enable/Disable automatic deletion of obsolete settings under System > Configuration > Developer > JH_RemoveRedundantConfig Settings.

## Usage
* First, test which entries are obsolete with the php script under shell directory in your magento directory:
```
 cd shell 
 php redundant_settings.php --action findSettings
```
The command lists all paths and config_ids which are redundant. If the output is correct, you can enable the cronjob (runs once per hour) under System -> Configuration > Developer > JH_RemoveRedundantConfig Settings or run the command:
```
 cd shell 
 php redundant_settings.php --action removeSettings
```
In the log file var/log/removed_obsolete_config_ids.log, you can find all ids that are removed by the module.

## Improvements 
Feel free to make a pull request when you have ideas for improving.

## Uninstallation
 
 * Fia FTP: Remove all extension files from your Magento installation
 * Via modman: `modman remove JH_RemoveRedundantConfig`
 * Via composer, remove the line of your composer.json related to `jh_removeredundantconfig`
 * Optional: Remove entry with path "dev/jh_removeredundantconfig/status" from table core_config_data


