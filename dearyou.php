<?php

require_once 'dearyou.civix.php';
use CRM_Dearyou_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function dearyou_civicrm_config(&$config) {
  _dearyou_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function dearyou_civicrm_xmlMenu(&$files) {
  _dearyou_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function dearyou_civicrm_install() {

  // Create settings.
  Civi::settings()->set('dearyou', '{}');

  _dearyou_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function dearyou_civicrm_postInstall() {
  _dearyou_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function dearyou_civicrm_uninstall() {
  Civi::settings()->set('dearyou', NULL);
  _dearyou_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function dearyou_civicrm_enable() {
  _dearyou_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function dearyou_civicrm_disable() {
  _dearyou_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function dearyou_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _dearyou_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function dearyou_civicrm_managed(&$entities) {
  _dearyou_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function dearyou_civicrm_caseTypes(&$caseTypes) {
  _dearyou_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function dearyou_civicrm_angularModules(&$angularModules) {
  _dearyou_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function dearyou_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _dearyou_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function dearyou_civicrm_entityTypes(&$entityTypes) {
  _dearyou_civix_civicrm_entityTypes($entityTypes);
}


/**
 * Provide the {dearyou} token.
 */
function dearyou_civicrm_tokens( &$tokens ) {
  $tokens['dearyou'] = [
    'dearyou.informal' => ts('Informal greeting :: Dear You'),
    'dearyou.formal'   => ts('Formal greeting :: Dear You'),
  ];
}
/**
 * Creates the dearyou token.
 */
function dearyou_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  if (empty($tokens['dearyou'])) {
    return;
  }

  $dearyou_settings = json_decode(Civi::settings()->get('dearyou'));

  // $tokens is sometimes like: { 'contact': { 'foo': 1 } } and sometimes like { 'contact': ['foo'] }
  if (is_numeric(key($tokens['dearyou']))) {
    // We have the 2nd form.
    $dearyou_tokens_in_use = array_values($tokens['dearyou']);
  }
  else {
    // tokens are keys.
    $dearyou_tokens_in_use = array_keys($tokens['dearyou']);
  }

  // Create an array including only the settings we need to process.
  // The array keys of tokens[dearyou] may include 'informal' or 'formal'
  // and if these exist in the config, use them.
  $versions = [];
  foreach ($dearyou_tokens_in_use as $_) {
    if (isset($dearyou_settings->tokens->$_)) {
      $versions[$_] = $dearyou_settings->tokens->$_;
    }
  }

  $contact_ids = [];
  foreach($cids as $cid) {
    $contact_ids[] = (int) $cid;
  }
  $contact_ids = implode(',', $contact_ids);
  if (!$contact_ids) {
    return;
  }
  $dao = CRM_Core_DAO::executeQuery("
      SELECT first_name, nick_name, last_name,
             legal_name, organization_name, display_name,
             contact_type, id
      FROM civicrm_contact
      WHERE id IN ($contact_ids)"
    );

  while ($dao->fetch()) {

    foreach ($versions as $key=>$config) {
      $val = '';
      $contact_type = strtolower($dao->contact_type);
      $prefs = explode(',', $config->$contact_type->ifData->prefs);
      foreach ($prefs as $prefferred_field) {
        if (!empty($dao->$prefferred_field)) {
          $val = $config->$contact_type->ifData->pre
                 . $dao->$prefferred_field
                 . $config->$contact_type->ifData->post;
          break;
        }
      }
      if (!$val) {
        $val = $config->$contact_type->ifNoData->text;
      }
      $values[$dao->id]["dearyou.$key"] = $val;
    }

  }
}

function dearyou_civicrm_navigationMenu(&$menu) {
  _dearyou_civix_insert_navigation_menu(
    $menu,
    'Administer/Customize Data and Screens',
    [
      'label'      => E::ts('Dear You greetings'),
      'name'       => 'dearyou_settings',
      'url'        => 'civicrm/a/#/dearyou',
      'permission' => 'administer CiviCRM',
      'attributes' => [],
      'operator'   => 'OR',
      'separator'  => 0,
    ]
  );
}

