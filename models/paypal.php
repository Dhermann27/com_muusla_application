<?php
/**
 * muusla_application Model for muusla_application Component
 *
 * @package    muusla_application
 * @subpackage Components
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 * muusla_application Model
 *
 * @package    muusla_application
 * @subpackage Components
 */
class muusla_applicationModelpaypal extends JModelItem
{
   function insertCharge($amount, $camperid, $txn) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $obj = new stdClass();
      $obj->camperid = $camperid;
      $obj->chargetypeid = 1022; // 1005;
      $obj->amount = $amount;
      $obj->timestamp = date("Y-m-d");
      $obj->memo = $txn;
      $obj->year = "&&(SELECT year FROM muusa_year WHERE is_current=1)";
      $obj->created_by = "paypal";
      $obj->created_at = date("Y-m-d H:i:s");
      $query = "SELECT id FROM muusa_charge WHERE chargetypeid=1022 AND timestamp='$obj->timestamp' AND camperid=$camperid";
      $db->setQuery($query);
      $chargeid = $db->loadResult();
      if($chargeid > 0) {
         $obj->id = $chargeid;
         $db->updateObject("muusa_charge", $obj, "id");
      }else {
         $db->insertObject("muusa_charge", $obj);
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }
}
