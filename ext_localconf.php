<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_nccu3er_pi1.php', '_pi1', 'list_type', 0);

t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_nccu3er_cubes=1
	mod.web_list.hideTables = tx_nccu3er_slides
');

$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:nc_cu3er/class.tx_nccu3er_tcemainprocdm.php:tx_nccu3er_tcemainprocdm';
?>