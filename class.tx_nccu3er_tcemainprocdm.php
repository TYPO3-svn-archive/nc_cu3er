<?php

class tx_nccu3er_tcemainprocdm {

	/**
	 * @param string $status
	 * @param string $table
	 * @param integer $id
	 * @param array $fieldArray
	 * @param t3lib_TCEmain $reference
	 */
	function processDatamap_afterDatabaseOperations ($status, $table, $id, array $fieldArray, t3lib_TCEmain $reference) {
		if ($table == 'tx_nccu3er_cubes') {
			$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nc_cu3er']);

			if ($status == 'new') { // If a NEW... id
				$id = $reference->substNEWwithIDs[$id];
			}
			$row = t3lib_BEfunc::getRecord($table, $id);
			$flexFormArray = t3lib_div::xml2array($row['user_interface']);
			$xmlEntries = $flexFormArray['data'];

			$xmlArray = array();
			foreach ($xmlEntries as $languageKey => $languageValue) {
				foreach($languageValue['lDEF'] as $fieldKey => $fieldValue){
					$xmlArray[$languageKey][$fieldKey] = $fieldValue['vDEF'];
				}
			}

			$xmlOutput = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'.chr(10);
			$xmlOutput .= '<cu3er>'.chr(10);
			$xmlOutput .= '	<settings type="array">'.chr(10);

			// loops the nodes (general, auto_play, preloader, description, buttons and symbols and finally the transitions)
			foreach ($xmlArray as $group => $groupValue){
				$xmlOutput.= '		<'.$group;

				// general and transitions don't have child tags
				if ($group != 'general' && $group != 'transitions') {
					//sections with defaults and tweenIn - sections
					$xmlOutput.='>'.chr(10).'			<defaults '.chr(10);
					foreach ($groupValue as $key => $value) {
						if (strstr($key, 'defaults_') != false) {
							if ($value != '') {
								$xmlOutput .= '				'.str_replace('defaults_', '', $key).'="'.$value.'"'.chr(10);
							}
						}
					}
					$xmlOutput.='			/>'.chr(10);

					$xmlOutput.='			<tweenIn '.chr(10);
					foreach ($groupValue as $key => $value) {
						if (strstr($key, 'tweenIn_') != false) {
							if ($value != '') {
								$xmlOutput .= '				'.str_replace('tweenIn_', '', $key).'="'.$value.'"'.chr(10);
							}
						}
					}
					$xmlOutput.='			/>'.chr(10).'		</'.$group.'>'.chr(10);
				} else {
					$xmlOutput .= chr(10);
					foreach($groupValue as $key => $value) {
						if ($value != '' && $key != 'slide_panel_width' && $key != 'slide_panel_height') {
							$xmlOutput .= '			'.$key.'="'.$value.'"'.chr(10);
						}
					}
					$xmlOutput.= '		/>'.chr(10);
				}
			}
			$xmlOutput.='	</settings>'.chr(10);
			$xmlOutput.='	<slides type="array">'.chr(10);
			$xmlOutput.= $this->getSlideXML($row['slides']);
			$xmlOutput.='	</slides>'.chr(10);
			$xmlOutput.= '</cu3er>';

			// get folder from extension configuration
			$folder = $_extConfig['pathConfigFile'];

			$filename = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT').'/'.$folder.'config_'.$id.'.xml';

			// write file
			if ($handle = fopen($filename, 'w')) {
				if (fwrite($handle, $xmlOutput) !== FALSE) {
					$message = t3lib_div::makeInstance('t3lib_FlashMessage', 'Successfully saved xml config file under: '.$folder.'config_'.$id.'.xml', 'Success', t3lib_FlashMessage::OK, TRUE); /* @var $message t3lib_FlashMessage */
					t3lib_FlashMessageQueue::addMessage($message);
				} else {
					$message = t3lib_div::makeInstance('t3lib_FlashMessage', 'Error: Could not save xml config file under: '.$folder.'config_'.$id.'.xml', 'Error', t3lib_FlashMessage::ERROR, TRUE); /* @var $message t3lib_FlashMessage */
					t3lib_FlashMessageQueue::addMessage($message);
				}
				fclose($handle);
			} else {
				$message = t3lib_div::makeInstance('t3lib_FlashMessage', 'Error: Could not save xml config file under: '.$folder.'config_'.$id.'.xml', 'Error', t3lib_FlashMessage::ERROR, TRUE); /* @var $message t3lib_FlashMessage */
				t3lib_FlashMessageQueue::addMessage($message);
			}
		}
	}

	/**
	 * get the slide part
	 * @param integer $slideUID
	 * @return string The slide part
	 */
	function getSlideXML($slideUID){
		$arrSlides = explode(',', $slideUID);
		$xmlOutput = '';
		foreach ($arrSlides as $value) {
			$row = t3lib_BEfunc::getRecord('tx_nccu3er_slides',$value);
			$xmlOutput.='		<slide type="array">'.chr(10);
			if ($row['url'] != '') {
				$xmlOutput.='			<url>uploads/tx_nccu3er/'.$row['url'].'</url>'.chr(10);
				if ($row['link'] != '') {
					$xmlOutput.='			<link target="'.$row['target'].'">'.$row['link'].'</link>'.chr(10);
				}
			}
			if ($row['description_paragraph'] != '') {
				$xmlOutput.='			<description>'.chr(10);
				if ($row['description_link'] != '') {
					$xmlOutput.='				<link target="'.$row['description_target'].'">'.$row['description_link'].'</link>'.chr(10);
				}
				if ($row['description_heading'] != '') {
					$xmlOutput.='				<heading>'.$row['description_heading'].'</heading>'.chr(10);
				}
				$xmlOutput.='				<paragraph>'.$row['description_paragraph'].'</paragraph>'.chr(10);
				$xmlOutput.='			</description>'.chr(10);
			}
			$xmlOutput.='		</slide>'.chr(10);
			if ($row['transition_flag'] > 0) {
				$xmlOutput.='		<transition'.chr(10);
				$xmlOutput.='			num="'.$row['transition_num'].'"'.chr(10);
				$xmlOutput.='			slicing="'.$this->getSlicingCode($row['transition_slicing']).'"'.chr(10);
				$xmlOutput.='			direction="'.$this->getDirectionCode($row['transition_direction']).'"'.chr(10);
				$xmlOutput.='			duration="'.$row['transition_duration'].'"'.chr(10);
				$xmlOutput.='			delay="'.$row['transition_delay'].'"'.chr(10);
				$xmlOutput.='			shader="'.$this->getShaderCode($row['transition_shader']).'"'.chr(10);
				$xmlOutput.='			light_position="'.$row['transition_light_position'].'"'.chr(10);
				$xmlOutput.='			z_multiplier="'.$row['transition_z_multiplier'].'"'.chr(10);
				$xmlOutput.='			cube_color="'.$row['transition_cube_color'].'"'.chr(10);
				$xmlOutput.='		/>'.chr(10);
			}
		}
		return $xmlOutput;
	}

	/**
	 * get direction code
	 * @param integer $direction
	 */
	protected function getDirectionCode($direction) {
		switch ($direction) {
			case 0:
				return 'left';
			case 1:
				return 'right';
			case 2:
				return 'up';
			case 3:
				return 'down';
		}
	}

	/**
	 * get slicing code
	 * @param integer $slicing
	 */
	protected function getSlicingCode($slicing) {
		switch ($slicing) {
			case 0:
				return 'horizontal';
			case 1:
				return 'vertical';
		}
	}

	/**
	 * get shader code
	 * @param integer $shader
	 */
	protected function getShaderCode($shader) {
		switch ($shader) {
			case 0:
				return 'none';
			case 1:
				return 'flat';
			case 2:
				return 'phong';
		}
	}

	/**
	 * @param string $command
	 * @param string $table
	 * @param integer $id
	 * @param string $value
	 * @param t3lib_TCEmain $reference
	 */
	public function processCmdmap_postProcess($command, $table, $id, $value, t3lib_TCEmain $reference) {

		if ($command == 'delete' && $table == 'tx_nccu3er_cubes') {

			$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nc_cu3er']);
			// get folder from extension configuration
			$folder = $_extConfig['pathConfigFile'];

			$filename = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT').'/'.$folder.'config_'.$id.'.xml';

			# FAL is responsible for the images - hopefully soon ;)
			if (unlink($filename)) {
				$message = t3lib_div::makeInstance('t3lib_FlashMessage', 'Successfully deleted xml config file under: '.$folder.'config_'.$id.'.xml', 'Success', t3lib_FlashMessage::OK, TRUE); /* @var $message t3lib_FlashMessage */
				t3lib_FlashMessageQueue::addMessage($message);
			} else {
				$message = t3lib_div::makeInstance('t3lib_FlashMessage', 'Error: Could not delete xml config file under: '.$folder.'config_'.$id.'.xml', 'Error', t3lib_FlashMessage::ERROR, TRUE); /* @var $message t3lib_FlashMessage */
				t3lib_FlashMessageQueue::addMessage($message);
			}
		}
	}
}
?>