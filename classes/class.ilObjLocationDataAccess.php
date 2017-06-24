<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2001 ILIAS open source, University of Cologne            |
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

require_once('./Services/Repository/classes/class.ilObjectPluginAccess.php');
require_once('class.ilObjLocationData.php');

/**
 * Access/Condition checking for LocationData object
 *
 * @author        Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version       1.0.00
 */
class ilObjLocationDataAccess extends ilObjectPluginAccess
{

    const TXT_PERMISSION_DENIED = 'permission_denied';
    /**
     * @var array
     */
    protected static $cache = array();


    /**
     * @param string $a_cmd
     * @param string $a_permission
     * @param int $a_ref_id
     * @param int $a_obj_id
     * @param string $a_user_id
     *
     * @return bool
     */
    public function _checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id = null, $a_user_id = '')
    {
        global $ilUser, $ilAccess;
        /**
         * @var $ilAccess ilAccessHandler
         */
        if ($a_user_id == '') {
            $a_user_id = $ilUser->getId();
        }
        if ($a_obj_id === null) {
            $a_obj_id = ilObject2::_lookupObjId($a_ref_id);
        }

        switch ($a_permission) {
            case 'read':
                return ilObjLocationDataAccess::checkOnline($a_obj_id)
                    && $ilAccess->checkAccessOfUser($a_user_id, 'read', '', $a_ref_id)
                    || $ilAccess->checkAccessOfUser($a_user_id, 'write', '', $a_ref_id);

            default:
                return $ilAccess->checkAccessOfUser($a_user_id, $a_permission, '', $a_ref_id);;
        }
    }


    /**
     * @param        $a_cmd
     * @param        $a_permission
     * @param        $a_ref_id
     * @param null $a_obj_id
     * @param string $a_user_id
     *
     * @return bool
     */
    protected static function checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id = null, $a_user_id = '')
    {
        $n = new self();

        return $n->_checkAccess($a_cmd, $a_permission, $a_ref_id, $a_obj_id, $a_user_id);
    }


    protected static function redirectNonAccess()
    {
        global $ilCtrl;
        /**
         * @var $ilCtrl ilCtrl
         */
        ilUtil::sendFailure(ilLocationDataPlugin::getInstance()->txt(self::TXT_PERMISSION_DENIED), true);
        $ilCtrl->redirectByClass('ilRepositoryGUI');
    }


    /**
     * @param $obj_id
     *
     * @return bool
     */
    static function checkOnline($obj_id)
    {
        // TODO: Check if yout object with the obj_id in online. This status has to be stored on your own per object

        return true;
    }


    /**
     * @param $ref_id
     *
     * @return bool
     */
    public static function hasWriteAccess($ref_id = null)
    {
        if ($ref_id === null) {
            $ref_id = $_GET['ref_id'];
        }

        return self::checkAccess('write', 'write', $ref_id);
    }


    /**
     * @param $ref_id
     *
     * @return bool
     */
    public static function hasReadAccess($ref_id = null)
    {
        if ($ref_id === null) {
            $ref_id = $_GET['ref_id'];
        }

        return self::checkAccess('write', 'write', $ref_id);
    }
}
