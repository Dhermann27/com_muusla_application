<?php
/**
 * @package		muusla_application
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_application campers Component
 *
 * @package		muusla_application
 */
class muusla_applicationViewroomselection extends JView
{

   function display($tpl = null) {
      $model =& $this->getModel();
      $user =& JFactory::getUser();
      
      $roomid = $this->getSafe(JRequest::getVar("yearattending-roomid-0"));
      if($roomid != "") {
         $model->updateYearattending($roomid, $this->getSafe(JRequest::getVar("yearattending-is_private-0")));
      }
      
      $year = $model->getYear();
      $this->assignRef('year', $year);
      if($year[1] == "0") {
         $this->assignRef('reg', $model->getPreregistered());
         $this->assignRef('rooms', $model->getRooms());
      } else {
         $this->assignRef('reg', $model->getPreregistered());
         $this->assignRef('rooms', $model->getPriorityRooms());
      }
      parent::display($tpl);
   }

   function getSafe($obj)
   {
      return htmlspecialchars(trim($obj), ENT_QUOTES);
   }

}
?>
