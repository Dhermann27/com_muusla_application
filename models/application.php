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
      return $db->loadAssocList("buildingid");
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

   function getCampers($familyid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT camperid, firstname, lastname, sexcd, email, DATE_FORMAT(birthdate, '%m/%d/%Y') birthday, (muusa_age_f(birthdate)+gradeoffset) grade, sponsor, is_handicap, smokingoptionid, foodoptionid, churchid FROM muusa_campers WHERE familyid=$familyid ORDER BY birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPhonenumbers($camperid) {
      $db =& JFactory::getDBO();
      $query = "SELECT mp.phonenbrid, mt.phonetypeid phonetypeid, mt.name phonetypename, CONCAT(left(mp.phonenbr,3) , '-' , mid(mp.phonenbr,4,3) , '-', right(mp.phonenbr,4)) phonenbr FROM muusa_phonenumbers mp, muusa_phonetypes mt WHERE mp.phonetypeid=mt.phonetypeid AND camperid=$camperid ORDER BY phonenbrid";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getFiscalyear($camperid) {
      $db =& JFactory::getDBO();
      $query = "SELECT mf.fiscalyearid FROM muusa_fiscalyear mf, muusa_currentyear my WHERE mf.fiscalyear=my.year AND mf.camperid=$camperid";
      $db->setQuery($query);
      return $db->loadResult();
   }

   function getRoomtypepreferences($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "SELECT buildingid FROM muusa_roomtype_preferences WHERE fiscalyearid=$fiscalyearid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function getRoommatepreferences($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "SELECT name FROM muusa_roommate_preferences WHERE fiscalyearid=$fiscalyearid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function getAttendees($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "SELECT eventid FROM muusa_attendees WHERE fiscalyearid=$fiscalyearid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function getVolunteers() {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mv.camperid, mv.positionid FROM muusa_volunteers_v mv WHERE mv.camperid IN (SELECT camperid FROM muusa_campers WHERE email='" . $user->email . "' OR hohid IN (SELECT camperid FROM muusa_campers WHERE email='" . $user->email . "'))";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function upsertFamily($obj) {
      $db =& JFactory::getDBO();
      if($obj->familyid < 1000) {
         unset($obj->familyid);
         $db->insertObject("muusa_family", $obj, "familyid");
         return $db->insertid();
      } else {
         $db->updateObject("muusa_family", $obj, "familyid");
         return $obj->familyid;
      }
   }

   function upsertCamper($obj) {
      $db =& JFactory::getDBO();
      unset($obj->attending);
      $obj->gradeoffset = $obj->grade < 13 ? "&&$obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')')" : "-5";
      unset($obj->grade);
      $obj->birthdate = "&&STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')";
      if($obj->camperid < 1000) {
         unset($obj->camperid);
         $db->insertObject("muusa_campers", $obj, "camperid");
         return $db->insertid();
      } else {
         $db->updateObject("muusa_campers", $obj, "camperid");
         return $obj->camperid;
      }
   }

   function upsertFiscalyear($camperid, $attending) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      if($attending > 0) {
         $query = "SELECT mf.fiscalyearid FROM muusa_fiscalyear mf, muusa_currentyear my WHERE mf.fiscalyear=my.year AND mf.camperid=$camperid";
         $db->setQuery($query);
         $fiscalyearid = $db->loadResult();
         if(!$fiscalyearid) {
            $fyobj = new stdClass;
            $fyobj->camperid = $camperid;
            $fyobj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
            $fyobj->days = 6;
            $fyobj->postmark = "&&CURRENT_TIMESTAMP";
            $fyobj->created_by = $user->username;
            $fyobj->created_at = "&&CURRENT_TIMESTAMP";
            $db->insertObject("muusa_fiscalyear", $fyobj);
            $fiscalyearid = $db->insertid();
         }
         return $fiscalyearid;
         /*} else {
          $query = "DELETE FROM muusa_fiscalyear WHERE camperid=$camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
         }

         $query = "DELETE FROM muusa_charges WHERE camperid=$camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
         }

         $query = "DELETE FROM muusa_volunteers WHERE camperid=$camperid AND fiscalyear=(SELECT year FROM muusa_currentyear)";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
         }

         $query = "DELETE FROM muusa_attendees WHERE camperid=$camperid";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
         }
         return 0;*/
      }
   }

   function deleteOldPhonenumbers($camperids, $numbers) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_phonenumbers WHERE camperid IN ($camperids) AND phonenbrid NOT IN ($numbers)";
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
      $query = "SELECT mf.familyid, mf.familyname, mf.address1, mf.address2, mf.city, mf.statecd, mf.zipcd, mf.country FROM muusa_family mf, muusa_campers mc WHERE mf.familyid=mc.familyid AND mc.email='" . $user->email . "'";
      $db->setQuery($query);
      return $db->loadObject();
   }

   function getTimes() {
      $db =& JFactory::getDBO();
      $query = "SELECT timeid, name, TIME_FORMAT(starttime, '%l:%i %p'), TIME_FORMAT(ADDTIME(starttime, CONCAT(REPLACE(TRUNCATE(length, 1), '.', ':'), '0:00')), '%l:%i %p') FROM muusa_times ORDER BY display_order";
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

   function getCharges($familyid) {
      $db =& JFactory::getDBO();
      $query = "SELECT mc.camperid, mt.name, FORMAT(mc.amount,2) amount, DATE_FORMAT(mc.timestamp, '%m/%d/%Y') timestamp, mc.chargetypeid, mc.memo FROM muusa_charges_v mc, muusa_chargetypes mt WHERE mc.chargetypeid=mt.chargetypeid AND mc.familyid=$familyid ORDER BY mc.timestamp, mc.chargetypeid, mc.camperid";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getCredits($familyid) {
      $db =& JFactory::getDBO();
      $query = "SELECT camperid, positionname, registration_amount, housing_amount FROM muusa_credits_v WHERE familyid=$familyid";
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