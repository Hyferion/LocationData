<?php
/**
 * Created by PhpStorm.
 * User: sst
 * Date: 08.06.17
 * Time: 16:26
 */
require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once 'class.xlcdLocation.php';
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/classes/Location/class.xlcdLocationGUI.php';
require_once './Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php';
require_once './Services/Form/classes/class.ilTextInputGUI.php';
require_once './Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/templates/tpl.LocationView.html';

//Creates the tableGUI at the start of the plugin. Lists all of the Locations and options.
class ilMyTableGUI extends ilTable2GUI
{
    /**
     * @var ilCtrl
     */
    protected $ctrl;
    /**
     * @var xlcdLocationGUI
     */
    protected $parent_obj;
    /**
     * @var ilLocationDataPlugin
     */
    protected $pl;
    /**
     * @var array
     */
    protected $filter = array();


    /**
     * ilMyTableGUI constructor.
     * @param xlcdLocationGUI $a_parent_obj
     * @param string $a_parent_cmd
     *
     */

    //Creates the whole GUI with the constructor.
    function __construct($a_parent_obj, $a_parent_cmd)
    {


        global $ilCtrl, $lng, $ilToolbar;
        $lng->loadLanguageModule("table");
        $this->toolbar = $ilToolbar;
        $this->ctrl = $ilCtrl;
        $this->setId("id");
        parent::__construct($a_parent_obj, $a_parent_cmd);


        $this->pl = ilLocationDataPlugin::getInstance();
        $this->getEnableHeader();
        $this->setTitle("Locations");


        $this->addColumn($this->pl->txt("_title"), "", "20%");
        $this->addColumn($this->pl->txt("_description"), "", "20%");
        $this->addColumn($this->pl->txt("_latitude"), "", "20%");
        $this->addColumn($this->pl->txt("_longitude"), "", "20%");
        $this->addColumn($this->pl->txt("_actions"), "", "20%");
        //$this->initColums();
        $this->setRowTemplate("Customizing/global/plugins/Services/Repository/RepositoryObject/LocationData/templates/tpl.locations.html");
        $this->setFormAction($this->ctrl->getFormAction($a_parent_obj));
        $this->setData(xlcdLocation::getArray());
        $this->initFilter();
        $this->setExternalSorting(true);
        $this->getItems();


        $b = ilLinkButton::getInstance();
        $b->setCaption($this->pl->txt('_table_add'), false);
        $this->toolbar->addButtonInstance($b);
        $b->setUrl($this->ctrl->getLinkTargetByClass(xlcdLocationGUI::class, xlcdLocationGUI::CMD_ADD));

    }

//Fields to read the filter input
    public function initFilter()
    {
        $title = new ilTextInputGUI($this->pl->txt('fil_title'), 'title');
        $this->addAndReadFilterItem($title);

        $description = new ilTextInputGUI($this->pl->txt('fil_description'), 'description');
        $this->addAndReadFilterItem($description);

        $title->setMaxLength(64);
        $title->setSize(20);
        $title->readFromSession();
        $this->filter['title'] = $title->getValue();
        $this->filter['description'] = $description->getValue();
    }

    public function getItems()
    {
        $collection = xlcdLocation::getCollection();
        $collection->where(array('title' => "%" . $this->filter['title'] . "%"), "LIKE");
        $sorting_column = $this->getOrderField() ? $this->getOrderField() : 'title';
        $offset = $this->getOffset() ? $this->getOffset() : 0;
        $sorting_direction = $this->getOrderDirection();
        $num = $this->getLimit();

        $collection->orderBy($sorting_column, $sorting_direction);
        $collection->limit($offset, $num);

        $this->setData($collection->getArray());
    }


    //Fields to read the filter input
    public function addAndReadFilterItem(ilFormPropertyGUI $item)
    {
        $this->addFilterItem($item);
        $item->readFromSession();
        if ($item instanceof ilCheckboxInputGUI) {
            $this->filter[$item->getPostVar()] = $item->getChecked();
        } else {
            $this->filter[$item->getPostVar()] = $item->getValue();
        }
        $this->setDisableFilterHiding(true);
    }



    /**
     * @param array $a_set
     */

    //fills the table with Locations. Requests the details from the db.
    public function fillRow($a_set)
    {
        global $ilCtrl;
        /**
         * @var $xlcdLocation xlcdLocation
         * @var $ilCtrl ilCtrl
         */

        $xlcdLocation = xlcdLocation::find($a_set['id']);
        $this->tpl->setVariable('TITLE', $xlcdLocation->getTitle());
        $this->tpl->setVariable('DESCRIPTION', $xlcdLocation->getDescription());
        $this->tpl->setVariable('LATITUDE', $xlcdLocation->getLatitude());
        $this->tpl->setVariable('LONGITUDE', $xlcdLocation->getLongitude());


        $button = ilLinkButton::getInstance();
        $button->setCaption($this->pl->txt('common_actions_button'), false);
        $button->setUrl($ilCtrl->getLinkTargetByClass(strtolower(xlcdLocationGUI::class)));

        $this->tpl->setVariable($this->pl->txt('_actions'), $button->render());

        $this->addActionMenu($xlcdLocation);
        //$this->addActionButton($xlcdLocation);

    }

    /*
     * creates the actionmenu with three buttons. View, edit and delete
     * Links the buttons to the LocationGUI where they either get redirected to the formGUI or stay in the view
     */
    public function addActionMenu(xlcdLocation $xlcdLocation)
    {

        global $access;
        $access = new ilObjLocationDataAccess();
        $current_selection_list = new ilAdvancedSelectionListGUI();
        $current_selection_list->setListTitle($this->pl->txt('_actions'));
        $current_selection_list->setId('loc_actions_' . $xlcdLocation->getId());
        $current_selection_list->setUseImages(false);

        $this->ctrl->setParameterByClass(xlcdLocationGUI::class, xlcdLocationGUI::IDENTIFIER, $xlcdLocation->getId());

        if ($access->hasReadAccess()) {

            $current_selection_list->addItem($this->pl->txt('view'), xlcdLocationGUI::CMD_VIEW, $this->ctrl->getLinkTargetByClass(xlcdLocationGUI::class, xlcdLocationGUI::CMD_VIEW));
        }
        if ($access->hasWriteAccess()) {

            $current_selection_list->addItem($this->pl->txt('edit'), xlcdLocationGUI::CMD_EDIT, $this->ctrl->getLinkTargetByClass(xlcdLocationGUI::class, xlcdLocationGUI::CMD_EDIT));
        }
        if ($access->hasWriteAccess()) {
            $current_selection_list->addItem($this->pl->txt('delete'), xlcdLocationGUI::CMD_DELETE, $this->ctrl->getLinkTargetByClass(xlcdLocationGUI::class, xlcdLocationGUI::CMD_DELETE));
        }

        $this->tpl->setVariable('ACTIONS', $current_selection_list->getHTML());


    }


}