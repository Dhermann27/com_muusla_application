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
<tr class="roomates">
   <td><?php echo $mateindex == 0 ? "Roommate Preferences" : "";?>
   </td>
   <td colspan="2"><input type="text" maxlength="50"
      class="inputtext roommates ui-corner-all mytooltip"
      value="<?php echo $name?>"
      title="You do not need to add family members to this list." />
   </td>
   <td>
      <button
         class="<?php echo $mateindex == 0 ? "add fa fa-plus" :"delete fa fa-minus";?> btn"
         title="<?php echo $mateindex == 0 ? "Add Roommate" : "Delete Roommate";?>">
      </button>
   </td>
</tr>
