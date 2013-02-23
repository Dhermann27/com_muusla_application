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
      return $db->loadAssocList("positionid");
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
      $query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.sexcd, mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthday, (muusa_age_f(mc.birthdate)+gradeoffset) grade, mc.sponsor, mc.is_handicap, mc.smokingoptionid, mc.foodoptionid, mc.churchid, mp.name programname FROM muusa_campers mc LEFT JOIN muusa_programs mp ON muusa_age_f(mc.birthdate)<mp.agemax AND muusa_age_f(mc.birthdate)>mp.agemin AND muusa_age_f(mc.birthdate)+mc.gradeoffset<mp.grademax AND muusa_age_f(mc.birthdate)+mc.gradeoffset>mp.grademin WHERE mc.familyid=$familyid ORDER BY mc.birthdate";
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

   function deleteRoomtypepreferences($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_roomtype_preferences WHERE fiscalyearid=$fiscalyearid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertRoomtypepreferences($fiscalyearid, $choicenbr, $buildingid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $obj = new stdClass;
      $obj->fiscalyearid = $fiscalyearid;
      $obj->choicenbr = $choicenbr;
      $obj->buildingid = $buildingid;
      $obj->created_by = $user->username;
      $obj->created_at = "&&CURRENT_TIMESTAMP";
      $db->insertObject("muusa_roomtype_preferences", $obj);
   }

   function getRoommatepreferences($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "SELECT name FROM muusa_roommate_preferences WHERE fiscalyearid=$fiscalyearid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function deleteRoommatepreferences($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_roommate_preferences WHERE fiscalyearid=$fiscalyearid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertRoommatepreferences($fiscalyearid, $choicenbr, $name) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $obj = new stdClass;
      $obj->fiscalyearid = $fiscalyearid;
      $obj->choicenbr = $choicenbr;
      $obj->name = $name;
      $obj->created_by = $user->username;
      $obj->created_at = "&&CURRENT_TIMESTAMP";
      $db->insertObject("muusa_roommate_preferences", $obj);
   }

   function getAttendees($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "SELECT eventid FROM muusa_attendees WHERE fiscalyearid=$fiscalyearid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function deleteAttendees($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_attendees WHERE fiscalyearid=$fiscalyearid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertAttendees($fiscalyearid, $choicenbr, $timeid, $eventid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $obj = new stdClass;
      $obj->eventid = $eventid;
      $obj->timeid = $timeid;
      $obj->fiscalyearid = $fiscalyearid;
      $obj->choicenbr = $choicenbr;
      $obj->created_by = $user->username;
      $obj->created_at = date("Y-m-d H:i:s");
      $db->insertObject("muusa_attendees", $obj);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function getVolunteers($camperid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT positionid FROM muusa_volunteers_v WHERE camperid=$camperid ORDER BY name";
      $db->setQuery($query);
      return $db->loadColumn(0);
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
      $obj->gradeoffset = $obj->grade < 13 ? "&&$obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'))" : "-5";
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

   function deleteOldVolunteers($fiscalyearid, $numbers) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_volunteers WHERE fiscalyearid IN ($fiscalyearid)";
      if($numbers != "") {
         $query .= " AND positionid NOT IN ($numbers)";
      }
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertVolunteers($fiscalyearid, $positionids) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "INSERT INTO muusa_volunteers (fiscalyearid, positionid, created_by, created_at) ";
      $query .= "SELECT $fiscalyearid, positionid, '$user->username', CURRENT_TIMESTAMP FROM muusa_positions ";
      $query .= "WHERE positionid NOT IN (SELECT positionid FROM muusa_volunteers WHERE fiscalyearid=$fiscalyearid) ";
      $query .= "AND positionid IN ($positionids)";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
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
      $query = "SELECT timeid, name, TIME_FORMAT(starttime, '%l:%i %p') start, TIME_FORMAT(ADDTIME(starttime, CONCAT(REPLACE(TRUNCATE(length, 1), '.', ':'), '0:00')), '%l:%i %p') end FROM muusa_times ORDER BY display_order";
      $db->setQuery($query);
      return $db->loadAssocList("timeid");
   }

   function getWorkshops() {
      $db =& JFactory::getDBO();
      $query = "SELECT timeid, CONCAT(IF(su,'S',''),IF(m,'M',''),IF(t,'Tu',''),IF(w,'W',''),IF(th,'Th',''),IF(f,'F',''),IF(sa,'S','')) days, eventid, name FROM muusa_events ORDER BY name";
      $db->setQuery($query);
      return $db->loadAssocList("eventid");
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

   function calculateCharges($familyid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mc.camperid, mc.firstname, mc.lastname, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthdate, muusa_age_f(mc.birthdate) age, mc.gradeoffset, IFNULL(mv.roomid,0) roomid FROM muusa_campers mc LEFT JOIN muusa_campers_v mv ON mc.camperid=mv.camperid WHERE mc.familyid=$familyid";
      $db->setQuery($query);
      $campers = $db->loadObjectList();
      $camperids = $db->loadColumn(0);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }

      if(count($campers) > 0) {
         $query = "DELETE FROM muusa_charges WHERE fiscalyear=(SELECT year FROM muusa_currentyear) AND camperid IN (" . implode(",", $camperids) . ") AND chargetypeid IN (1003,1004)";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
            JError::raiseError(500, $db->stderr());
         }

         $housingdepo = 0;

         foreach($campers as $camper) {
            $obj = new stdClass;
            $obj->camperid = $camper->camperid;
            $obj->amount = "&&muusa_programs_fee_f(STR_TO_DATE('$camper->birthdate', '%m/%d/%Y'), $camper->gradeoffset)";
            $obj->memo = $camper->firstname . " " . $camper->lastname;
            $obj->chargetypeid = "1003";
            $obj->timestamp = date("Y-m-d");
            $obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
            $obj->created_by = $user->username;
            $obj->created_at = date("Y-m-d H:i:s");
            $db->insertObject("muusa_charges", $obj);
            if($db->getErrorNum()) {
               JError::raiseError(500, $db->stderr());
            }
            if($camper->roomid == 0 && ($camper->age > 16 || ($camper->age+$camper->gradeoffset) > 6)) {
               $housingdepo += 50;
            }
         }

         if($housingdepo > 0) {
            $obj = new stdClass;
            $obj->camperid = $campers[0]->camperid;
            $obj->amount = $housingdepo;
            $obj->memo = "Housing Deposit";
            $obj->chargetypeid = "1004";
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
   }
    
   function insertDonation($camperid, $amt) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $obj = new stdClass;
      $obj->camperid = $camperid;
      $obj->amount = $amt;
      $obj->memo = "Thank you for your donation";
      $obj->chargetypeid = "1008";
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