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
require_once('./Services/Repository/classes/class.ilObjectPluginGUI.php');
require_once('class.ilLocationDataPlugin.php');
require_once('./Services/Link/classes/class.ilLink.php');
require_once('./Services/InfoScreen/classes/class.ilInfoScreenGUI.php');
require_once('class.ilObjLocationData.php');
require_once('./Services/InfoScreen/classes/class.ilInfoScreenGUI.php');
require_once('./Services/Repository/classes/class.ilRepUtilGUI.php');
require_once('class.ilObjLocationDataAccess.php');
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/Location/class.xlcdLocation.php';
include_once './Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/Location/class.ilMyTableGUI.php';
require_once("./Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/Location/class.xlcdLocationGUI.php");
include_once('Services/AccessControl/classes/class.ilPermissionGUI.php');

/**
 * User Interface class for example repository object.
 *
 * @author            Fabian Schmid <fs@studer-raimann.ch>
 *
 * @version           1.0.00
 *
 * Integration into control structure:
 * - The GUI class is called by ilRepositoryGUI
 * - GUI classes used by this class are ilPermissionGUI (provides the rbac
 *   screens) and ilInfoScreenGUI (handles the info screen).
 *
 * The most complicated thing is the control-flow.
 *
 *
 * @ilCtrl_isCalledBy ilObjLocationDataGUI: ilRepositoryGUI,
 * @ilCtrl_isCalledBy ilObjLocationDataGUI: ilObjPluginDispatchGUI
 * @ilCtrl_isCalledBy ilObjLocationDataGUI: ilAdministrationGUI
 * @ilCtrl_Calls      ilObjLocationDataGUI: ilPermissionGUI, ilInfoScreenGUI
 * @ilCtrl_Calls      ilObjLocationDataGUI: ilObjectCopyGUI
 * @ilCtrl_Calls      ilObjLocationDataGUI: ilCommonActionDispatcherGUI
 * @ilCtrl_Calls      ilObjLocationDataGUI: xlcdLocationGUI
 * @ilCtrl_Calls      ilObjLocationDataGUI: ilMytableGUI
 *
 */
//This class sets the Tabs on the top and redirects the controlflow
class ilObjLocationDataGUI extends ilObjectPluginGUI
{
    const CMD_EDIT = 'edit';
    const CMD_DEFAULT = 'showContent';
    const CMD_STANDARD = 'index';
    /**
     * @var ilObjLocationData
     */
    public $object;
    /**
     * @var ilLocationDataPlugin
     */
    protected $pl;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilPropertyFormGUI
     */
    protected $form;
    /**
     * @var ilNavigationHistory
     */
    protected $history;
    /**
     * @var ilTabsGUI
     */
    public $tabs;
    /**
     * @var ilAccessHandler
     */
    protected $access;


    protected function afterConstructor()
    {
        global $tpl, $ilCtrl, $ilAccess, $ilNavigationHistory, $ilTabs;
        /**
         * @var $tpl                 ilTemplate
         * @var $ilCtrl              ilCtrl
         * @var $ilAccess            ilAccessHandler
         * @var $ilNavigationHistory ilNavigationHistory
         */
        $this->tpl = $tpl;
        $this->history = $ilNavigationHistory;
        $this->access = $ilAccess;
        $this->ctrl = $ilCtrl;
        $this->tabs = $ilTabs;
        $this->pl = ilLocationDataPlugin::getInstance();
    }


    /**
     * @return string
     */
    final function getType()
    {
        return ilLocationDataPlugin::PLUGIN_PREFIX;
    }

    //directs the controlflow, default to the parent class the LocationGUI.
    public function executeCommand()
    {
        $next_class = $this->ctrl->getNextClass($this);

        switch ($next_class) {
            case 'xlcdlocationgui':
                $this->tabs->setTabActive(xlcdLocationGUI::CMD_STANDARD);
                $xlcdLocationGUI = new xlcdLocationGUI();
                $this->ctrl->forwardCommand($xlcdLocationGUI);
                break;
            default:
                parent::executeCommand();
        }
    }

    //creates the Tabs under the plugin name for options
    protected function setTabs()
    {
        global $lng;
        $this->tabs->addTab(self::CMD_DEFAULT, $this->pl->txt('tab_show_content'), $this->ctrl->getLinkTarget($this, self::CMD_DEFAULT));
        $this->addInfoTab();
        //$this->tabs->addTab(self::CMD_EDIT, $this->pl->txt('perm_settings'), $this->ctrl->getLinkTargetByClass(ilPermissionGUI::class));
        $this->addPermissionTab();


        return true;

    }



    /**
     * @param $cmd
     */

    public function performCommand($cmd)
    {
        switch ($cmd) {
            case self::CMD_DEFAULT:
            case 'showLocation':
            case 'applyFilter':
            case 'resetFilter':
                if (ilObjLocationDataAccess::hasReadAccess()) {
                    $this->{$cmd}();
                }
                return;

        }
        throw new Exception("Not implemented.");
    }

    /*
    public function performCommand(){
        $cmd = $this->ctrl->getCmd(self::CMD_STANDARD);
        switch ($cmd) {
            case self::CMD_STANDARD;
                $this->ctrl->redirect(new xlcdLocationGUI(), xlcdLocationGUI::CMD_STANDARD);
                break;
                case self::CMD_EDIT;
                $this->{$cmd}();
        }
    }*/
    //applies filter and reloads the table
    protected function applyFilter()
    {
        $table_gui = new ilMyTableGUI($this, "showLocation");
        $table_gui->writeFilterToSession();
        $table_gui->resetOffset();
        $this->showContent();
    }

    //loads the template and shows the tabs
    protected function showContent()
    {
        global $ilCtrl;

        global $tpl;

        $table_gui = new ilMyTableGUI($this, "showLocation");

        $tpl->setContent($table_gui->getHtml());

    }

    //hardcoded a location to add to the db. Is not used anymore.
    public function showLocation()
    {
    /*
        xlcdLocation::installDB();
        $xlcdLocation = new xlcdLocation();
        $xlcdLocation->setDescription('first location');
        $xlcdLocation->setLatitude(23.23);
        //$xlcdLocation->setId(59);
        $xlcdLocation->setLongitude(22);
        $xlcdLocation->setCreationDate(time());
        $xlcdLocation->setTitle("Location");
        //$xlcdLocation->create();
*/
        global $tpl;

        $table_gui = new ilMyTableGUI($this, "showLocation");

        $tpl->setContent($table_gui->getHtml());

        //echo print_r($xlcdLocation);
        //exit;

    }

    public function edit()
    {
        $this->tabs->activateTab(self::CMD_EDIT);
        $this->initPropertiesForm();
        $this->fillPropertiesForm();
        $this->tpl->setContent($this->form->getHTML());
    }


    /**
     * @return string
     */
    public function getAfterCreationCmd()
    {
        return self::CMD_DEFAULT;
    }


    /**
     * @return string
     */
    public function getStandardCmd()
    {
        return self::CMD_DEFAULT;
    }


    /**
     * @param \ilObjLocationData $newObj
     */
    public function afterSave(ilObject $newObj)
    {
        assert(is_a($newObj, ilObjLocationData::class));
        parent::afterSave($newObj);
    }


    /*
        public function showData()
        {
            global $tpl;

            $table_gui = new ilMyTableGUI($this, "showData");

            $tpl->setContent($table_gui->getHtml());

        }*/

    //properties gui
    public function initPropertiesForm()
    {
        //$this->form = new ilPropertyFormGUI();
        // $this->form->setTitle($this->pl->txt(('edit_properties')));

        $ti = new ilTextInputGUI($this->pl->txt('title'), 'title');
        $ti->setRequired(true);
        $this->form->addItem($ti);
        $ta = new ilTextAreaInputGUI($this->pl->txt('description'), 'desc');
        $this->form->addItem($ta);
        //$cb = new ilCheckboxInputGUI($this->pl->txt('online'),'online');
        //$this->form->addItem($cb);

        $this->form->addCommandButton('updateProperties', $this->pl->txt('save'));
        $this->form->setFormAction($this->ctrl->getFormAction($this));
    }

    public function fillPropertiesForm()
    {
        $values['title'] = $this->object->getTitle();
        $values['desc'] = $this->object->getDescription();
        //$values['online'] = $this->object->getOnline();

        $this->form->setValuesByArray($values);
    }

    /*public function createToolbar() {
        $this->tool = new ilToolbarGUI();
        $this->tool->addButtonInstance("Content");
        $this->tool->addButtonInstance("Settings");
        $this->tool->addButtonInstance("Permissions");
    }*/

}