<?php 
class tx_nccu3er_tcemainprocdm extends tslib_pibase {

    function processDatamap_afterDatabaseOperations ($status,$table,$id,&$fieldArray,&$reference) {
    	require_once (PATH_t3lib.'class.t3lib_page.php');
		require_once (PATH_t3lib.'class.t3lib_tstemplate.php');
		require_once (PATH_t3lib.'class.t3lib_tsparser_ext.php'); 
    	
		// get folder from constants
		$_extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['nc_cu3er']);
		$folder = $_extConfig['pathConfigFile'];

		//if ($status == 'update' && $table == 'tx_nccu3er_cubes') {
		if (($status == 'update' || $status == 'new') && $table == 'tx_nccu3er_cubes') {
    		$row = t3lib_BEfunc::getRecord($table, $id);
    		$flexFormArray = t3lib_div::xml2array($row['user_interface']);
    		$xmlEntries = $flexFormArray['data'];
    		$xmlArray = array();

    		foreach ($xmlEntries as $keyA => $valueA){
    			foreach($valueA['lDEF'] as $keyB => $valueB){
    				$xmlArray[$keyA][$keyB] = $valueB['vDEF'];
    			}
    		}
    		$xmlOutput = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>'.chr(10);
    		$xmlOutput .= '<cu3er>'.chr(10);
    		$xmlOutput .= '	<settings type="array">'.chr(10);
    		// loops the nodes (general, auto_play, preloader etc.)
    		foreach ($xmlArray as $keyC => $valueC){
    			
    			$xmlOutput.= chr(10).'		<'.$keyC;
    			if ($keyC != 'general' && $keyC != 'transitions') {
    			//sections with defaults and tweenIn - sections
    				$xmlOutput.='><defaults ';
	    			foreach($valueC as $keyD => $valueD){
	    				if (strstr($keyD,"defaults_") != false) {
	    					if ($valueD != '')
	    						$xmlOutput .= chr(10).str_replace('defaults_','',$keyD).'="'.$valueD.'"';
	    				}
	    			}
	    			$xmlOutput.=' />';

	    			$xmlOutput.='<tweenIn ';
	    			foreach($valueC as $keyE => $valueE){
	    				if (strstr($keyE,"tweenIn_") != false) {
	    					if ($valueE != '')
	    						$xmlOutput .= chr(10).str_replace('tweenIn_','',$keyE).'="'.$valueE.'"';
	    				}
	    			}
	    			$xmlOutput.=' /></'.$keyC.'>'.chr(10);
    			}else {
	    			foreach($valueC as $keyF => $valueF){
	    				if ($valueF !='' && $keyF != 'slide_panel_width' && $keyF != 'slide_panel_height')
	    					$xmlOutput .= chr(10).$keyF.'="'.$valueF.'"';
	    			}
	    			$xmlOutput.= chr(10).'		/>'.chr(10);
    			}
    		}
    		$xmlOutput.='	</settings>'.chr(10);
    		$xmlOutput.='	<slides type="array">'.chr(10);
    		$xmlOutput.=$this->getSlideXML($row['slides']);
    		$xmlOutput.='	</slides>'.chr(10);
    		$xmlOutput.= '</cu3er>'.chr(10);
    		
    		//write file
    		//$filename = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT')  . '/' . $conf['pathConfigFile'].'config_'.$id.'.xml';
    		$filename = t3lib_div::getIndpEnv('TYPO3_DOCUMENT_ROOT')  . '/' . $folder.'config_'.$id.'.xml';
    		//t3lib_div::debug($filename);
			if (!$handle = fopen($filename, 'w')) {
				exit;
			}
			if (fwrite($handle, $xmlOutput) === FALSE) {
				exit;
			}
			fclose($handle);
    	}
    }
	function getSlideXML($slideUID){
    	$arrSlides = explode(',',$slideUID);
    	foreach ($arrSlides as $value) {
    		$row = t3lib_BEfunc::getRecord('tx_nccu3er_slides',$value);
    		$xmlOutput.='<slide type="array">'.chr(10);
    		if ($row['url'] !='') {
    			$xmlOutput.='	<url>uploads/tx_nccu3er/'.$row['url'].'</url>'.chr(10);
    			if ($row['link'] != '')
    				$xmlOutput.='	<link target="'.$row['target'].'">'.$row['link'].'</link>'.chr(10);
    		}
    		if ($row['description_paragraph'] != '') {
	    		$xmlOutput.='	<description>'.chr(10);
	    		if ($row['description_link'] != '')
	    			$xmlOutput.='		<link target="'.$row['description_target'].'">'.$row['description_link'].'</link>'.chr(10);
	    		if ($row['description_heading'] != '')
	    		$xmlOutput.='		<heading>'.$row['description_heading'].'</heading>'.chr(10);
	    		$xmlOutput.='		<paragraph>'.$row['description_paragraph'].'</paragraph>'.chr(10);
	    		$xmlOutput.='	</description>'.chr(10);
    		}
    		$xmlOutput.='</slide>';
    		if ($row['transition_flag'] > 0) {
    			$xmlOutput.='<transition'.chr(10);
	    		$xmlOutput.='	num="'.$row['transition_num'].'"'.chr(10);
	    		$xmlOutput.='	slicing="'.$this->getSlicingCode($row['transition_slicing']).'"'.chr(10);
	    		$xmlOutput.='	direction="'.$this->getDirectionCode($row['transition_direction']).'"'.chr(10);
	    		$xmlOutput.='	duration="'.$row['transition_duration'].'"'.chr(10);
	    		$xmlOutput.='	delay="'.$row['transition_delay'].'"'.chr(10);
	    		$xmlOutput.='	shader="'.$this->getShaderCode($row['transition_shader']).'"'.chr(10);
	    		$xmlOutput.='	light_position="'.$row['transition_light_position'].'"'.chr(10);
	    		$xmlOutput.='	z_multiplier="'.$row['transition_z_multiplier'].'"'.chr(10);
	    		$xmlOutput.='	cube_color="'.$row['transition_cube_color'].'"'.chr(10);
	    		$xmlOutput.='/>';
    		}
    		
    	}
    	return $xmlOutput;
    }
	function getDirectionCode($direction) {
    	switch($direction) {
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
	function getSlicingCode($slicing) {
    	switch($slicing) {
    		case 0:
    			return 'horizontal';
    		case 1:
    			return 'vertical';
    	}
    }
	function getShaderCode($shader) {
    	switch($shader) {
    		case 0:
    			return 'none';
    		case 1:
    			return 'flat';
    		case 2:
    			return 'phong';
    	}
    }
} 
?>