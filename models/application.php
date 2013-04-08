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

   // DATA FUNCTIONS

   function getBuildings() {
      $db =& JFactory::getDBO();
      $query = "SELECT buildingid, name, introtext FROM muusa_buildings LEFT JOIN jml_content ON title LIKE CONCAT('%', name, '%') ORDER BY name";
      $db->setQuery($query);
      return $db->loadAssocList("buildingid");
   }

   function getChargetypes() {
      $db =& JFactory::getDBO();
      $query = "SELECT chargetypeid, name FROM muusa_chargetypes ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getChurches() {
      $db =& JFactory::getDBO();
      $query = "SELECT churchid, name, city, statecd FROM muusa_churches ORDER BY statecd, city, name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getFoodoptions() {
      $db =& JFactory::getDBO();
      $query = "SELECT foodoptionid, name FROM muusa_foodoptions ORDER BY foodoptionid";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPhonetypes() {
      $db =& JFactory::getDBO();
      $query = "SELECT phonetypeid, name FROM muusa_phonetypes";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPositions($isshown) {
      $db =& JFactory::getDBO();
      $query = "SELECT positionid, name FROM muusa_positions WHERE is_shown=$isshown ORDER BY name";
      $db->setQuery($query);
      return $db->loadAssocList("positionid");
   }

   function getPrograms() {
      $db =& JFactory::getDBO();
      $query = "SELECT name, agemax, agemin, grademax, grademin, registration_fee FROM muusa_programs";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getStates() {
      $db =& JFactory::getDBO();
      $query = "SELECT statecd, name FROM muusa_states ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getYear() {
      $db =& JFactory::getDBO();
      $query = "SELECT DATE_FORMAT(date, '%M %d, %Y') date, year FROM muusa_currentyear";
      $db->setQuery($query);
      return $db->loadAssoc();
   }

   function getTimes() {
      $db =& JFactory::getDBO();
      $query = "SELECT timeid, name, TIME_FORMAT(starttime, '%l:%i %p') start, TIME_FORMAT(ADDTIME(starttime, CONCAT(REPLACE(TRUNCATE(length, 1), '.', ':'), '0:00')), '%l:%i %p') end FROM muusa_times ORDER BY display_order";
      $db->setQuery($query);
      return $db->loadAssocList("timeid");
   }

   function getWorkshops() {
      $db =& JFactory::getDBO();
      $query = "SELECT timeid, CONCAT(IF(su,'S',''),IF(m,'M',''),IF(t,'Tu',''),IF(w,'W',''),IF(th,'Th',''),IF(f,'F',''),IF(sa,'S','')) days, eventid, name, introtext FROM muusa_events LEFT JOIN jml_content ON REPLACE(title, '&', '') LIKE CONCAT('%', REPLACE(name, '&', ''), '%') AND STATE=1 ORDER BY name";
      $db->setQuery($query);
      return $db->loadAssocList("eventid");
   }

   // CAMPER FUNCTIONS

   function getFamily($where) {
      $db =& JFactory::getDBO();
      $query = "SELECT mf.familyid, mf.familyname, mf.address1, mf.address2, mf.city, mf.statecd, mf.zipcd, mf.country FROM muusa_family mf, muusa_campers mc WHERE mf.familyid=mc.familyid AND $where";
      $db->setQuery($query);
      return $db->loadObject();
   }

   function upsertFamily($obj) {
      $db =& JFactory::getDBO();
      if($obj->familyid < 1000) {
         unset($obj->familyid);
         $query = "SELECT familyid FROM muusa_family WHERE familyname='$obj->familyname' AND address1='$obj->address1' AND city='$obj->city'";
         $db->setQuery($query);
         $familyid = $db->loadResult();
         if($familyid > 0) {
            $obj->familyid = $familyid;
            $db->updateObject("muusa_family", $obj, "familyid");
            return $familyid;
         } else {
            $db->insertObject("muusa_family", $obj, "familyid");
            return $obj->familyid;
         }
      } else {
         $db->updateObject("muusa_family", $obj, "familyid");
         return $obj->familyid;
      }
   }

   function getCampers($familyid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.sexcd, mc.email, DATE_FORMAT(mc.birthdate, '%m/%d/%Y') birthday, (muusa_age_f(mc.birthdate)+mc.gradeoffset) grade, mc.sponsor, mc.is_handicap, mc.foodoptionid, mc.churchid, mp.name programname, IFNULL(mv.days,0) days, IFNULL(mv.postmark,'') postmark FROM muusa_campers mc LEFT JOIN muusa_campers_v mv ON mc.camperid=mv.camperid LEFT JOIN muusa_programs mp ON muusa_age_f(mc.birthdate)<mp.agemax AND muusa_age_f(mc.birthdate)>mp.agemin AND muusa_age_f(mc.birthdate)+mc.gradeoffset<mp.grademax AND muusa_age_f(mc.birthdate)+mc.gradeoffset>mp.grademin WHERE mc.familyid=$familyid ORDER BY IF(mc.email='$user->email',0,1), mc.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRegisteredCampersByFamily($where) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mc.camperid, mc.firstname, mc.lastname, mc.fiscalyearid, IF(mc.grade<13,0,1) shops, mc.programname FROM muusa_campers_v mc, muusa_family_v mf WHERE mf.familyid=mc.familyid AND $where";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRegisteredCampersByCamper($where) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mcc.camperid, mcc.firstname, mcc.lastname, mcc.fiscalyearid, IF(mcc.grade<13,0,1) shops, mcc.programname FROM muusa_campers mc, muusa_family_v mf, muusa_campers_v mcc  WHERE mc.familyid=mf.familyid AND mf.familyid=mcc.familyid AND $where";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function upsertCamper($obj) {
      $db =& JFactory::getDBO();
      unset($obj->attending);
      $obj->gradeoffset = $obj->grade < 13 ? "&&$obj->grade-muusa_age_f(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'))" : "-5";
      unset($obj->grade);
      $obj->birthdate = "&&STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')";
      if($obj->camperid < 1000) {
         unset($obj->camperid);
         $query = "SELECT camperid FROM muusa_campers WHERE firstname='$obj->firstname' AND lastname='$obj->lastname' AND email='$obj->email'";
         $db->setQuery($query);
         $camperid = $db->loadResult();
         if($camperid > 0) {
            $obj->camperid = $camperid;
            $db->updateObject("muusa_campers", $obj, "camperid");
            return $camperid;
         } else {
            $db->insertObject("muusa_campers", $obj, "camperid");
            return $obj->camperid;
         }
      } else {
         $db->updateObject("muusa_campers", $obj, "camperid");
         return $obj->camperid;
      }
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

   function upsertFiscalyear($camperid, $postmark) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT mf.fiscalyearid FROM muusa_fiscalyear mf, muusa_currentyear my WHERE mf.fiscalyear=my.year AND mf.camperid=$camperid";
      $db->setQuery($query);
      $fiscalyearid = $db->loadResult();
      if(!$fiscalyearid) {
         $obj = new stdClass;
         $obj->camperid = $camperid;
         $obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
         $obj->days = 6;
         $obj->postmark = $postmark != "" ? "&&STR_TO_DATE('$postmark', '%m/%d/%Y')" : "&&CURRENT_TIMESTAMP";
         $obj->created_by = $user->username;
         $obj->created_at = "&&CURRENT_TIMESTAMP";
         $db->insertObject("muusa_fiscalyear", $obj, "fiscalyearid");
         $fiscalyearid = $obj->fiscalyearid;
      } else {
         $obj = new stdClass;
         $obj->fiscalyearid = $fiscalyearid;
         if ($postmark != "") {
            $obj->postmark =  "&&STR_TO_DATE('$postmark', '%m/%d/%Y')";
         }
         $obj->modified_by = $user->username;
         $obj->modified_at = "&&CURRENT_TIMESTAMP";
         $db->updateObject("muusa_fiscalyear", $obj, "fiscalyearid");
      }
      return $fiscalyearid;
   }

   function removeFiscalyear($camperid) {
      $db =& JFactory::getDBO();
      $query = "SELECT mf.fiscalyearid FROM muusa_fiscalyear mf, muusa_currentyear my WHERE mf.fiscalyear=my.year AND mf.camperid=$camperid";
      $db->setQuery($query);
      $fiscalyearid = $db->loadResult();

      if($fiscalyearid > 0) {
         $query = "DELETE FROM muusa_fiscalyear WHERE fiscalyearid=$fiscalyearid";
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

         $query = "DELETE FROM muusa_volunteers WHERE fiscalyearid=$fiscalyearid";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
            JError::raiseError(500, $db->stderr());
         }

         $query = "DELETE FROM muusa_attendees WHERE fiscalyearid=$fiscalyearid";
         $db->setQuery($query);
         $db->query();
         if($db->getErrorNum()) {
            JError::raiseError(500, $db->stderr());

         }
         $this->deleteRoomtypepreferences($fiscalyearid);
         $this->deleteRoommatepreferences($fiscalyearid);
      }
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

   function insertAttendee($attendee) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $attendee->created_by = $user->username;
      $attendee->created_at = date("Y-m-d H:i:s");
      $db->insertObject("muusa_attendees", $attendee);
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

   function getStaff($camperid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT positionid FROM muusa_positions_v WHERE camperid=$camperid ORDER BY positionname";
      $db->setQuery($query);
      return $db->loadColumn(0);
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
         $query = "SELECT phonenbrid FROM muusa_phonenumbers WHERE camperid=$obj->camperid AND phonenbr=$obj->phonenbr";
         $db->setQuery($query);
         $phonenbrid = $db->loadResult();
         if($phonenbrid > 0) {
            $obj->phonenbrid = $phonenbrid;
            $obj->modified_by = $user->username;
            $obj->modified_at = "&&CURRENT_TIMESTAMP";
            $db->updateObject("muusa_campers", $obj, "phonenbrid");
         } else {
            $obj->created_by = $user->username;
            $obj->created_at = "&&CURRENT_TIMESTAMP";
            $db->insertObject("muusa_phonenumbers", $obj);
         }
      } else {
         $obj->modified_by = $user->username;
         $obj->modified_at = "&&CURRENT_TIMESTAMP";
         $db->updateObject("muusa_phonenumbers", $obj, "phonenbrid");
      }
   }

   function deleteOldVolunteers($fiscalyearid) {
      $db =& JFactory::getDBO();
      $query = "DELETE FROM muusa_volunteers WHERE fiscalyearid=$fiscalyearid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertVolunteer($volunteer) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $volunteer->created_by = $user->username;
      $volunteer->created_at = date("Y-m-d H:i:s");
      $db->insertObject("muusa_volunteers", $volunteer);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function getCharges($familyid, $where) {
      $db =& JFactory::getDBO();
      $query = "SELECT mc.chargeid, mc.fiscalyear, mc.chargetypeid, mt.name, FORMAT(mc.amount,2) amount, DATE_FORMAT(mc.timestamp, '%m/%d/%Y') timestamp, mc.chargetypeid, mc.memo FROM muusa_campers ma, muusa_charges mc, muusa_chargetypes mt WHERE mc.chargetypeid=mt.chargetypeid AND mc.camperid=ma.camperid AND ma.familyid=$familyid $where ORDER BY mc.fiscalyear DESC, mc.timestamp, mc.chargetypeid, mc.camperid";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getCredits($familyid, $where) {
      $db =& JFactory::getDBO();
      $query = "SELECT 	mf.fiscalyear, IF(COUNT(mp.housing_amount)=1,mp.name,'Multiple Credits') positionname, ";
      $query .= "LEAST(mr.amount, SUM(mp.registration_amount)) registration_amount, IF(mh.amount>0, LEAST(mh.amount,SUM(mp.housing_amount)), LEAST(50,SUM(mp.housing_amount))) housing_amount ";
      $query .= "FROM (muusa_campers mc, muusa_fiscalyear mf, muusa_volunteers mv, muusa_positions mp) ";
      $query .= "LEFT JOIN muusa_charges mr ON mc.camperid=mr.camperid AND mf.fiscalyear=mr.fiscalyear AND mr.chargetypeid=1003 ";
      $query .= "LEFT JOIN muusa_charges mh ON mc.camperid=mh.camperid AND mf.fiscalyear=mh.fiscalyear AND mh.chargetypeid=1000 ";
      $query .= "WHERE  mc.camperid=mf.camperid AND mv.fiscalyearid=mf.fiscalyearid AND mc.camperid=mf.camperid AND mv.positionid=mp.positionid AND mc.familyid=$familyid $where ";
      $query .= "AND mf.fiscalyear>=mp.start_year AND mf.fiscalyear<=mp.end_year AND (mp.housing_amount>0 OR mp.registration_amount>0) GROUP BY mf.fiscalyear, mf.camperid ORDER BY mc.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function calculateCharges($familyid) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT camperid, firstname, lastname, birthdate, age, gradeoffset, IFNULL(roomid,0) roomid FROM muusa_campers_v WHERE familyid=$familyid";
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

   function upsertCharge($obj) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      if($obj->chargetypeid != "delete") {
         $query = "SELECT chargeid FROM muusa_charges WHERE fiscalyear=$obj->fiscalyear AND timestamp='$obj->timestamp' AND amount=$obj->amount AND chargetypeid=$obj->chargetypeid AND camperid=$obj->camperid";
         $obj->amount = preg_replace("/,/", "", $obj->amount);
         $obj->fiscalyear = "&&(SELECT year FROM muusa_currentyear)";
         $obj->timestamp = "&&STR_TO_DATE('$obj->timestamp', '%m/%d/%Y')";
         if($obj->chargeid < 1000) {
            $db->setQuery($query);
            $chargeid = $db->loadResult();
            if($chargeid > 0) {
               $obj->chargeid = $chargeid;
               $obj->modified_by = $user->username;
               $obj->modified_at = "&&CURRENT_TIMESTAMP";
               $db->updateObject("muusa_charges", $obj, "chargeid");
            } else {
               $db->insertObject("muusa_charges", $obj);
            }
         } else {
            $obj->modified_by = $user->username;
            $obj->modified_at = "&&CURRENT_TIMESTAMP";
            $db->updateObject("muusa_charges", $obj, "chargeid");
         }
      } else {
         $query = "DELETE FROM muusa_charges WHERE chargeid=$obj->chargeid";
         $db->setQuery($query);
         $db->query();
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }
}