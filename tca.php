<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_nccu3er_slides'] = array (
	'ctrl' => $TCA['tx_nccu3er_slides']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'sys_language_uid,l10n_parent,l10n_diffsource,hidden,starttime,endtime,fe_group,name,url,link,target,description_heading,description_paragraph,description_link,description_target,transition_num,transition_slicing,transition_direction,transition_duration,transition_delay,transition_shader,transition_light_position,transition_cube_color,transition_z_multiplier,transition_flag'
	),
	'feInterface' => $TCA['tx_nccu3er_slides']['feInterface'],
	'columns' => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_nccu3er_slides',
				'foreign_table_where' => 'AND tx_nccu3er_slides.pid=###CURRENT_PID### AND tx_nccu3er_slides.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'name' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.name',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required,trim',
			)
		),
		'url' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.url',		
			'config' => array (
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],	
				'max_size' => $GLOBALS['TYPO3_CONF_VARS']['BE']['maxFileSize'],	
				'uploadfolder' => 'uploads/tx_nccu3er/',
				'show_thumbs' => 1,	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'link' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.link',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'target' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.target',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.target.I.0', '0'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.target.I.1', '1'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.target.I.2', '2'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.target.I.3', '3'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'description_heading' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_heading',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
			)
		),
		'description_paragraph' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_paragraph',		
			'config' => array (
				'type' => 'text',
				'wrap' => 'OFF',
				'cols' => '40',	
				'rows' => '10',
			)
		),
		'description_link' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_link',		
			'config' => array (
				'type'     => 'input',
				'size'     => '15',
				'max'      => '255',
				'checkbox' => '',
				'eval'     => 'trim',
				'wizards'  => array(
					'_PADDING' => 2,
					'link'     => array(
						'type'         => 'popup',
						'title'        => 'Link',
						'icon'         => 'link_popup.gif',
						'script'       => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1'
					)
				)
			)
		),
		'description_target' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_target',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_target.I.0', '0'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_target.I.1', '1'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_target.I.2', '2'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.description_target.I.3', '3'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'transition_flag' => array (        
            'exclude' => 0,        
            'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.flag',        
            'config' => array (
                'type' => 'check',
            )
        ),
		'transition_num' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.num',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '1'
				),
				'default' => 1
			)
		),
		'transition_slicing' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.slicing',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.slicing.I.0', '0'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.slicing.I.1', '1'),
				),
				'size' => 1,	
				'maxitems' => 1,
				'default' => 0
			)
		),
		'transition_direction' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.direction',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.direction.I.0', '0'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.direction.I.1', '1'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.direction.I.2', '2'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.direction.I.3', '3'),
				),
				'size' => 1,	
				'maxitems' => 1,
				'default' => 0
			)
		),
		'transition_duration' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 0,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.duration',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'double',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0.5
			)
		),
		'transition_delay' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.delay',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'double',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 0.1
			)
		),
		'transition_shader' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.shader',		
			'config' => array (
				'type' => 'select',
				'items' => array (
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.shader.I.0', '0'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.shader.I.1', '1'),
					array('LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.shader.I.2', '2'),
				),
				'size' => 1,	
				'maxitems' => 1,
				'default' => 0
			)
		),
		'transition_light_position' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.light_position',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'trim',
				'default' => '0,0,-100'
			)
		),
		'transition_cube_color' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.cube_color',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'wizards' => array(
					'_PADDING' => 2,
					'color' => array(
						'title' => 'Color:',
						'type' => 'colorbox',
						'dim' => '12x12',
						'tableStyle' => 'border:solid 1px black;',
						'script' => 'wizard_colorpicker.php',
						'JSopenParams' => 'height=300,width=250,status=0,menubar=0,scrollbars=1',
					),
				),
			)
		),
		'transition_z_multiplier' => array (		
			'displayCond' => 'FIELD:transition_flag:>:0',
			'exclude' => 1,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_transitions.z_multiplier',		
			'config' => array (
				'type'     => 'input',
				'size'     => '4',
				'max'      => '4',
				'eval'     => 'int',
				'checkbox' => '0',
				'range'    => array (
					'upper' => '1000',
					'lower' => '0'
				),
				'default' => 2
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => '--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.div_general,name,sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, 
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.div_image,url, 
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.div_link,link, target, 
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.div_description,description_heading, description_paragraph, description_link, description_target,
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_slides.div_transition,transition_flag,transition_num,transition_slicing,transition_direction,transition_duration,transition_delay,transition_shader,transition_light_position,transition_cube_color,transition_z_multiplier')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);




$TCA['tx_nccu3er_cubes'] = array (
	'ctrl' => $TCA['tx_nccu3er_cubes']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'name,hidden,starttime,endtime,fe_group,name,user_interface,slides'
	),
	'feInterface' => $TCA['tx_nccu3er_cubes']['feInterface'],
	'columns' => array (
		't3ver_label' => array (		
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.versionLabel',
			'config' => array (
				'type' => 'input',
				'size' => '30',
				'max'  => '30',
			)
		),
		'hidden' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'  => array (
				'type'    => 'check',
				'default' => '0'
			)
		),
		'starttime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'default'  => '0',
				'checkbox' => '0'
			)
		),
		'endtime' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config'  => array (
				'type'     => 'input',
				'size'     => '8',
				'max'      => '20',
				'eval'     => 'date',
				'checkbox' => '0',
				'default'  => '0',
				'range'    => array (
					'upper' => mktime(3, 14, 7, 1, 19, 2038),
					'lower' => mktime(0, 0, 0, date('m')-1, date('d'), date('Y'))
				)
			)
		),
		'fe_group' => array (		
			'exclude' => 1,
			'label'   => 'LLL:EXT:lang/locallang_general.xml:LGL.fe_group',
			'config'  => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
					array('LLL:EXT:lang/locallang_general.xml:LGL.hide_at_login', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.any_login', -2),
					array('LLL:EXT:lang/locallang_general.xml:LGL.usergroups', '--div--')
				),
				'foreign_table' => 'fe_groups'
			)
		),
		'name' => array (        
            'exclude' => 1,        
            'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.name',        
            'config' => array (
                'type' => 'input',    
                'size' => '30',
            )
        ),
		'user_interface' => array (		
			'exclude' => 0,		
			'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.user_interface',		
			'config' => array (
				'type' => 'flex',
				'ds' => array (
					'default' => 'FILE:EXT:nc_cu3er/flexform_tx_nccu3er_cubes_user_interface.xml',
				),
			)
		),
        'slides' => array (        
            'exclude' => 0,        
            'label' => 'LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.slides',        
			'config' => array (
				'type' => 'inline',
				'foreign_table' => 'tx_nccu3er_slides',
				'maxitems' => 100,
				'appearance' => Array (
					'collapseAll' => 1,
					'expandSingle' => 1,
					'newRecordLinkAddTitle' => 1,
					'newRecordLinkPosition' => 'bottom',
					'useSortable' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1,
					'showSynchronizationLink' => 1,
				),
			),
        ),
	),
	'types' => array (
		'0' => array('showitem' => '--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.div_general,name,hidden;;1;;1-1-1, 
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.div_user_interface,user_interface, 
									--div--;LLL:EXT:nc_cu3er/locallang_db.xml:tx_nccu3er_cubes.div_slides,slides')
	),
	'palettes' => array (
		'1' => array('showitem' => 'starttime, endtime, fe_group')
	)
);
?>