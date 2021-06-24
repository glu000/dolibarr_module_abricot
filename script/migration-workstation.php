<?php


/*
 * Script de migration WorkStation

Il faut renommer

- les fichiers et les répertoires
- les occurrences dans le code (classes, noms de variables)
- les occurrences dans les clés de traduction (mais pas les traductions elles-mêmes)
- les occurrences en base de données:
   - les droits (llx_rights_def)
   - les confs (llx_const)


 */


//if(is_file('../main.inc.php'))$dir = '../';
//else  if(is_file('../../../main.inc.php'))$dir = '../../../';
//else $dir = '../../';

//echo realpath('.'); exit;

if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER', '1');
if (! defined('NOREQUIREDB'))    define('NOREQUIREDB', '1');
if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC', '1');
if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN', '1');
if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK', '1');
if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL', '1');
if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU', '1');
if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML', '1');
if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX', '1');
if (! defined("NOLOGIN"))        define("NOLOGIN", '1');

for ($i=0, $f='../main.inc.php'; !is_file($f) && $i < 4; $i++) {
	$f="../$f";
	echo $f . "\n";
}
if ($i < 4) include_once $f;

function rename_files() {
	$OLD_MODULE_NAME='workstation';
	$NEW_MODULE_NAME='workstationatm';
	$CURRENT_MODULE_DIR=dol_buildpath('/' . $OLD_MODULE_NAME);

// fichiers à renommer au sein du module workstation
	$files_to_rename = array(
		'tpl/workstation.tpl.php',
		'tpl/workstation_link.tpl.php',
		'class/workstation.class.php',
		'class/actions_workstation.class.php',
		'admin/workstation_setup.php',
		'admin/workstation_about.php',
		'langs/fr_FR/workstation.lang',
		'langs/en_US/workstation.lang',
		'workstation.php',
		'img/object_workstation.png',
		'img/workstation.png',
		'lib/workstation.lib.php',
		'./core/modules/modWorkstation.class.php',
		'./core/triggers/interface_99_modWorkstation_Workstationtrigger.class.php',
	);

	function renamed($f) {
		return preg_replace('|([wW])orkstation(?!atm)|', '${1}orkstationatm', $f);
	}

	foreach ($files_to_rename as $file_to_rename) {
		$src_file = $CURRENT_MODULE_DIR . '/' . $file_to_rename;
		$dst_file = $CURRENT_MODULE_DIR . '/' . renamed($file_to_rename);
		if (is_file($src_file)) rename($src_file, $dst_file);
	}
	if (is_dir($CURRENT_MODULE_DIR)) rename($CURRENT_MODULE_DIR, dirname($CURRENT_MODULE_DIR) . '/' . $NEW_MODULE_NAME);
}

function rename_workstation_in_database() {
	$queries = [
		'CREATE TABLE llx_workstationatm LIKE llx_workstation',
		'INSERT INTO llx_workstationatm SELECT * FROM llx_workstation',

		'ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm',
		'ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm',
		'ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm',
		'ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm',
		'ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm',
	];
}

function detect_workstation_without_atm_in_code() {
	global $conf, $dolibarr_main_document_root_alt;
//	$module_roots = $conf->file->dol_document_root;
//	if (!is_array($module_roots)) $module_roots = array($module_roots);
//	foreach ($module_roots as $module_root) {
//		chdir()
//	}
	// POUR FAIRE SIMPLE, ON PART DU PRINCIPE QUE LES MODULES SONT BIEN DANS custom
//	$customs = explode(',', $dolibarr_main_document_root_alt);


	chdir(DOL_DOCUMENT_ROOT . '/custom');

	$REGEXP_DETECT_WORKSTATION="\bworkstation(?!atm)\b";

	$fichiers_php=explode("\n", `find ./ -name "*.php"`);
	$fichiers_langs=explode("\n", `find ./ -name "*.lang"`);
	if (`grep --version | grep "GNU grep"` === '') {
		echo "This script requires GNU grep because it uses perl regular expressions.\n";
		exit;
	}

	$n = 0;
	foreach ($fichiers_php as $file) {
		if ($file === '') continue;
//	echo "$file\n";
//	if ($n++ == 10) exit;
		if ($grep_result = `grep -P -i -n "$REGEXP_DETECT_WORKSTATION" "$file"`) {
			echo $file, "\n", $grep_result, "\n\n\n";
		}
	}
}

detect_workstation_without_atm_in_code();


//
//ALTER TABLE llx_nomenclaturedet RENAME COLUMN workstations TO workstationatms
//ALTER TABLE llx_projet_task_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_nomenclature_workstation RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_workstation_schedule RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_nomenclature_workstation_thm_object RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_workstation_product RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_asset_workstation_task RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_asset_workstation_of RENAME COLUMN fk_asset_workstation TO fk_asset_workstationatm
//ALTER TABLE llx_asset_workstation_product RENAME COLUMN fk_asset_workstation TO fk_asset_workstationatm
//ALTER TABLE llx_actioncomm_extrafields RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_workstation_workstation_resource RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_workstation_workstation_usergroup RENAME COLUMN fk_workstation TO fk_workstationatm
//ALTER TABLE llx_nomenclaturedet_20181030 RENAME COLUMN workstations TO workstationatms
//ALTER TABLE llx_procedure RENAME COLUMN fk_workstation TO fk_workstationatm



// UPDATE llx_c_email_templates SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_input_method SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_pos_cash_fence SET posmodule = "workstationatm" WHERE posmodule = "workstation"
// UPDATE llx_printing SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_prospectlevel SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_paiement SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_rights_def SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_rights_def SET module_position = "workstationatm" WHERE module_position = "workstation"
// UPDATE llx_c_effectif SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_shipment_mode SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_commande SET module_source = "workstationatm" WHERE module_source = "workstation"
// UPDATE llx_c_payment_term SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_user SET module_comm = "workstationatm" WHERE module_comm = "workstation"
// UPDATE llx_user SET module_compta = "workstationatm" WHERE module_compta = "workstation"
// UPDATE llx_c_input_reason SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_type_container SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_chargesociales SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_typent SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_actioncomm SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_cronjob SET module_name = "workstationatm" WHERE module_name = "workstation"
// UPDATE llx_facture SET module_source = "workstationatm" WHERE module_source = "workstation"
// UPDATE llx_c_civility SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_paper_format SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_menu SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_forme_juridique SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_type_contact SET module = "workstationatm" WHERE module = "workstation"
// UPDATE llx_c_type_fees SET module = "workstationatm" WHERE module = "workstation"








// UPDATE llx_element_resources SET element_id = "workstationatm" WHERE element_id = "workstation"
// UPDATE llx_element_resources SET element_type = "workstationatm" WHERE element_type = "workstation"
// UPDATE llx_actioncomm SET elementtype = "workstationatm" WHERE elementtype = "workstation"
// UPDATE llx_advtargetemailing SET type_element = "workstationatm" WHERE type_element = "workstation"
// UPDATE llx_c_action_trigger SET elementtype = "workstationatm" WHERE elementtype = "workstation"
// UPDATE llx_comment SET fk_element = "workstationatm" WHERE fk_element = "workstation"
// UPDATE llx_comment SET element_type = "workstationatm" WHERE element_type = "workstation"
// UPDATE llx_actioncomm_resources SET element_type = "workstationatm" WHERE element_type = "workstation"
// UPDATE llx_blockedlog SET element = "workstationatm" WHERE element = "workstation"
// UPDATE llx_element_contact SET element_id = "workstationatm" WHERE element_id = "workstation"
// UPDATE llx_extrafields SET elementtype = "workstationatm" WHERE elementtype = "workstation"
// UPDATE llx_c_type_contact SET element = "workstationatm" WHERE element = "workstation"
// UPDATE llx_c_field_list SET element = "workstationatm" WHERE element = "workstation"


// UPDATE llx_onlinesignature SET object_type = "workstationatm" WHERE object_type = "workstation"
// UPDATE llx_nomenclature SET object_type = "workstationatm" WHERE object_type = "workstation"

















