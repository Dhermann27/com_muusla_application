<?php
/**
 * muusla_prereg Model for muusla Component
 *
 * @package    muusla_prereg
 * @subpackage Components
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * muusla_prereg Model
 *
 * @package muusla_prereg
 * @subpackage Components
 */
class muusla_applicationModelprereg extends JModelItem
{

   function getYear() {
      $db = JFactory::getDBO();
      $query = "SELECT DATE_FORMAT(MAX(open), '%b %D'), MAX(year) FROM muusa_year";
      $db->setQuery($query);
      return $db->loadRow();
   }

    function getCampers ()
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $query = "SELECT c.id, c.firstname, c.lastname, muusa_getage(c.birthdate, (SELECT MAX(year) FROM muusa_year)) age, muusa_isprereg(c.id, (SELECT MAX(year) FROM muusa_year)) is_prereg, muusa_getprogramfee(c.id, (SELECT MAX(year) FROM muusa_year)) fee FROM muusa_camper c, muusa_camper cp WHERE cp.email='$user->email' AND cp.familyid=c.familyid ORDER BY IF(c.email='$user->email',0,1), c.birthdate";
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}