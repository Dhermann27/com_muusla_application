<?php defined('_JEXEC') or die('Restricted access'); 
/**
 * roommate.php
 * XHTML Block containing a roommate preference and one control
 *
 * @param   int     $mateindex  If it's the first row of the form, output the label and an Add control, otherwise a Delete control, -1 for hidden row
 * @param   object  $name       The name of the roommate if the it exists
 *
 */
?>
<tr <?echo $mateindex == -1 ? "class='hidden'" : "";?>>
   <td><?php echo $mateindex == 0 ? "Roommate Preferences" : "&nbsp;";?>
   </td>
   <td colspan="3"><input type="text" maxlength="50"
      class="inputtext roommates ui-corner-all"
      value="<?php echo $name?>" />
      <button
         class="<?php echo $mateindex == 0 ? "add" :"delete";?> help">
         <?php echo $mateindex == 0 ? "Add Roommate" : "Delete Roommate";?>
      </button></td>
</tr>
