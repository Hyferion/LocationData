<?php

/**
 * Created by PhpStorm.
 * User: sst
 * Date: 13.06.17
 * Time: 10:41
 */

require_once './Services/Form/classes/class.ilPropertyFormGUI.php';
require_once './Services/Form/classes/class.ilLocationInputGUI.php';
require_once './Services/Form/classes/class.ilTextAreaInputGUI.php';

class xlcdLocationFormGUI extends ilPropertyFormGUI
{
    const DATETIME = 'Y-m-d H:i:s';
    /**
     * @var xlcdLocation
     */
    protected $object;
    /**
     * @var xlcdLocationGUI
     */
    protected $parent_gui;
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var ilLocationDataPlugin
     */
    protected $pl;
    /**
     * @var boolean
     */
    protected $is_new;


    /**
     * @param               $parent_gui
     * @param xlcdLocation $xlcdLocation
     */
    public function __construct($parent_gui, xlcdLocation $xlcdLocation)
    {
        global $ilCtrl, $tpl;
        $this->object = $xlcdLocation;
        $this->parent_gui = $parent_gui;
        $this->ctrl = $ilCtrl;
        $this->pl = ilLocationDataPlugin::getInstance();
        $this->ctrl->saveParameter($parent_gui, xlcdLocationGUI::IDENTIFIER);
        $this->is_new = ($this->object->getId() == '');

        $this->initForm();
    }

    //creates the Edit, add form
    public function initForm()
    {
        $this->setTarget('_top');
        $this->setFormAction($this->ctrl->getFormAction($this->parent_gui));
        $this->initButtons();

        $te = new ilTextInputGUI($this->pl->txt('_title'), 'title');
        $te->setRequired(true);
        $this->addItem($te);

        $ta = new ilTextAreaInputGUI($this->pl->txt('_description'), 'desc');
        $ta->setRequired(true);
        $this->addItem($ta);

        $loc = new ilLocationInputGUI($this->pl->txt('_coordinates'), 'loc');
        $loc->setRequired(true);
        $this->addItem($loc);

    }

    //fills the form if you want to edit
    public function fillForm()
    {
        $array = array('title' => $this->object->getTitle(),
            'desc' => $this->object->getDescription(),
            'loc' => array('latitude' => $this->object->getLatitude(), 'longitude' => $this->object->getLongitude(), 'zoom' => 15));
        $this->setValuesByArray($array);
    }

    /**
     * @return bool|string
     */
    //if the Location doesn't exist it will call the create method else it will only update
    public function save()
    {
        if (!$this->fill()) {
            return false;
        }
        if ($this->object->getLocationDataId() == $this->parent_gui->getLocationsDataId()) {
            if (!xlcdLocation::where(array('id' => $this->object->getId()))->hasSets()) {
                $this->object->create();
            } else {
                $this->object->update();
            }
        }
        return true;
    }

    // creates the create and edit button. the create button only shows if you want to add a new location.
    protected function initButtons()
    {
        if ($this->is_new) {

            $this->setTitle($this->pl->txt('_create'));
            $this->addCommandButton(xlcdLocationGUI::CMD_CREATE, $this->pl->txt('_create'));
        } else {
            $this->setTitle($this->pl->txt('_edit'));
            $this->addCommandButton(xlcdLocationGUI::CMD_UPDATE, $this->pl->txt('_update'));
        }
        $this->addCommandButton(xlcdLocationGUI::CMD_CANCEL, $this->pl->txt('_cancel'));
    }

    /**
     * @return boolean
     */
    //fills the form with the data. always returns true
    public function fill()
    {
        if (!$this->checkInput()) {
            return false;
        }
        $this->object->setTitle($this->getInput('title'));
        $this->object->setDescription($this->getInput('desc'));

        $latlong = $this->getInput('loc');

        $this->object->setLatitude($latlong['latitude']);
        $this->object->setLongitude($latlong['longitude']);

        $this->object->setLocationDataId($this->parent_gui->getLocationsDataId());

        global $ilUser;
        $user_id = $ilUser->getId();
        $date = date(self::DATETIME);
        $this->object->setCreationDate($date);
        $this->object->setCreatorUserId($user_id);
        return true;
    }
}