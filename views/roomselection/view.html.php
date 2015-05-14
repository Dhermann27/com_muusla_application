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
class muusla_applicationViewroomselection extends JViewLegacy
{

   function display($tpl = null) {
      $model = $this->getModel();
      $user = JFactory::getUser();

      $roomid = $this->getSafe(JRequest::getVar("yearattending-roomid-0"));
      if($roomid != "") {
         $emails = array_filter(explode(",", $model->updateYearattending($roomid, $this->getSafe(JRequest::getVar("yearattending-is_private-0")))));
         $msg = true;
         $this->assignRef("msg", $msg);
         if(count($emails) > 0) {
            $mailer = JFactory::getMailer();
            $config = JFactory::getConfig();
            $mailer->setSender(array($config->get( 'config.mailfrom' ), $config->get( 'config.fromname' ) ));
            $mailer->addRecipient($emails);
            $mailer->setSubject("MUUSA Online Registration");
            $mailer->setBody("Dear camper,\nJust an update on your MUUSA registration: your room assignment has just been saved (it may or may not have changed).\n\nPlease keep in mind that if you have recently vacated another room, it will be open to other campers and this process can be impossible to reverse.\n\nhttp://muusa.org/index.php/registration/room-selection\n(You may be required to login first.)\n\nSee you \"next week\",\n\nDan Hermann\nMUUSA Webmaster");
            $send = $mailer->Send();
         }
         
         $model->refresh();
      }

      $year = $model->getYear();
      $this->assignRef('year', $year);
      $this->assignRef('reg', $model->getPreregistered());
      if($year[1] == "0") {
         $this->assignRef('rooms', $model->getRooms());
      } else {
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
