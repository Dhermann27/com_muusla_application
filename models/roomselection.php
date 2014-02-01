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

class muusla_applicationModelroomselection extends JModel
{

   function getYear() {
      $db =& JFactory::getDBO();
      $query = "SELECT year, IF(DATEDIFF(DATE_ADD(open, INTERVAL 30 DAY), NOW())>0,1,0) is_priorityreg FROM muusa_year WHERE is_current=1";
      $db->setQuery($query);
      return $db->loadRow();
   }

   function getRooms() {
      $db =& JFactory::getDBO();
      $query = "SELECT r.id, b.id buildingid, b.name buildingname, r.roomnbr, r.is_handicap, r.capacity, r.xcoord, r.ycoord, r.pixelsize, rp.roomnbr connected, (SELECT IF(ya.is_private=1,'1',GROUP_CONCAT(c.firstname, ' ', c.lastname SEPARATOR '<br />')) FROM muusa_camper c, muusa_yearattending ya, muusa_year y WHERE c.id=ya.camperid AND r.id=ya.roomid AND y.year=ya.year AND y.is_current=1) AS occupants FROM muusa_building b, muusa_room r LEFT JOIN muusa_room rp ON r.connected_with=rp.id WHERE b.id=r.buildingid AND r.xcoord>0 AND r.ycoord>0";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPriorityRooms() {
      $db =& JFactory::getDBO();
      $query = "SELECT r.id, b.id buildingid, b.name buildingname, r.roomnbr, r.is_handicap, r.capacity, r.xcoord, r.ycoord, r.pixelsize, rp.roomnbr connected, (SELECT IF(ya.roomid!=0,'1',GROUP_CONCAT(c.firstname, ' ', c.lastname SEPARATOR '<br />')) FROM muusa_camper c, muusa_yearattending ya, muusa_year y WHERE c.id=ya.camperid AND r.id=ya.roomid AND y.year-1=ya.year AND muusa_isprereg(c.id, y.year)>0 AND y.is_current=1) AS occupants FROM muusa_building b, muusa_room r LEFT JOIN muusa_room rp ON r.connected_with=rp.id WHERE b.id=r.buildingid AND r.xcoord>0 AND r.ycoord>0";
      //       $query = "SELECT r.id, b.id buildingid, b.name buildingname, r.roomnbr, r.is_handicap, r.capacity, r.xcoord, r.ycoord, r.pixelsize, rp.roomnbr connected, (SELECT IF(ya.is_private=1,'1',GROUP_CONCAT(c.firstname, ' ', c.lastname SEPARATOR '<br />')) FROM muusa_camper c, muusa_yearattending ya, muusa_year y WHERE c.id=ya.camperid AND r.id=ya.roomid AND y.year-1=ya.year AND muusa_isprereg(c.id, y.year)>0 AND y.is_current=1) AS occupants FROM muusa_building b, muusa_room r LEFT JOIN muusa_room rp ON r.connected_with=rp.id WHERE b.id=r.buildingid AND r.xcoord>0 AND r.ycoord>0";
      $db->setQuery($query);
      return $db->loadObjectList();
   }

   function getPreregistered() {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT muusa_isprereg(tc.id, y.year), IF(tc.roomid!=0,tc.roomid,ya.roomid) FROM muusa_thisyear_camper tc, muusa_yearattending ya, muusa_year y WHERE tc.email='$user->email' AND tc.id=ya.camperid AND y.year-1=ya.year AND y.is_current=1";
      $db->setQuery($query);
      return $db->loadRow();
   }

   function getRegistered() {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT 0, tc.roomid FROM muusa_thisyear_camper tc WHERE tc.email='$user->email'";
      $db->setQuery($query);
      return $db->loadRow();
   }

   function updateYearattending($roomid, $isprivate) {
      $db =& JFactory::getDBO();
      $user =& JFactory::getUser();
      $query = "SELECT GROUP_CONCAT(tc.yearattendingid) FROM muusa_thisyear_camper tc, muusa_camper c WHERE c.email='$user->email' AND c.familyid=tc.familyid AND tc.programid IN (1000,1002,1005,1007)";
      $db->setQuery($query);
      $query = "UPDATE muusa_yearattending ya SET ya.roomid=$roomid, ya.is_private=$isprivate WHERE ya.id IN (" . $db->loadResult() . ")";
      $db->setQuery($query);
      $db->query();
      if($db->getErrorNum()) {
         JError::raiseError(500, $db->stderr());
      }
   }

}