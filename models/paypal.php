<?php
/**
 * muusla_application Model for muusla_application Component
 *
 * @package    muusla_application
 * @subpackage Components
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * muusla_application Model
 *
 * @package muusla_application
 * @subpackage Components
 */
class muusla_applicationModelpaypal extends JModelItem
{

    function insertCharge ($amount, $camperid, $txn)
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $obj = new stdClass();
        $obj->camperid = $camperid;
        $obj->chargetypeid = 1005;
        $obj->amount = $amount;
        $obj->timestamp = date("Y-m-d");
        $obj->memo = $txn;
        $obj->year = "&&(SELECT year FROM muusa_year WHERE is_current=1)";
        $obj->created_by = "paypal";
        $obj->created_at = date("Y-m-d H:i:s");
        $query = "SELECT id FROM muusa_charge WHERE chargetypeid=1005 AND timestamp='$obj->timestamp' AND amount=-$amount AND camperid=$camperid";
        $db->setQuery($query);
        $chargeid = $db->loadResult();
        if ($chargeid > 0) {
            $obj->id = $chargeid;
            $db->updateObject("muusa_charge", $obj, "id");
        } else {
            $db->insertObject("muusa_charge", $obj);
        }
        if ($db->getErrorNum()) {
            JError::raiseError(500, $db->stderr());
        }
    }

    function insertPrereg ($amount, $camperids, $txn)
    {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $campers = explode("|", $camperids);
        foreach ($campers as $camper) {
            $data = explode("-", $camper);
            $amount -= $data[1];
            if ($amount < 0) {
                break;
            }
            
            $obj = new stdClass();
            $obj->camperid = $data[0];
            $obj->chargetypeid = 1022;
            $obj->amount = $data[1] * -1;
            $obj->timestamp = date("Y-m-d");
            $obj->memo = $txn;
            $obj->year = "&&(SELECT MAX(year) FROM muusa_year)";
            $obj->created_by = "prereg";
            $obj->created_at = date("Y-m-d H:i:s");
            $query = "SELECT id FROM muusa_charge WHERE chargetypeid=1022 AND timestamp='$obj->timestamp' AND amount=-$data[1] AND camperid=$data[0]";
            $db->setQuery($query);
            $chargeid = $db->loadResult();
            if ($chargeid > 0) {
                $obj->id = $chargeid;
                $db->updateObject("muusa_charge", $obj, "id");
            } else {
                $db->insertObject("muusa_charge", $obj);
            }
            if ($db->getErrorNum()) {
                JError::raiseError(500, $db->stderr());
            }
        }
    }
}
