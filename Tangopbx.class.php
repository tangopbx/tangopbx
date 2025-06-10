<?php

namespace FreePBX\modules;

use BMO;
use FreePBX_Helpers;

class Tangopbx extends FreePBX_Helpers implements BMO
{
	const BRAND = 'TangoPBX';
	const RAWNAME = 'tangopbx';

	public $moduleFunctions = null;
	public $defaults = array(
		'schedule' => 'nightly',
		'onreload' => 'no',
	);

	private $advancedSettings = array(
		'BRAND_IMAGE_FAVICON' => 'modules/__RAWNAME__/assets/images/favicon.ico',
		'BRAND_IMAGE_TANGO_LEFT' => '/admin/modules/__RAWNAME__/assets/images/oem-top-left-image.png',
		'BRAND_TITLE' => '__BRAND__',
		'BRAND_IMAGE_FREEPBX_FOOT' => '',
		'BRAND_FREEPBX_ALT_LEFT' => '__BRAND__',
		'BRAND_FREEPBX_ALT_FOOT' => '__BRAND__',
		'BRAND_IMAGE_FREEPBX_LINK_LEFT' => 'https://tangopbx.org/',
		'BRAND_IMAGE_FREEPBX_LINK_FOOT' => 'https://tangopbx.org/',
		'BRAND_CSS_ALT_MAINSTYLE' => '',
		'BRAND_CSS_ALT_POPOVER' => '',
		'BRAND_CSS_CUSTOM' => 'modules/__RAWNAME__/assets/css/custom.css',
		'DASHBOARD_FREEPBX_BRAND' => '__BRAND__',
		'MODULE_REPO' => 'https://mirror.clearlyip.com',
		'VIEW_UCP_FOOTER_CONTENT' => 'modules/__RAWNAME__/views/ucp_footer_content.php',
		'VIEW_UCP_ICONS_FOLDER' => 'modules/__RAWNAME__/assets/icons',
		'VIEW_FOOTER_CONTENT' => 'modules/__RAWNAME__/views/footer_content.php',
		'VIEW_LOGIN' => 'modules/__RAWNAME__/views/login.php',
		'SIPUSERAGENT' => '__BRAND__',
		'RSSFEEDS' => 'https://clearlyip.com/feed/',
		'USE_FREEPBX_MENU_CONF' => true
	);

	//See https://github.com/FreePBX/framework/commit/a9713bf27754ba99670e356f02ad760e149651b2?branch=a9713bf27754ba99670e356f02ad760e149651b2&diff=split
	private $lessVars = array(
		'base-color' => 'rgb(215, 235, 249)',
		'background-color' => '@base-color',
		'text-color' => 'darken(@background-color, 50%)',
		'border-color' => 'fadeout(darken(@background-color, 10%),10%)',
		'table-th-background-color' => '@menu-button-background-color',
		'hr-background-color' => '@menu-button-background-color',
		'button-background-color' => '@background-color',
		'button-active-background-color' => 'darken(@button-background-color,50%)',
		'button-hover-background-color' => 'lighten(@button-background-color,5%)',
		'button-hover-text-color' => '@text-color',
		'button-hover-border-color' => 'lighten(@background-color,5%)',
		'nav-active-background-color' => '@background-color',
		'nav-hover-background-color' => 'lighten(@background-color,5%)',
		'menu-button-background-color' => '@background-color',
		'info-background-color' => '@background-color',
	);

	public function __construct($freepbx = null)
	{
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}

	public function install()
	{
		$settings = $this->FreePBX->Config;

		$set['value'] = 'tangopbx';
		$set['defaultval'] = $set['value'];
		$set['options'] = '';
		$set['readonly'] = 1;
		$set['hidden'] = 1;
		$set['level'] = 0;
		$set['module'] = 'tangopbx';
		$set['category'] = _('Branding');
		$set['emptyok'] = 0;
		$set['name'] = _('Brand ID');
		$set['description'] = _("ID for this brand");
		$set['type'] = CONF_TYPE_TEXT;
		$set['sortorder'] = 98;
		$settings->define_conf_setting('MIRROR_BRAND', $set);
		unset($set);

		$set['value'] = 'latest';
		$set['defaultval'] = $set['value'];
		$set['options'] = '';
		$set['readonly'] = 1;
		$set['hidden'] = 0;
		$set['level'] = 0;
		$set['module'] = 'tangopbx';
		$set['category'] = _('Branding');
		$set['emptyok'] = 0;
		$set['name'] = _('Lock Version');
		$set['description'] = _("Version to set this brand to");
		$set['type'] = CONF_TYPE_TEXT;
		$set['sortorder'] = 98;
		$settings->define_conf_setting('MIRROR_BRAND_VERSION', $set);
		unset($set);

		unset($settings);

		$this->removeConflictingModules();
		$this->setSettings($this->FreePBX);
		$this->clearModuleCache();
	}

	public function uninstall()
	{
		$settings = array_keys($this->advancedSettings);

		$this->FreePBX->Config->reset_conf_settings($settings, true);

		$this->clearModuleCache();
	}

	/**
	 * During an enable, let's make sure all our settings are set
	 */
	public function enable()
	{
		$this->setSettings($this->FreePBX);
	}

	/**
	 * When you disable this module, revert the advanced settings to prevent any issues with viewing things in the UI (in case it's removed)
	 */
	public function disable()
	{
		$this->uninstall();
	}

	public function backup()
	{
	}

	public function restore($backup)
	{
	}

	public static function myDialplanHooks()
	{
		return true;
	}

	public function doDialplanHook(&$ext, $engine, $priority)
	{
		$settings = $this->getModuleSettings();

		if ($settings['onreload'] !== 'yes') {
			return;
		}
	}



	//We do this because if we call get on a freepbx setting and it doesn't exist we get back int(0),
	//which could also be a valid value, so this allows us to know if it's valid or not
	private function getAvailableAdvancedSettings()
	{
		$advancedSettingKeys = array_keys($this->advancedSettings);
		$whereInArray  = str_repeat('?,', count($advancedSettingKeys) - 1) . '?';
		$sql = "SELECT * FROM freepbx_settings WHERE keyword IN ($whereInArray)";
		$stmt = \FreePBX::Database()->prepare($sql);
		$stmt->execute($advancedSettingKeys);
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		$keysAvailable = array();
		foreach ($data as $d) {
			$keysAvailable[$d['keyword']] = $d;
		}

		return $keysAvailable;
	}

	public function setSettings($FreePBX)
	{
		$settings = $FreePBX->Config;

		$keysAvailable = $this->getAvailableAdvancedSettings();

		foreach ($this->advancedSettings as $setting => $value) {
			//If the setting does not exist we can not change it
			if (!isset($keysAvailable[$setting])) {
				//We don't exist so we can't set the conf values
				continue;
			}
			$value = str_replace('__RAWNAME__', self::RAWNAME, $value);
			$value = str_replace('__BRAND__', self::BRAND, $value);
			$settings->set_conf_values(array($setting => $value), true, true);
		}
	}

	public function removeConflictingModules()
	{
		$mf = $this->getModuleFunctions();

		$modules = array(
			'oembranding'
		);

		foreach ($modules as $module) {
			//Delete tries to do an uninstall and then removes it
			$mf->delete($module, true);
		}
	}

	public function getModuleFunctions()
	{
		if (is_null($this->moduleFunctions)) {
			$this->moduleFunctions = new \module_functions();
		}
		return 	$this->moduleFunctions;
	}

	public function clearModuleCache()
	{
		$this->FreePBX->Config->set_conf_values(array('MODULEADMIN_SKIP_CACHE' => true), true, true);
		$mf = \module_functions::create();
		$mf->getonlinexml();
		$this->FreePBX->Config->reset_conf_settings(array('MODULEADMIN_SKIP_CACHE'), true);
	}


	public function showView($view = '')
	{
		$vars = $this->getModuleSettings();

		$vars['advanced'] = false;
		$vars['freepbx'] = $this->FreePBX;
		$vars['data'] = $this->getSupportData();

		if (isset($_REQUEST['advanced'])) {
			$vars['advanced'] = true;
	}
		return load_view(__DIR__ . '/views/admin.php', $vars);
	}

	public function doConfigPageInit($page)
	{
		foreach ($this->defaults as $key => $value) {
			if (isset($_REQUEST[$key])) {
				$this->updateModuleSetting($key, $_REQUEST[$key]);
			}
		}
	}

	public function ajaxRequest($command, $setting)
	{
		return false;
	}

	public function ajaxHandler()
	{
	}

	public function getModuleSettings()
	{
		$saved = $this->getAll('modulesettings');
		$defaults = $this->defaults;
		$return = array();
		foreach ($defaults as $key => $value) {
			$return[$key] = isset($saved[$key]) ? $saved[$key] : $value;
		}
		return $return;
	}

	public function updateModuleSetting($setting, $value)
	{
		$this->setConfig($setting, $value, 'modulesettings');
		return $this;
	}

	public function getSupportData()
	{
		$mirror = $this->FreePBX->Config->get('MODULE_REPO');
		$brand = $this->FreePBX->Config->get('MIRROR_BRAND');
		$version = $this->FreePBX->Config->get('MIRROR_BRAND_VERSION');
		$mf = $this->getModuleFunctions();
		$mirrorinfo = $mf->generate_remote_urls('all-' . $this->getFrameworkVersion() . '.0.xml', true);
		$options = $mirrorinfo['options'];
		/** Including all modules is obnoxious */
		$options['modules'] = [];
		return array(
			'mirror' => $mirror,
			'brand' => $brand,
			'version' => $version,
			'testCURL' => $this->getPostExample($mirrorinfo['mirrors'][0] . '/' . $mirrorinfo['path'], $options),
			'mirrordata' => $mirrorinfo,
		);
	}

	private function getFrameworkVersion()
	{
		$framework = explode('.', get_framework_version());
		return $framework[0];
	}

	private function getPostExample($url, $params)
	{
		$command = sprintf("curl --location --request POST '%s' ", $url);
		foreach ($params as $key => $value) {
			if (is_array($value)) {
				continue;
			}
			$command .= sprintf('--form \'%s="%s"\' ', $key, $value);
		}
		return $command;
	}

	public function lessVars($vars = array())
	{
		return $this->lessVars;
	}

}
