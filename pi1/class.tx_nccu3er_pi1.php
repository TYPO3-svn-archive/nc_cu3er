<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 roman stulz, netcase <info@netcase.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'cu3er' for the 'nc_cu3er' extension.
 *
 * @author	roman stulz, netcase <info@netcase.ch>
 * @package	TYPO3
 * @subpackage	tx_nccu3er
 */
class tx_nccu3er_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_nccu3er_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_nccu3er_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'nc_cu3er';	// The extension key.

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		$this->pi_USER_INT_obj = 1;
		$this->pi_initPIflexform();
		$this->piFlexForm = $this->cObj->data['pi_flexform'];

		$this->useStatic = $this->conf['useStaticConfig'];
		$this->useStatic = $this->pi_getFFvalue($this->piFlexForm, 'use_static_config', 'sCONF');

		if ($this->useStatic > 0) {
			$this->staticConfigurationFile = $this->pi_getFFvalue($this->piFlexForm, 'static_config', 'sCONF');
			if ($this->staticConfigurationFile == '') {
				$this->staticConfigurationFile = $this->conf['staticConfigFile'];
			}
		} else{
			$this->dynConfigPath = $GLOBALS['TSFE']->tmpl->setup['config.']['baseURL'].$this->conf['pathConfigFile'];
		}

		$this->cu3er = $this->pi_getFFvalue($this->piFlexForm, 'cu3er', 'sCONF');

		$this->width = $this->pi_getFFvalue($this->piFlexForm, 'width', 'sOPT');
		if ($this->width == '') {
			$this->width = $this->conf['width'];
		}

		$this->height = $this->pi_getFFvalue($this->piFlexForm, 'height', 'sOPT');
		if ($this->height == '') {
			$this->height = $this->conf['height'];
		}

		$this->wmode = $this->pi_getFFvalue($this->piFlexForm, 'wmode', 'sOPT');
		if ($this->wmode == '')
			$this->wmode = $this->conf['wmode'];

		$this->alternativeContent = $this->pi_getFFvalue($this->piFlexForm, 'alternative_content', 'sALT');

		if ($this->alternativeContent == '') {
			$this->alternativeContentOutput = '
				<a href="http://www.adobe.com/go/getflashplayer">
					<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
				</a>
			';
		} else {
			$tt_content_conf = array('tables' => 'tt_content', 'source' => $this->alternativeContent, 'dontCheckPid' => 1);
			$this->alternativeContentOutput = $this->cObj->RECORDS($tt_content_conf);
		}

		//include swfObject and JS-Code
		$GLOBALS['TSFE']->additionalHeaderData[$this->prefixId] .= '
			<script type="text/javascript" src="'.$this->conf['pathSwfObject'].'"></script>
			<script type="text/javascript">
				var flashvars = {};
				flashvars.xml = "'.$this->getXML().'";
				flashvars.font = "'.$this->conf['pathFlashFont'].'";
				var attributes = {};
				attributes.wmode = "'.$this->wmode.'";
				attributes.id = "'.$this->conf['idFlashObject'].'_'.$this->cu3er.'";
				swfobject.embedSWF("'.$this->conf['pathFlashFile'].'", "'.$this->conf['idSwfObject'].'_'.$this->cu3er.'", "'.$this->width.'", "'.$this->height.'", "'.$this->conf['flashVersion'].'", "'.$this->conf['pathExpressInstall'].'", flashvars, attributes);
			</script>
		';

		$content='
			<div id="'.$this->conf['idSwfObject'].'_'.$this->cu3er.'">
				'.$this->alternativeContentOutput.'
			</div>
		';
		return $this->pi_wrapInBaseClass($content);
	}

	function getXML() {
		if ($this->useStatic > 0) {
			return $this->staticConfigurationFile;
		}
		return $this->dynConfigPath . 'config_' . $this->cu3er .'.xml';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nc_cu3er/pi1/class.tx_nccu3er_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/nc_cu3er/pi1/class.tx_nccu3er_pi1.php']);
}

?>