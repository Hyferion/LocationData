<?php

require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');

/**
 * ilLocationDataConfigGUI
 *
 * This Class provides the general co0ntrol flow for the global plugins settings in
 * Administration-> Plugins -> LocationData -> Actions -> Configure
 *
 * After clicking the performCommand($cmd); method is called
 *
 * @author             Fabian Schmid <fs@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy  ilLocationDataConfigGUI: ilObjComponentSettingsGUIs
 */
class ilLocationDataConfigGUI extends ilPluginConfigGUI
{

    public function executeCommand()
    {
        $this->storeParameters();
        $this->showTitleAndDescription();
        $this->showTabs();
    }


    /**
     * @param strind $cmd (default command is "configure")
     */
    public function performCommand($cmd)
    {
    }


    protected function storeParameters()
    {
        global $ilCtrl;
        /**
         * @var $ilCtrl ilCtrl
         */
        $ilCtrl->setParameterByClass("ilobjcomponentsettingsgui", "ctype", $_GET["ctype"]);
        $ilCtrl->setParameterByClass("ilobjcomponentsettingsgui", "cname", $_GET["cname"]);
        $ilCtrl->setParameterByClass("ilobjcomponentsettingsgui", "slot_id", $_GET["slot_id"]);
        $ilCtrl->setParameterByClass("ilobjcomponentsettingsgui", "plugin_id", $_GET["plugin_id"]);
        $ilCtrl->setParameterByClass("ilobjcomponentsettingsgui", "pname", $_GET["pname"]);
    }


    protected function showTitleAndDescription()
    {
        global $lng, $tpl;
        $tpl->setTitle($lng->txt("cmps_plugin") . ": " . $_GET["pname"]);
        $tpl->setDescription("");
    }


    protected function showTabs()
    {
        global $ilCtrl, $ilTabs, $lng;
        /**
         * @var $ilCtrl ilCtrl
         * @var $ilTabs \ilTabsGUI
         */
        $ilTabs->clearTargets();

        if ($_GET["plugin_id"]) {
            $ilTabs->setBackTarget($lng->txt("cmps_plugin"), $ilCtrl->getLinkTargetByClass("ilobjcomponentsettingsgui", "showPlugin"));
        } else {
            $ilTabs->setBackTarget($lng->txt("cmps_plugins"), $ilCtrl->getLinkTargetByClass("ilobjcomponentsettingsgui", "listPlugins"));
        }
    }
}
