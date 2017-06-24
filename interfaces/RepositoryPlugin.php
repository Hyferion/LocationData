<?php

namespace SRAG\Tutorial;

/**
 * LocationData repository object plugin
 *
 * This class inherits from the general ilRepositoryObjectPlugin-class und provides information
 * about the plugin.
 *
 * To show which methods can be used I itroduces an interface
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version 1.0.00
 *
 */
interface RepositoryPlugin {

	/**
	 * @return string Name of the plugin as often storen in self::PLUGIN_NAME
	 */
	public function getPluginName();


	/**
	 * Should be implemented to provide a complete uninstallation of the plugin. Remove all tables
	 * and other plugin-related data
	 */
	public function uninstallCustom();


	/**
	 * @return    string    Identifier of the plugin, MUST start with a "x" for repository object
	 *                      plugins
	 */
	public function getId();


	/**
	 * @return    string    Current Version (from plugin.php file)
	 */
	public function getVersion();


	/**
	 * @return    boolean    true if the plugin is active
	 */
	public function getActive();


	/**
	 * @return    int    latest installed Updatestep of the sql/dbupdate.php
	 */
	public function getDBVersion();


	/**
	 * Get Plugin Directory
	 *
	 * @return    object    Plugin Slot
	 */
	public function getDirectory();


	/**
	 * Get Plugin's classes Directory
	 *
	 * @return    object    classes directory
	 */
	// public function getClassesDirectory();


	/**
	 * Get plugin prefix, used for lang vars
	 */
	function getPrefix();


	/**
	 * Get db table plugin prefix
	 */
	function getTablePrefix();


	/**
	 * Get Language Variable (prefix will be prepended automatically)
	 */
	public function txt($a_var);


	/**
	 * Get template from plugin
	 */
	public function getTemplate($a_template, $a_par1 = true, $a_par2 = true);


	/**
	 * Get css file location
	 */
	public function getStyleSheetLocation($a_css_file);


	/**
	 * Check whether plugin is active
	 */
	public function isActive();


	/**
	 * After activation processing
	 */
	// protected function afterActivation();


	/**
	 * After deactivation processing
	 */
	// protected  function afterDeactivation();


    // protected  function afterUninstall();


	/**
	 * Before update processing
	 */
	// protected function beforeUpdate();


	/**
	 * After update processing
	 */
	// public function afterUpdate();


	/**
	 * Get Slot Name.
	 *
	 * @return        string        Slot Name
	 */
	function getSlot();


	/**
	 * Get Slot ID.
	 *
	 * @return        string        Slot Id
	 */
	function getSlotId();


	/**
	 * Before activation processing
	 */
	// public function beforeActivation();


	// public function beforeUninstall();


	/**
	 * decides if this repository plugin can be copied
	 *
	 * @return bool
	 */
	public function allowCopy();
}