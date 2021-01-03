<?php
 
if (!defined('_PS_VERSION_')) {
    exit;
}
 
class Ns_MonModule extends Module
{
    public function __construct()
    {
        $this->name = 'ns_monmodule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Hary Rafalimanana & Thomas Debacker & Lamine_c';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;
 
        parent::__construct();
 
        $this->displayName = $this->l('Module Trouve ta zone');
        $this->description = $this->l('Proposer une vue d\'ensemble sur les différents marchés et par zone');
 
        $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');
 
        if (!Configuration::get('NS_MONMODULE_PAGENAME')) {
            $this->warning = $this->l('Aucun nom fourni');
        }
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
 
        if (!parent::install() ||
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('NS_MONMODULE_PAGENAME', 'Mentions légales')
        ) {
            return false;
        }
 
        return true;
    }
 
    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('NS_MONMODULE_PAGENAME')
        ) {
            return false;
        }
     
        return true;
    }

    public function hookDisplayHome($params)
    {
        
        $this->context->controller->registerJavascript('leaflet', 'https://unpkg.com/leaflet@1.6.0/dist/leaflet.js', ['server' => 'remote', 'position' => 'bottom', 'priority' => 0]);

        $this->context->controller->registerStylesheet(
            'leaflet', 'https://unpkg.com/leaflet@1.6.0/dist/leaflet.css',
            ['server' => 'remote', 'position' => 'head', 'priority' => 150]
        );

        $this->context->controller->registerStylesheet(
            'ns_monmodule',
            $this->_path.'views/css/ns_monmodule.css',
            ['server' => 'remote', 'position' => 'head', 'priority' => 150]
        );
        $this->context->controller->registerJavascript(
            'ns_monmodule',
            $this->_path.'views/js/ns_monmodule.js',
            ['server' => 'remote', 'position' => 'body', 'priority' => 150]
        );
                
        $this->context->smarty->assign([
        'ns_page_name' => Configuration::get('NS_MONMODULE_PAGENAME'),
        'ns_page_link' => $this->context->link->getModuleLink('ns_monmodule', 'display')
      ]);
        return $this->display(__FILE__, 'ns_monmodule.tpl');
    }
}

