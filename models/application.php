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
class muusla_applicationModelapplication extends JModelItem
{

   // DATA FUNCTIONS

   function getChargetypes() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name FROM muusa_chargetype WHERE is_shown=1 ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getChurches() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name, city, statecd FROM muusa_church ORDER BY statecd, city, name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getFoodoptions() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name FROM muusa_foodoption ORDER BY id";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPhonetypes() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name FROM muusa_phonetype";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getVolunteerPositions() {
      $db = JFactory::getDBO();
      $query = "SELECT vp.id, vp.name FROM muusa_volunteerposition vp, muusa_year y WHERE vp.start_year<=y.year AND vp.end_year>y.year ORDER BY name";
      $db->setQuery($query);
      return $db->loadAssocList("id");
   }

   function getPrograms() {
      $db = JFactory::getDBO();
      $query = "SELECT p.name, p.agemax, p.agemin, p.grademax, p.grademin, p.registration_fee FROM muusa_program p, muusa_year y WHERE y.year>=p.start_year AND y.year<=p.end_year AND y.is_current=1";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getStates() {
      $db = JFactory::getDBO();
      $query = "SELECT code, name FROM muusa_statecode ORDER BY name";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getYear() {
      $db = JFactory::getDBO();
      $query = "SELECT DATE_FORMAT(date, '%M %d, %Y') date, DATE_FORMAT(date, '%Y%M%d') startdate, year FROM muusa_year WHERE is_current=1";
      $db->setQuery($query);
      return $db->loadAssoc();
   }

   function getTimes() {
      $db = JFactory::getDBO();
      $query = "SELECT id, name, TIME_FORMAT(starttime, '%l:%i %p') start, TIME_FORMAT(ADDTIME(starttime, CONCAT(REPLACE(TRUNCATE(length, 1), '.', ':'), '0:00')), '%l:%i %p') end FROM muusa_timeslot ORDER BY display_order";
      $db->setQuery($query);
      return $db->loadAssocList("id");
   }

   function getWorkshops() {
      $db = JFactory::getDBO();
      $query = "SELECT w.timeslotid, CONCAT(IF(w.su,'S',''),IF(w.m,'M',''),IF(w.t,'Tu',''),IF(w.w,'W',''),IF(w.th,'Th',''),IF(w.f,'F',''),IF(w.sa,'S','')) days, w.id, w.name, w.subname, jc.introtext, IFNULL(w.enrolled/w.capacity,0) enrollment FROM muusa_workshop w LEFT JOIN jml_content jc ON REPLACE(jc.title, '&', '') LIKE CONCAT('%', SUBSTRING(REPLACE(w.name, '&', ''), 6), '%') AND jc.state=1 ORDER BY w.name";
      $db->setQuery($query);
      return $db->loadAssocList("id");
   }

   // CAMPER FUNCTIONS

   function getFamily($where) {
      $db = JFactory::getDBO();
      $query = "SELECT f.id, f.name, f.address1, f.address2, f.city, f.statecd, f.zipcd, f.country FROM muusa_family f, muusa_camper c WHERE f.id=c.familyid AND $where";
      $db->setQuery($query);
      return $db->loadObject();
   }

   function upsertFamily($obj) {
      $db = JFactory::getDBO();
      if($obj->id < 1000) {
         unset($obj->id);
         $query = "SELECT id FROM muusa_family WHERE name='$obj->name' AND address1='$obj->address1' AND city='$obj->city'";
         $db->setQuery($query);
         $familyid = $db->loadResult();
         if($familyid > 0) {
            $obj->id = $familyid;
            $db->updateObject("muusa_family", $obj, "id");
            return $familyid;
         } else {
            $db->insertObject("muusa_family", $obj, "id");
            return $obj->id;
         }
      } else {
         $db->updateObject("muusa_family", $obj, "id");
         return $obj->id;
      }
   }

   function getCampers($familyid) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "SELECT c.id, IFNULL(ya.id,0) yearattendingid, c.firstname, c.lastname, c.sexcd, c.email, DATE_FORMAT(c.birthdate, '%m/%d/%Y') birthday, (muusa_getage(c.birthdate, y.year)+c.gradeoffset) grade, c.sponsor, c.is_handicap, c.foodoptionid, c.churchid, p.name programname, IFNULL(ya.days,-1) days FROM (muusa_camper c, muusa_program p, muusa_year y) LEFT JOIN muusa_yearattending ya ON c.id=ya.camperid AND y.year=ya.year WHERE p.id=muusa_getprogramid(c.id, y.year) AND c.familyid=$familyid AND y.is_current=1 ORDER BY IF(c.email='$user->email',0,1), c.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRegisteredCampersByFamily($where) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "SELECT id, firstname, lastname, yearattendingid, IF(grade<13,0,1) shops, programname FROM muusa_thisyear_camper WHERE $where ORDER BY birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getRegisteredCampersByCamper($where) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "SELECT tc.id, tc.firstname, tc.lastname, tc.yearattendingid, IF(tc.grade<13,0,1) shops, tc.programname FROM muusa_thisyear_camper tc, muusa_camper c WHERE tc.familyid=c.familyid AND $where ORDER BY tc.birthdate";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function upsertCamper($obj) {
      $db = JFactory::getDBO();
      unset($obj->attending);
      $obj->gradeoffset = $obj->grade != "13" ? "&&" . $obj->grade . "-muusa_getage(STR_TO_DATE('$obj->birthdate', '%m/%d/%Y'), (SELECT year FROM muusa_year WHERE is_current=1))" : "-4";
      unset($obj->grade);
      $obj->birthdate = "&&STR_TO_DATE('$obj->birthdate', '%m/%d/%Y')";
      if($obj->id < 1000) {
         unset($obj->id);
         $query = "SELECT id FROM muusa_camper WHERE firstname='$obj->firstname' AND lastname='$obj->lastname' AND email='$obj->email'";
         $db->setQuery($query);
         $camperid = $db->loadResult();
         if($camperid > 0) {
            $obj->id = $camperid;
            $db->updateObject("muusa_camper", $obj, "id");
            return $camperid;
         } else {
            $db->insertObject("muusa_camper", $obj, "id");
            return $obj->id;
         }
      } else {
         $db->updateObject("muusa_camper", $obj, "id");
         return $obj->id;
      }
   }

   function getPhonenumbers($camperid) {
      $db = JFactory::getDBO();
      $query = "SELECT n.id, nt.id phonetypeid, nt.name phonetypename, CONCAT(left(n.phonenbr,3) , '-' , mid(n.phonenbr,4,3) , '-', right(n.phonenbr,4)) phonenbr FROM muusa_phonenumber n, muusa_phonetype nt WHERE n.phonetypeid=nt.id AND n.camperid=$camperid ORDER BY id";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function upsertYearattending($camperid, $days) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $query = "SELECT IFNULL(ya.id, 0), y.year FROM muusa_year y LEFT JOIN muusa_yearattending ya ON ya.year=y.year AND ya.camperid=$camperid WHERE y.is_current=1";
      $db->setQuery($query);
      $yearattending = $db->loadRow();
      if($yearattending[0] == 0) {
         $obj = new stdClass;
         $obj->camperid = $camperid;
         $obj->year = $yearattending[1];
         $obj->days = $days;
         $obj->postmark = date("Y-m-d");
         $obj->created_by = $user->username;
         $db->insertObject("muusa_yearattending", $obj, "id");
         return $obj->id;
      } else {
         $query = "UPDATE muusa_yearattending SET days=$days WHERE id=" . $yearattending[0];
         $db->setQuery($query);
         $db->query();
         return $yearattending[0];
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function deleteYearattending($camperid) {
      $db = JFactory::getDBO();
      $query = "DELETE ya FROM muusa_yearattending ya INNER JOIN muusa_year y ON ya.year=y.year AND y.is_current=1 WHERE camperid=$camperid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function getRoommatepreferences($yearattendingid) {
      $db = JFactory::getDBO();
      $query = "SELECT name FROM muusa_roommatepreference WHERE yearattendingid=$yearattendingid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function deleteRoommatepreferences($yearattendingid) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_roommatepreference WHERE yearattendingid=$yearattendingid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertRoommatepreferences($yearattendingid, $choicenbr, $name) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $obj = new stdClass;
      $obj->yearattendingid = $yearattendingid;
      $obj->choicenbr = $choicenbr;
      $obj->name = $name;
      $obj->created_by = $user->username;
      $db->insertObject("muusa_roommatepreference", $obj);
   }

   function getAttendees($yearattendingid) {
      $db = JFactory::getDBO();
      $query = "SELECT workshopid, is_leader FROM muusa_yearattending__workshop WHERE yearattendingid=$yearattendingid ORDER BY choicenbr";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function deleteAttendees($yearattendingid) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_yearattending__workshop WHERE yearattendingid=$yearattendingid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertAttendee($attendee) {
      $db = JFactory::getDBO();
      $db->insertObject("muusa_yearattending__workshop", $attendee);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function getVolunteers($camperid) {
      $db = JFactory::getDBO();
      $query = "SELECT volunteerpositionid FROM muusa_thisyear_volunteer WHERE camperid=$camperid ORDER BY volunteerpositionname";
      $db->setQuery($query);
      return $db->loadColumn(0);
   }

   function deleteOldPhonenumbers($camperids, $numbers) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_phonenumber WHERE camperid IN ($camperids)";
      if($numbers != "") {
         $query .= " AND id NOT IN ($numbers)";
      }
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function upsertPhonenumber($obj) {
      $phone = array('/\\(/', '/ /', '/\\)/', '/-/', '/_/');
      $db = JFactory::getDBO();
      $obj->phonenbr = preg_replace($phone, "", $obj->phonenbr);
      if($obj->id < 1000) {
         unset($obj->id);
         $query = "SELECT id FROM muusa_phonenumber WHERE camperid=$obj->camperid AND phonenbr=$obj->phonenbr";
         $db->setQuery($query);
         $phonenbrid = $db->loadResult();
         if($phonenbrid > 0) {
            $obj->id = $phonenbrid;
            $db->updateObject("muusa_phonenumber", $obj, "id");
         } else {
            $db->insertObject("muusa_phonenumber", $obj);
         }
      } else {
         $db->updateObject("muusa_phonenumber", $obj, "id");
      }
   }

   function deleteOldVolunteers($yearattendingid) {
      $db = JFactory::getDBO();
      $query = "DELETE FROM muusa_yearattending__volunteer WHERE yearattendingid=$yearattendingid";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function insertVolunteer($volunteer) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      $db->insertObject("muusa_yearattending__volunteer", $volunteer);
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function getCharges($familyid, $table) {
      $db = JFactory::getDBO();
      $query = "SELECT IF(h.chargetypeid IN (1001,1016,1022),0,h.id) id, h.year, h.chargetypeid, h.chargetypename, FORMAT(h.amount,2) amount, DATE_FORMAT(h.timestamp, '%m/%d/%Y') timestamp, h.memo FROM muusa_" . $table . "_charge h WHERE h.familyid=$familyid ORDER BY h.year DESC, h.timestamp, h.chargetypeid, h.camperid";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function upsertCharge($obj, $camperid) {
      $db = JFactory::getDBO();
      $user = JFactory::getUser();
      if($obj->chargetypeid != "delete") {
         if($obj->id < 1000) {
            $query = "SELECT IFNULL(h.id, 0), y.year FROM muusa_year y LEFT JOIN muusa_charge h ON h.camperid=$camperid AND h.timestamp=STR_TO_DATE('$obj->timestamp', '%m/%d/%Y') AND h.amount=$obj->amount AND h.chargetypeid=$obj->chargetypeid AND h.memo='$obj->memo' WHERE y.is_current=1";
            $obj->camperid = $camperid;
            $obj->amount = preg_replace("/,/", "", $obj->amount);
            $obj->timestamp = "&&STR_TO_DATE('$obj->timestamp', '%m/%d/%Y')";
            $db->setQuery($query);
            $charge = $db->loadRow();
            $obj->year = $charge[1];
            if($charge[0] > 0) {
               $obj->id = $charge[0];
               $db->updateObject("muusa_charge", $obj, "id");
            } else {
               unset($obj->id);
               $db->insertObject("muusa_charge", $obj);
            }
         } else {
            $obj->camperid = $camperid;
            $obj->amount = preg_replace("/,/", "", $obj->amount);
            $obj->timestamp = "&&STR_TO_DATE('$obj->timestamp', '%m/%d/%Y')";
            $db->updateObject("muusa_charge", $obj, "id");
         }
      } else {
         $query = "DELETE FROM muusa_charge WHERE id=$obj->id";
         $db->setQuery($query);
         $db->query();
      }
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

   function callTrigger($familyid) {
      $db = JFactory::getDBO();
      $query = "UPDATE (muusa_charge h, muusa_camper c) SET h.created_at=CURRENT_TIMESTAMP WHERE h.camperid=c.id AND c.familyid=$familyid AND h.chargetypeid IN (1001,1016,1022)";
      $db->setQuery($query);
      $db->query();
   }

   function refresh() {
      $db = JFactory::getDBO();
      $query = "CALL muusa_generate_charges";
      $db->setQuery($query);
      $db->query();
   }
}