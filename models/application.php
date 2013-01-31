<?php
/**
 * muusla_application Model for muusla Component
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
class muusla_applicationModelapplication extends JModel
{

	function getPositions() {
		$db =& JFactory::getDBO();
		$query = "SELECT positionid, name FROM muusa_positions WHERE is_shown=1 ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getBuildings() {
		$db =& JFactory::getDBO();
		$query = "SELECT buildingid, name FROM muusa_buildings ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getStates() {
		$db =& JFactory::getDBO();
		$query = "SELECT statecd, name FROM muusa_states ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getFoodoptions() {
		$db =& JFactory::getDBO();
		$query = "SELECT foodoptionid, name FROM muusa_foodoptions ORDER BY foodoptionid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getSmokingoptions() {
		$db =& JFactory::getDBO();
		$query = "SELECT smokingoptionid, name FROM muusa_smokingoptions ORDER BY smokingoptionid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getChurches() {
		$db =& JFactory::getDBO();
		$query = "SELECT churchid, name, city, statecd FROM muusa_churches ORDER BY statecd, city, name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getPrograms() {
		$db =& JFactory::getDBO();
		$query = "SELECT name, agemax, agemin, grademax, grademin, registration_fee FROM muusa_programs";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getYear() {
		$db =& JFactory::getDBO();
		$query = "SELECT DATE_FORMAT(date, '%M %d, %Y') date FROM muusa_currentyear";
		$db->setQuery($query);
		return $db->loadResult();
	}

	function getPhonetypes() {
		$db =& JFactory::getDBO();
		$query = "SELECT phonetypeid, name FROM muusa_phonetypes";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCamper() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT mc.camperid, mc.hohid, mc.firstname, mc.lastname, mc.sexcd, mc.address1, mc.address2, mc.city, mc.statecd, mc.zipcd, mc.country, ";
		$query .= "mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, muusa_age_f(mc.birthdate) age, mc.gradeoffset, ";
		$query .= "mc.roomprefid1, mc.roomprefid2, mc.roomprefid3, mc.matepref1, mc.matepref2, mc.matepref3, mc.sponsor, IF(mc.is_handicap, ' checked', '') is_handicap, IF(mc.is_ymca,' checked', '') is_ymca, IF(mc.is_ecomm,' checked', '') is_ecomm, ";
		$query .= "mc.foodoptionid, mc.churchid, (SELECT COUNT(*) FROM muusa_campers_v mv WHERE mv.camperid=mc.hohid) not_attending FROM muusa_campers mc WHERE mc.email='$user->email'";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getChildren($hohid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.sexcd, mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, mc.gradeoffset, muusa_age_f(mc.birthdate) age, ";
		$query .= "IF(mc.is_handicap, ' checked', '') is_handicap, mc.foodoptionid, IF((SELECT IF(COUNT(*)!=1,0,1) FROM muusa_campers_v mv WHERE mv.camperid=$hohid OR mv.camperid=mc.camperid), ' checked', '') not_attending FROM muusa_campers mc WHERE ";
		$query .= "mc.hohid=$hohid ORDER BY birthdate DESC";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getPhonenumbers($id) {
		$db =& JFactory::getDBO();
		if($id != 0) {
			$query = "SELECT mp.phonenbrid, mt.phonetypeid phonetypeid, mt.name phonetypename, CONCAT(left(mp.phonenbr,3) , '-' , mid(mp.phonenbr,4,3) , '-', right(mp.phonenbr,4)) phonenbr FROM muusa_phonenumbers mp, muusa_phonetypes mt WHERE mp.phonetypeid=mt.phonetypeid AND camperid=$id ORDER BY phonenbrid";
			$db->setQuery($query);
			return $db->loadObjectList();
		} else {
			return array();
		}
	}

	function getVolunteers() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT mv.camperid, mv.positionid FROM muusa_volunteers_v mv WHERE mv.camperid IN (SELECT camperid FROM muusa_campers WHERE email='" . $user->email . "' OR hohid IN (SELECT camperid FROM muusa_campers WHERE email='" . $user->email . "'))";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function upsertCamper($obj) {
		$db =& JFactory::getDBO();

		$obj->gradeoffset = "&&$obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')')";
		$obj->programid = "&&muusa_programs_id_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'), ($obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'))))";
		$obj->birthdate = "&&STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')";
		unset($obj->grade);
		if($obj->camperid < 1000) {
			unset($obj->camperid);
			$db->insertObject("muusa_campers", $obj, "camperid");
		} else {
			$db->updateObject("muusa_campers", $obj, "camperid");
		}

		if(!$obj->notattending) {
			$query = "SELECT COUNT(*) FROM muusa_campers_v WHERE camperid=$obj->camperid";
			$db->setQuery($query);
			if($db->loadResult() == 0) {
				$fyobj = new stdClass;
				$fyobj->camperid = $obj->camperid;
				$fyobj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
				$fyobj->days = 6;
				$fyobj->postmark = "&&CURRENT_TIMESTAMP";
				if($obj->created_by) {
					$fyobj->created_by = $obj->created_by;
					$fyobj->created_at = $obj->created_at;
				} else {
					$fyobj->created_by = $obj->modified_by;
					$fyobj->created_at = $obj->modified_at;
				}
				$db->insertObject("muusa_fiscalyear", $fyobj);
			}
		} else {
			$query = "DELETE FROM muusa_fiscalyear WHERE camperid=$obj->camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$query = "DELETE FROM muusa_charges WHERE camperid=$obj->camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$query = "DELETE FROM muusa_volunteers WHERE camperid=$obj->camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$query = "DELETE FROM muusa_attendees WHERE camperid=$obj->camperid";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}

		return $obj->camperid;
	}

	function deletePhonenumber($id) {
		$db =& JFactory::getDBO();
		$query = "DELETE FROM muusa_phonenumbers WHERE phonenbrid=$id";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}
	}

	function upsertPhonenumber($obj) {
		$phone = array('/\\(/', '/ /', '/\\)/', '/-/');
		$db =& JFactory::getDBO();
		$obj->phonenbr = preg_replace($phone, "", $obj->phonenbr);
		if($obj->phonenbrid < 1000) {
			unset($obj->phonenbrid);
			$db->insertObject("muusa_phonenumbers", $obj);
		} else {
			$db->updateObject("muusa_phonenumbers", $obj, "phonenbrid");
		}
	}

	function upsertVolunteers($id, $positionids) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "DELETE FROM muusa_volunteers WHERE camperid=$id AND positionid IN (SELECT positionid FROM muusa_positions WHERE is_shown=1) AND fiscalyear IN (SELECT year FROM muusa_currentyear)";
		if(is_array($positionids) && count($positionids) > 0) {
			$query .= " AND positionid NOT IN (" . implode(",", $positionids) . ")";
		}
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		if(is_array($positionids) && count($positionids) > 0) {
			$query = "INSERT INTO muusa_volunteers (camperid, positionid, fiscalyear, created_by, created_at) ";
			$query .= "SELECT $id, positionid, (SELECT year FROM muusa_currentyear), '$user->username', CURRENT_TIMESTAMP FROM muusa_positions ";
			$query .= "WHERE positionid NOT IN (SELECT positionid FROM muusa_positions_v WHERE camperid=$id) ";
			$query .= "AND positionid IN (" . implode(",", $positionids) . ")";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}
	}

	function getFamily() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT camperid, CONCAT(firstname, ' ', lastname) fullname, mp.name programname, IF(hohid=0,0,(SELECT IF(COUNT(*)!=1,0,1) FROM muusa_campers_v mv WHERE mv.camperid=mc.hohid OR mv.camperid=mc.camperid)) notattending FROM muusa_campers mc, muusa_programs mp WHERE mc.programid=mp.programid AND (email='" . $user->email . "' OR hohid IN (SELECT camperid FROM muusa_campers WHERE email='" . $user->email . "')) ORDER BY mc.hohid, mc.birthdate";
		$db->setQuery($query);
		$campers = $db->loadObjectList();
		foreach($campers as $camper) {
			$query = "SELECT eventid, choicenbr, is_leader FROM muusa_attendees WHERE camperid=$camper->camperid ORDER BY choicenbr";
			$db->setQuery($query);
			$camper->choices = $db->loadObjectList();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
			if($camper->programname == "Adult" || preg_match("/^Young Adult/", $camper->programname) != 0) {
				$camper->workshop = "0";
			} else {
				$camper->workshop = "Automatically enrolled in " . $camper->programname . " programming.";
			}
		}
		return $campers;
	}
	
   function getTimes() {
      $db =& JFactory::getDBO();
      $query = "SELECT timeid, name, TIME_FORMAT(starttime, '%l:%i %p'), TIME_FORMAT(ADDTIME(starttime, CONCAT(REPLACE(TRUNCATE(length, 1), '.', ':'), '0:00')), '%l:%i %p') FROM muusa_times ORDER BY starttime";
      $db->setQuery($query);
      return $db->loadAssocList("timeid");
   }

	function getWorkshops() {
		$db =& JFactory::getDBO();
		$query = "SELECT timeid, CONCAT(IF(su,'S',''),IF(m,'M',''),IF(t,'Tu',''),IF(w,'W',''),IF(th,'Th',''),IF(f,'F',''),IF(sa,'S','')) days, eventid, name FROM muusa_events ORDER BY name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function upsertAttendees($camperid, $events) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "DELETE FROM muusa_attendees WHERE camperid=$camperid AND is_leader=0";
		$db->setQuery($query);
		$db->query();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		foreach($events as $event) {
			for($i=0; $i<count($event); $i++) {
				if($event[$i] != "LEAD") {
					$obj = new stdClass;
					$obj->eventid = $event[$i];
					$obj->camperid = $camperid;
					$obj->choicenbr = $i+1;
					$obj->is_leader = 0;
					$obj->created_by = $user->username;
					$obj->created_at = date("Y-m-d H:i:s");
					$db->insertObject("muusa_attendees", $obj);
					if($db->getErrorNum()) {
						JError::raiseError(500, $db->stderr());
					}
				}
			}
		}
	}

	function getCharges($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT mc.camperid camperid, mt.name name, FORMAT(mc.amount,2) amount, mc.timestamp timestamp, mc.chargetypeid chargetypeid, mc.memo memo FROM muusa_charges_v mc, muusa_chargetypes mt WHERE mc.chargetypeid=mt.chargetypeid AND mc.familyid=$camperid ORDER BY mc.timestamp, mc.chargetypeid, mc.camperid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCredits($camperid) {
		$db =& JFactory::getDBO();
		$query = "SELECT camperid, name, registration_amount, housing_amount FROM muusa_credits_v WHERE camperid=$camperid OR hohid=$camperid";
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function calculateCharges() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.hohid, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, muusa_age_f(mc.birthdate) age, mc.gradeoffset, IFNULL(mv.roomid,0) roomid FROM muusa_campers mc LEFT JOIN muusa_campers_v mv ON mc.camperid=mv.camperid WHERE mc.email='$user->email'";
		$db->setQuery($query);
		$camper = $db->loadObject();
		if($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		if($camper->camperid != 0 && $camper->hohid == 0) {
			$query = "DELETE FROM muusa_charges WHERE fiscalyear=(SELECT year FROM muusa_currentyear) AND camperid IN (SELECT camperid FROM muusa_campers_v WHERE camperid=$camper->camperid OR hohid=$camper->camperid) AND chargetypeid IN (SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Registration%' OR name LIKE 'Housing Depo%')";
			$db->setQuery($query);
			$db->query();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$obj = new stdClass;
			$obj->camperid = $camper->camperid;
			$obj->amount = "&&muusa_programs_fee_f(STR_TO_DATE('$camper->birthdate', '%m/%d/%Y'), $camper->gradeoffset)";
			$obj->memo = $camper->firstname . " " . $camper->lastname;
			$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Registration%')";
			$obj->timestamp = date("Y-m-d");
			$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
			$obj->created_by = $user->username;
			$obj->created_at = date("Y-m-d H:i:s");
			$db->insertObject("muusa_charges", $obj);
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
			$housingdepo = 50;

			$query = "SELECT camperid, firstname, lastname, birthdate, age, gradeoffset FROM muusa_campers_v WHERE hohid=$camper->camperid";
			$db->setQuery($query);
			$children = $db->loadObjectList();
			if($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			foreach($children as $child) {
				$obj = new stdClass;
				$obj->camperid = $child->camperid;
				$obj->amount = "&&muusa_programs_fee_f(STR_TO_DATE('$child->birthdate', '%m/%d/%Y'), $child->gradeoffset)";
				$obj->memo = $child->firstname . " " . $child->lastname;
				$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Registration%')";
				$obj->timestamp = date("Y-m-d");
				$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
				$obj->created_by = $user->username;
				$obj->created_at = date("Y-m-d H:i:s");
				$db->insertObject("muusa_charges", $obj);
				if($db->getErrorNum()) {
					JError::raiseError(500, $db->stderr());
				}
				if($child->age > 16 || ($child->age+$child->gradeoffset) > 6) {
					$housingdepo += 50;
				}
			}

			if($camper->roomid == 0) {
				$obj = new stdClass;
				$obj->camperid = $camper->camperid;
				$obj->amount = $housingdepo;
				$obj->memo = "Housing Deposit";
				$obj->chargetypeid = "&&(SELECT chargetypeid FROM muusa_chargetypes WHERE name LIKE 'Housing Depo%')";
				$obj->timestamp = date("Y-m-d");
				$obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
				$obj->created_by = $user->username;
				$obj->created_at = date("Y-m-d H:i:s");
				$db->insertObject("muusa_charges", $obj);
				if($db->getErrorNum()) {
					JError::raiseError(500, $db->stderr());
				}
			}
		}
		return $camper;
	}
}