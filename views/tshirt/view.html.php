<?php
/**
 * @package		muusla_application
 * @license		GNU/GPL, see LICENSE.php
 */

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the muusla_application buildings Component
 *
 * @package		muusla_application
 */
class muusla_applicationViewtshirt extends JView
{
	function display($tpl = null) {
		$model =& $this->getModel();

		parent::display($tpl);
	}

}
?>
