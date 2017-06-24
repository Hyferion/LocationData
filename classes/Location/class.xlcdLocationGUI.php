<?php

/**
 * Created by PhpStorm.
 * User: sst
 * Date: 09.06.17
 * Time: 08:52
 */
require_once('./Services/UIComponent/Button/classes/class.ilLinkButton.php');
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/class.ilLocationDataPlugin.php';
require_once 'class.xlcdLocation.php';
require_once 'class.ilMyTableGUI.php';
require_once 'class.xlcdLocationFormGUI.php';
require_once './Services/Utilities/classes/class.ilConfirmationGUI.php';
require_once './Services/Form/classes/class.ilPropertyFormGUI.php';
require_once './Services/Form/classes/class.ilNonEditableValueGUI.php';
require_once './Services/Maps/classes/class.ilMapUtil.php';


class xlcdLocationGUI
    /*
     * Shows GUI when you view the Location.
     * The different commands control the control flow. They each call methods.
     */
{

    const CMD_STANDARD = 'content';
    const CMD_ADD = 'add';
    const IDENTIFIER = 'xlcdLoc';
    const CMD_VIEW = 'view';
    const CMD_EDIT = 'edit';
    const CMD_DELETE = 'delete';
    const CMD_UPDATE = 'update';
    const CMD_CREATE = 'create';
    const CMD_CANCEL = 'cancel';
    const CMD_CONFIRM = 'confirmDelete';


    /**
     * @var ilTabsGUI
     */
    protected $tabs;
    /**
     * @var ilLocationDataPlugin
     */
    protected $pl;
    /**
     * @var ilObjLocationDataAccess
     */
    protected $access;

    /**
     * @var ilToolbarGUI
     */
    protected $toolbar;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilTemplate
     */
    protected $tpl;
    /**
     * @var integer
     */
    protected $location_id;

    public function __construct()
    {
        global $tpl, $ilCtrl, $ilTabs, $ilToolbar, $access;


        /**
         * @var $ilCtrl ilCtrl
         * @var $ilTabs ilTabsGUI
         * @var $tpl ilTemplate
         * @var $ilToolbar ilToolbarGUI
         * @var $ilObjLocationDataAccess ilObjLocationDataAccess
         * @var $ilUser ilUser
         *
         */

        $this->tpl = $tpl;
        $this->ctrl = $ilCtrl;
        $this->tabs = $ilTabs;
        $this->access = new ilObjLocationDataAccess();
        $this->toolbar = $ilToolbar;
        $this->user = $ilUser;
        $this->pl = new ilLocationDataPlugin();


    }

    public function executeCommand()
    {
        $this->tpl->getStandardTemplate();
        $nextClass = $this->ctrl->getNextClass();
        switch ($nextClass) {
            default:
                $cmd = $this->ctrl->getCmd(self::CMD_VIEW);
                $this->tabs->setTabActive(self::CMD_STANDARD);
                $this->{$cmd}();
                break;
        }
        $this->tpl->show();
    }

    public function content()
    {

        $ilMyTableGUI = new ilMyTableGUI($this, self::CMD_STANDARD);
        $this->tpl->setContent($ilMyTableGUI->getHTML());
    }


    public function view()
    {
        /**
         * @var $xlcdLocation xlcdLocation
         */
        global $access;
        $access = new ilObjLocationDataAccess();
        /*
         * The identifier links to the right location
         */
        $xlcdLocation = xlcdLocation::find($_GET[self::IDENTIFIER]);

        //Links to the edit gui
        if ($access->hasWriteAccess()) {
            $b_edit = ilLinkButton::getInstance();
            $b_edit->setCaption($this->pl->txt('edit'));
            $this->ctrl->saveParameter($this, self::IDENTIFIER);
            $b_edit->setUrl($this->ctrl->getLinkTarget($this, self::CMD_EDIT));
            $this->toolbar->addButtonInstance($b_edit);
        }
        //The delete button redirects to the Confirm Question.
        if ($access->hasWriteAccess()) {
            $b_delete = ilLinkButton::getInstance();
            $b_delete->setCaption($this->pl->txt('delete'));
            $b_delete->setUrl($this->ctrl->getLinkTarget($this, self::CMD_CONFIRM));
            $this->toolbar->addButtonInstance($b_delete);
        }
        //Building the GUI
        $prop_form = new ilPropertyFormGUI();
        $prop_form->setTitle($this->pl->txt('view_info'));

        $title = new ilNonEditableValueGUI();
        $title->setTitle($this->pl->txt('view_title'));
        $title->setValue($xlcdLocation->getTitle());
        $prop_form->addItem($title);

        $description = new ilNonEditableValueGUI();
        $description->setTitle($this->pl->txt('view_description'));
        $description->setValue($xlcdLocation->getDescription());
        $prop_form->addItem($description);

        $lat = new ilNonEditableValueGUI();
        $lat->setTitle($this->pl->txt('view_latitude'));
        $lat->setValue($xlcdLocation->getLatitude());
        $prop_form->addItem($lat);

        $long = new ilNonEditableValueGUI();
        $long->setTitle($this->pl->txt('view_longitude'));
        $long->setValue($xlcdLocation->getLatitude());
        $prop_form->addItem($long);

        //sets the map
        $map = ilMapUtil::getMapGUI();
        $map->setMapId('map_1');
        $map->setZoom(12);
        $map->setHeight('500px');
        $map->setWidth('500px');
        $map->setLongitude($xlcdLocation->getLongitude());
        $map->setLatitude($xlcdLocation->getLatitude());
        $map->setEnableCentralMarker(true);
        $map->setEnableNavigationControl(true);
        $map->setEnableLargeMapControl(true);
        $map->setEnableTypeControl(true);

        // $prop_form->addCommandButton()
        //gets the template and initializes it
        $my_tpl = new ilTemplate('Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/templates/tpl.LocationView.html', false, false);
        $my_tpl->setVariable('MAP', $map->getHtml());
        $my_tpl->setVariable('INFO', $prop_form->getHTML());
        $html = $my_tpl->get();
        $this->tpl->setContent($html);
        //$this->tpl->setContent($prop_form->getHTML());


    }

    public function add()
    {
        //opens the form GUI to add a new location
        if ($this->access->hasWriteAccess()) {
            $this->ctrl->saveParameter($this, self::IDENTIFIER);
            $xlcdLocationFormGUI = new xlcdLocationFormGUI($this, new xlcdLocation());
            $this->tpl->setContent($xlcdLocationFormGUI->getHTML());
        }
    }

    public function edit()
    {
        //Also opens the FormGUI to edit the current location, finds the right location with the identifier. CANT GET THE RIGHT LOCATION FROM THE DB
        //It cannot retrieve an id for the location so it wants to create a new one. (is_new) {SOLVED}
        if ($this->access->hasWriteAccess()) {
            $xlcdLocationFormGUI = new xlcdLocationFormGUI($this, xlcdLocation::find($_GET[self::IDENTIFIER]));
            $xlcdLocationFormGUI->fillForm();
            $this->tpl->setContent($xlcdLocationFormGUI->getHTML());
        } else {
            $this->ctrl->redirect(ilMyTableGUI::class);
        }
    }

    public function update()
    {
        //Sends the data which you can put in the form to the db. CANT GET THE RIGHT LOCATION FROM THE DB {SOLVED}
        $xlcdLocationFormGUI = new xlcdLocationFormGUI($this, xlcdLocation::find($_GET[self::IDENTIFIER]));
        $xlcdLocationFormGUI->setValuesByPost();
        if ($xlcdLocationFormGUI->save()) {
            ilUtil::sendSuccess($this->pl->txt('system_account_msg_success'), true);
            $this->ctrl->redirect($this);
        }
        $this->tpl->setContent($xlcdLocationFormGUI->getHTML());

    }

    public function create()
    {
        //create a new location
        $xlcdLocationFormGUI = new xlcdLocationFormGUI($this, new xlcdLocation());

        $xlcdLocationFormGUI->setValuesByPost();
        if ($xlcdLocationFormGUI->save()) {
            ilUtil::sendSuccess($this->pl->txt('system_account_msg_success'), true);
            $this->ctrl->redirect($this);
        }
        $this->tpl->setContent($xlcdLocationFormGUI->getHTML());
    }

    public function confirmDelete()
    {
        /**
         * @var $xlcdLocation xlcdLocation
         */
        //Doublechecks if you really want to delete the location.
        $xlcdLocation = xlcdLocation::find($_GET[self::IDENTIFIER]);
        $this->ctrl->saveParameter($this, self::IDENTIFIER);

        ilUtil::sendQuestion(ilLocationDataPlugin::getInstance()->txt('confirm_delete_question'), true);
        $confirm = new ilConfirmationGUI();
        //Everything is null
        $confirm->addItem(self::IDENTIFIER, $xlcdLocation->getId(), $xlcdLocation->getTitle());
        $confirm->setFormAction($this->ctrl->getFormAction($this));
        $confirm->setCancel($this->txt('_cancel'), self::CMD_CANCEL);
        $confirm->setConfirm($this->txt('_delete'), self::CMD_DELETE);

        $this->tpl->setContent($confirm->getHTML());
    }


    public function delete()
    {
        /**
         * @var $xlcdLocation xlcdLocation
         */
        // finds the location with the identifier and deletes it
        $xlcdLocation = xlcdLocation::find($_GET[self::IDENTIFIER]);


        $xlcdLocation->delete();
        $this->cancel();

    }

    public function cancel()
    {
        //cancel function if you don't want to delete your location
        $this->ctrl->redirect($this, self::CMD_STANDARD);

    }

    /**
     * @param $key
     * @return string
     */

    public function txt($key)
    {
        //if($key == null) {
        // throw new ilObjectException('key is null');
        return $this->pl->txt($key);
        // }
    }

    public function applyFilter()
    {
        //apply filter function linked to the button. The page gets reloaded.

        $ilMyTableGUI = new ilMyTableGUI($this, self::CMD_STANDARD);
        $ilMyTableGUI->writeFilterToSession();
        $ilMyTableGUI->resetOffset();
        $this->ctrl->redirect($this, self::CMD_STANDARD);

    }

    public function resetFilter()
    {
        //reset the current filter and reloads the page
        $ilMyTableGUI = new ilMyTableGUI($this, self::CMD_STANDARD);
        $ilMyTableGUI->resetOffset();
        $ilMyTableGUI->resetFilter();
        $this->ctrl->redirect($this, self::CMD_STANDARD);
    }

    //returns the location_id

    /**
     * @return int
     */
    public function getLocationsDataId()
    {
        return $this->location_id;
    }
}
