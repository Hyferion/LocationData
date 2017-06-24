<?php

namespace SRAG\Tutorial;

/**
 * Interface PluginObject
 *
 * Description of the Methods you can use in this tutorial
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version 1.0.00
 */
interface PluginObject {

	/**
	 * In this Method your class has to set the type (prefix) of your plugin,
	 * e.g. $this->setType(ilLocationDataPlugin::PLUGIN_PREFIX);
	 */
	public function initType();


	/**
	 * after ILIAS has created your instance of the plugin in the repository
	 * and stores data such as ref_id, obj_id, owner, creation_date, ... your
	 * doCreate() is called. Store your own data if needed. Create an own class
	 * to hold the specific data (as a hint, use ActiveRecord for this purpose
	 * which saves a lot of time).
	 *
	 * Use the obj_id $this->>getId() to map the instance with your data
	 */
	public function doCreate();


	/**
	 * after ILIAS has read all data it knows such as ref_id, obj_id, owner,
	 * creation_date, ..., your doRead() is called. read the additional data
	 * and provide it to this class (use the ActiveRecord you used to store the
	 * spefific data)
	 */
	public function doRead();


	/**
	 * same as in doCreate() but this is called after updating
	 */
	public function doUpdate();


	/**
	 * remove the specific data of the instance (in database or delete files of
	 * needed). Attention: only delete the data associated to $this->>getId(); !
	 */
	public function doDelete();


	/**
	 * Whenever a Instance of your plugin is clones using the GUI (Actions-Menu
	 * -> Copy), after ILIAS has clones the data it knows, your doCloneObject()
	 * is called. The new (cloned) instance is passed using $new_obj. you have
	 * to clone your specific data
	 *
	 *
	 * @param ilObjLocationData $new_obj     Instance of
	 * @param int               $a_target_id obj_id of the new created object
	 * @param int               $a_copy_id
	 *
	 * @return bool|void
	 */
	public function doCloneObject($new_obj, $a_target_id, $a_copy_id = null);


	/**
	 * @return int Returns the obj_id of your instance
	 */
	public function getId();


	/**
	 * @return int Returns the ref_id of your instance
	 */
	public function getRefId();


	/**
	 * @return string Shortened Title with ...
	 */
	public function getPresentationTitle();


	/**
	 * @return string
	 */
	public function getTitle();


	/**
	 * @param string $a_title
	 */
	public function setTitle($a_title);


	/**
	 * @param string $a_desc
	 */
	public function setDescription($a_desc);


	/**
	 * @return int user_id of the user which created the instance
	 */
	public function getOwner();


	/**
	 * @param $owner_user_id
	 */
	public function setOwner($owner_user_id);


	/**
	 * Wrapper for txt function. This adds the slot-prefix and the
	 * plugin-prefix in front of the passed $variable.
	 * E.g.: $this->>txt("tab_show_content"); search the language database for tab_show_content
	 *
	 * @var string $variable
	 */
	//final protected function txt($variable);
}