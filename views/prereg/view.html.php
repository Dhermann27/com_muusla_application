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
class muusla_applicationViewprereg extends JViewLegacy
{

   function display($tpl = null) {
      $model = $this->getModel();
      $this->assignRef('year', $model->getYear());
      $this->assignRef('campers', $model->getCampers());
      parent::display($tpl);
   }

}
?>
