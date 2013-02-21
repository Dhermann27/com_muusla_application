<?php 
/**
 * phonenumber.php
 * XHTML Block containing a phone number and one control
 *
 * @param   int     $nbrindex    If it's the first row of the form, output the label and an Add control, otherwise a Delete control, -1 for hidden row
 * @param   object  $phonenumber The database phone number object if the number exists
 *
 */
$nbrid = $phonenumber->phonenbrid > 0 ? "-" . $phonenumber->phonenbrid : "";
?>
<tr class="phonenbrs<?echo $nbrindex == -1 ? " hidden" : "";?>">
   <td><?php echo $nbrindex == 0 ? "Phone Numbers" : "&nbsp;";?></td>
   <td colspan="3"><select
      name="phonenumbers-phonetypeid<?php echo $nbrid;?>"
      class="ui-corner-all">
         <?php
         foreach($this->phonetypes as $phonetype) {
            $selected = $phonenumber->phonetypeid == $phonetype->phonetypeid ? " selected" : "";
            echo "      <option value='$phonetype->phonetypeid'$selected>$phonetype->name</option>\n";
         }
         ?>
   </select> <input type="text"
      name="phonenumbers-phonenbr<?php echo $nbrid; ?>" maxlength="14"
      class="inputtextshort ui-corner-all"
      value="<?php echo $phonenumber->phonenbr;?>" /> <input
      type="hidden" name="phonenumbers-phonenbrid<?php echo $nbrid; ?>"
      value="<?php echo $phonenumber->phonenbrid?>" /><input
      type="hidden" name="phonenumbers-camperid<?php echo $nbrid; ?>"
      value="<?php echo $camperid?>" />
      <button
         class="<?php echo $nbrindex == 0 ? "add" :"delete";?> help">
         <?php echo $nbrindex == 0 ? "Add Phone Number" : "Delete Phone Number";?>
      </button></td>
</tr>
