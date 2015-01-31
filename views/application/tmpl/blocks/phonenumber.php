<?php defined('_JEXEC') or die('Restricted access'); 
/**
 * phonenumber.php
 * XHTML Block containing a phone number and one control
 *
 * @param   int     $nbrindex    If it's the first row of the form, output the label and an Add control, otherwise a Delete control, -1 for hidden row
 * @param   object  $phonenumber The database phone number object if the number exists
 *
 */
$nbrid = $phonenumber->id > 0 ? $phonenumber->id : "";
?>
<tr class="phonenbrs">
   <td><?php echo $nbrindex == 0 ? "Phone Numbers" : "&nbsp;";?></td>
   <td colspan="2"><select
      name="phonenumber-phonetypeid-<?php echo $nbrid;?>"
      class="ui-corner-all">
         <?php
         foreach($this->phonetypes as $phonetype) {
            $selected = $phonenumber->phonetypeid == $phonetype->id ? " selected" : "";
            echo "      <option value='$phonetype->id'$selected>$phonetype->name</option>\n";
         }
         ?>
   </select> <input type="text"
      name="phonenumber-phonenbr-<?php echo $nbrid; ?>"
      class="inputtextshort validphone ui-corner-all"
      value="<?php echo $phonenumber->phonenbr;?>" /> <?php if($nbrid >= 1000) { ?>
      <input type="hidden" name="phonenumber-id-<?php echo $nbrid; ?>"
      value="<?php echo $phonenumber->id?>" /> <?php }?><input
      type="hidden" name="phonenumber-camperid-<?php echo $nbrid; ?>"
      value="<?php echo $camperid?>" />
   </td>
   <td>
      <button
         class="<?php echo $nbrindex == 0 ? "add fa fa-plus" :"delete fa fa-minus";?> btn"
         title="<?php echo $nbrindex == 0 ? "Add Phone Number" : "Delete Phone Number";?>">
      </button>
   </td>
</tr>
