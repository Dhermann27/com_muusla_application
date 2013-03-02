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
class muusla_applicationModelpaypal extends JModel
{
   function insertCharge($amount, $camperid, $txn) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $obj = new stdClass();
      $obj->camperid = $camperid;
      $obj->chargetypeid = 1005;
      $obj->amount = $amount;
      $obj->timestamp = date("Y-m-d");
      $obj->memo = $txn;
      $obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
      $obj->created_by = "paypal";
      $obj->created_at = date("Y-m-d H:i:s");
      $query = "SELECT chargeid FROM muusa_charges WHERE chargetypeid=1005 AND timestamp='$obj->timestamp' AND camperid=$camperid";
      $db->setQuery($query);
      $chargeid = $db->loadResult();
      if($chargeid > 0) {
         $obj->chargeid = $chargeid;
         $db->updateObject("muusa_charges", $obj, "chargeid");
      }else {
         $db->insertObject("muusa_charges", $obj);
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }
}