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
	function insertCharge($amount) {
//		$db =& JFactory::getDBO();
//		$user =& JFactory::getUser();
//		$query = "INSERT INTO muusa_charges (camperid, chargetypeid, amount, timestamp, fiscalyear, created_by, created_at) VALUES (";
//		$query .= "(SELECT IF(hohid=0,camperid,hohid) camperid FROM muusa_campers WHERE email='" . $user->email . "'), ";
//		$query .= "(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Paypal%'), ";
//		$query .= "-" . $amount . ", ";
//		$query .= "CURRENT_TIMESTAMP, ";
//		$query .= "2009, '$user->username', CURRENT_TIMESTAMP)";
//		$db->setQuery($query);
//		$db->query();
//		if($db->getErrorNum()) {
//			JError::raiseError(500, $db->stderr());
//		}
	}
}