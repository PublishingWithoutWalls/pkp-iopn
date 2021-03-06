<?php

/**
 * @file plugins/themes/iopn-ojs/IopnThemePlugin.inc.php
 *
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class IopnThemePlugin
 * @ingroup plugins_themes_iopn
 *
 * @brief IOPN theme
 */

import('lib.pkp.classes.plugins.ThemePlugin');

class IopnThemePlugin extends ThemePlugin {
	/**
	 * Constructor
	 */
	function IopnThemePlugin() {
		parent::ThemePlugin();
	}

	/**
	 * @copydoc ThemePlugin::isActive()
	 */
	public function isActive() {
                if (defined('SESSION_DISABLE_INIT')) return true;
		return parent::isActive();
	}

	/**
	 * Initialize the theme's styles, scripts and hooks. This is run on the
	 * currently active theme and it's parent themes.
	 *
	 * @return null
	 */
	public function init() {
		// Load Noto Sans font from Google Font CDN
		// To load extended latin or other character sets, see:
		// https://www.google.com/fonts#UsePlace:use/Collection:Noto+Sans
		if (Config::getVar('general', 'enable_cdn')) {
			$this->addStyle(
				'fontNotoSans',
				'//fonts.googleapis.com/css?family=Noto+Sans:400,400italic,700,700italic',
				array('baseUrl' => '')
			);
		}

		// Load primary stylesheet
		$this->addStyle('stylesheet', 'styles/index.less');

		// Load jQuery from a CDN or, if CDNs are disabled, from a local copy.
		$min = Config::getVar('general', 'enable_minified') ? '.min' : '';
		$request = Application::getRequest();
		if (Config::getVar('general', 'enable_cdn')) {
			$jquery = '//ajax.googleapis.com/ajax/libs/jquery/' . CDN_JQUERY_VERSION . '/jquery' . $min . '.js';
			$jqueryUI = '//ajax.googleapis.com/ajax/libs/jqueryui/' . CDN_JQUERY_UI_VERSION . '/jquery-ui' . $min . '.js';
		} else {
			// Use OJS's built-in jQuery files
			$jquery = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jquery/jquery' . $min . '.js';
			$jqueryUI = $request->getBaseUrl() . '/lib/pkp/lib/vendor/components/jqueryui/jquery-ui' . $min . '.js';
		}
		// Use an empty `baseUrl` argument to prevent the theme from looking for
		// the files within the theme directory
		$this->addScript('jQuery', $jquery, array('baseUrl' => ''));
		$this->addScript('jQueryUI', $jqueryUI, array('baseUrl' => ''));
		$this->addScript('jQueryTagIt', $request->getBaseUrl() . '/lib/pkp/js/lib/jquery/plugins/jquery.tag-it.js', array('baseUrl' => ''));

		// Load custom JavaScript for this theme
		$this->addScript('default', 'js/main.js');
	}

	/**
	 * Get the name of the settings file to be installed on new journal
	 * creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the name of the settings file to be installed site-wide when
	 * OJS is installed.
	 * @return string
	 */
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	 * Get the display name of this plugin
	 * @return string
	 */
	function getDisplayName() {
		return __('plugins.themes.iopn.name');
	}

	/**
	 * Get the description of this plugin
	 * @return string
	 */
	function getDescription() {
		return __('plugins.themes.iopn.description');
	}
}

?>
