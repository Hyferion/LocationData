<?php

require_once('./Services/Repository/classes/class.ilRepositoryObjectPlugin.php');
require_once('class.ilObjLocationDataAccess.php');
require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/interfaces/RepositoryPlugin.php");

/**
 * LocationData repository object plugin
 *
 * This class inherits from the general ilRepositoryObjectPlugin-class und provides information
 * about the plugin.
 *
 * To show which methods can be used I introduces an interface
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version 1.0.00
 *
 */
class ilLocationDataPlugin extends ilRepositoryObjectPlugin implements \SRAG\Tutorial\RepositoryPlugin
{

    const PLUGIN_PREFIX = 'xlcd';
    const PLUGIN_NAME = 'LocationData';
    /**
     * @var ilLocationDataPlugin
     */
    protected static $cache;


    /**
     * @return ilLocationDataPlugin
     */
    public static function getInstance()
    {
        if (!isset(self::$cache)) {
            self::$cache = new self();
        }

        return self::$cache;
    }


    /**
     * @return string
     */
    function getPluginName()
    {
        return self::PLUGIN_NAME;
    }


    public function uninstallCustom()
    {
        // TODO: Implement uninstallCustom() method. Remove all Data-tables and created user-files
    }
}

