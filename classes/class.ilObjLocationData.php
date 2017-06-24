<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2009 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

require_once('./Services/Repository/classes/class.ilObjectPlugin.php');
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/interfaces/PluginObject.php');

/**
 * Class ilObjLocationData
 *
 * This class inherits from the general Reposity-Object-Plugin and represents
 * one instance of your Plugin in the repository (after you created one
 * LocationData in the ILIAS-Repository using "Neues Objekt anlegen".
 *
 * It's constructed using the ref_id (which you see in the browser address)
 *
 * @see     ilRepositoryPluginInterface for further information. The interface
 *          ilRepositoryPluginInterface is only in this Demo-Repository to
 *          provide infos on the methods you can use in this class
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version 1.0.00
 */
class ilObjLocationData extends ilObjectPlugin implements \SRAG\Tutorial\PluginObject
{

    /**
     * @var \ilDBInterface
     */
    protected $db;


    /**
     * @param int $ref_id
     */
    public function __construct($ref_id = 0)
    {
        /**
         * @var $ilDB \ilDBInterface
         */
        global $ilDB;

        parent::__construct($ref_id);
        $this->db = $ilDB;
    }


    /**
     * @inheritdoc
     */
    final public function initType()
    {
        $this->setType(ilLocationDataPlugin::PLUGIN_PREFIX);
    }


    /**
     * @inheritdoc
     */
    public function doCreate()
    {
    }


    /**
     * @inheritdoc
     */
    public function doRead()
    {
    }


    /**
     * @inheritdoc
     */
    public function doUpdate()
    {
    }


    /**
     * @inheritdoc
     */
    public function doDelete()
    {
    }


    /**
     * @param ilObjLocationData $new_obj Instance of
     * @param int $a_target_id obj_id of the new created object
     * @param int $a_copy_id
     *
     * @return bool|void
     */
    public function doCloneObject($new_obj, $a_target_id, $a_copy_id = null)
    {
        assert(is_a($new_obj, ilObjLocationData::class));
    }
}



