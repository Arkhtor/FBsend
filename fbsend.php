<?php
if (!defined('_PS_VERSION_')) {
  exit;
}
 
class fbsend extends Module
{
  public function __construct()
  {
    $this->name = 'fbsend';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'Paweł Ługowski';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6'); 
    $this->bootstrap = true;
 
    parent::__construct();
 
    $this->displayName = $this->l('Facebook Send Message');
    $this->description = $this->l('Widget wyślij wiadomość na messengera do Presta 1.6');
 
    $this->confirmUninstall = $this->l('Jesteś pewien, że chcesz odinstalować ten moduł?');
 
    if (!Configuration::get('fbsend')) {
      $this->warning = $this->l('Brak pliku konfiguracyjnego');
    }
  }
    public function install()
{
  if (Shop::isFeatureActive()) {
    Shop::setContext(Shop::CONTEXT_ALL);
      
      return parent::install() &&
          $this->registerHook('leftColumn') &&
    $this->registerHook('header') &&
    $this->registerHook('displayFooter') &&
    Configuration::updateValue('fbchat', 'fbczat') &&
    Configuration::updateValue('fbpageid1', 'Id Twojej strony Facebook');
          
  }
 
  if (!parent::install()
    
  ) {
    return false;
  }
 
    //    DB::getInstance()->execute(
 //  "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "fbchat` (
 //     `fb_id_page` varchar(64) NOT NULL,
 //     `fb_wiad_zal` varchar(128) NOT NULL,
 //     `fb_wiad_wyl` varchar(128) NOT NULL,
 //     PRIMARY KEY (`fb_id_page`)
 //   ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;"
//);
        
        
        
  return true;
}
    public function uninstall()
{
  if (!parent::uninstall() ||
<<<<<<< HEAD:fbsend.php
    !Configuration::deleteByName('fbsend') ||
    !Configuration::deleteByName('fbpageid1')
=======
    !Configuration::deleteByName('fbchat')  ||
    !Configuration::deleteByName('fbwiadomzal1')  ||
    !Configuration::deleteByName('fbwiadomwyl1')
>>>>>>> ef47b8803b5bae1ceb0188f67e4229fa2e1cf227:fbchat.php
  ) {
    return false;
  }
 
  return true;
}
    public function hookDisplayFooter($params)
{
  $this->context->smarty->assign(
      array(
          'fbpageide' => Configuration::get('fbpageid1')
      )
  );
  return $this->display(__FILE__, 'fbsend.tpl');
}
   
    public function getContent()
{
    $output = null;
 
    if (Tools::isSubmit('submit'.$this->name))
    {
       
            Configuration::updateValue('fbpageid1', Tools::getValue('fbpageid'));
            $output .= $this->displayConfirmation($this->l('Zapisano ustawienia'));
        }
    
    return $output.$this->displayForm();
}

   public function displayForm()
{
    // Get default language
    $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
     
    // Init Fields form array
    $fields_form[0]['form'] = array(
        'legend' => array(
            'title' => $this->l('Ustawienia'),
        )
         );
      $fields_form[1]['form']['input'][] = array( 
        
           
                'type' => 'text',
                'label' => $this->l('FB page ID'),
                'name' => 'fbpageid',
                'size' => 20,
                'required' => true,
               'class' => 'col-md-6'
            
            );
         
        $fields_form[4]['form']['submit'] = array(
				'title' => $this->l('Zapisz')
			);
     
    $helper = new HelperForm();
     
    // Module, token and currentIndex
    $helper->module = $this;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
     
    // Language
    $helper->default_form_language = $default_lang;
    $helper->allow_employee_form_lang = $default_lang;
     
    // Title and toolbar
    $helper->title = $this->displayName;
    $helper->show_toolbar = true;        // false -> remove toolbar
    $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
    $helper->submit_action = 'submit'.$this->name;
    $helper->toolbar_btn = array(
        'save' =>
        array(
            'desc' => $this->l('Save'),
            'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
        ),
        'back' => array(
            'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Back to list')
        )
    );
     
    // Load current value
    $helper->fields_value['fbpageid'] = Configuration::get('fbpageid1');
     
    return $helper->generateForm($fields_form);
}
  
    
}
