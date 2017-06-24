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
require_once('./Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/class.ilLocationDataPlugin.php');
include_once('./Services/Repository/classes/class.ilObjectPluginListGUI.php');

/**
 * ListGUI implementation for LocationData object plugin. This one
 * handles the presentation in container items (categories, courses, ...)
 * together with the corresponding ...Access class.
 *
 * PLEASE do not create instances of larger classes here. Use the
 * ...Access class to get DB data and keep it small.
 *
 * @author        Fabian Schmid <fs@studer-raimann.ch>
 * @author        Gabriel Comte <gc@studer-raimann.ch>
 *
 *
 * @version       1.0.00
 */
class ilObjLocationDataListGUI extends ilObjectPluginListGUI
{

    /**
     * @var ilLocationDataPlugin
     */
    public $plugin;


    public function initType()
    {
        $this->setType(ilLocationDataPlugin::PLUGIN_PREFIX);
    }


    /**
     * @return string
     */
    public function getGuiClass()
    {
        return ilObjLocationDataGUI::class;
    }


    /**
     * @return array
     */
    public function getCommands()
    {
        return parent::getCommands();
    }


    /**
     * @return array
     */
    public function initCommands()
    {
        // Always set
        $this->timings_enabled = false;
        $this->subscribe_enabled = false;
        $this->payment_enabled = false;
        $this->link_enabled = false;
        $this->info_screen_enabled = true;
        $this->delete_enabled = true;

        // Should be overwritten according to status
        $this->cut_enabled = false;
        $this->copy_enabled = false;

        $commands = array(
            array(
                'permission' => 'read',
                'cmd' => 'showContent',
                'default' => true,
            ),
        );

        return $commands;
    }

}
