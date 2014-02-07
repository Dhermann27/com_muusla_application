<?php defined('_JEXEC') or die('Restricted access');
/**
 * charge.php
 * XHTML Block containing the editable charge block
 *
 * @param   object  $charge The database charges object
 *
 */
?>
<tr>
   <td><select name="charge-chargetypeid-<? echo $charge->id;?>"
      class="ui-corner-all">
         <option value="0">Charge Type</option>
         <?php if($charge->id > 0) {
            echo "          <option value='delete' style='background-color: indianred'>Delete Charge</option>\n";
         }
         foreach($this->chargetypes as $chargetype) {
            $selected = $charge->chargetypeid == $chargetype->id ? " selected" : "";
            echo "          <option value='$chargetype->chargetypeid'$selected>$chargetype->name</option>\n";
         }?>
   </select><input type="hidden"
      name="charge-id-<?php echo $charge->id;?>"
      value="<?php echo $charge->id;?>" /></td>
   <td align="right"><input type="text"
      name="charge-amount-<? echo $charge->id;?>"
      class="inputtexttiny onlymoney recalc ui-corner-all"
      value="<?php echo $charge->amount;?>" /></td>
   <td align="center"><input type="text"
      name="charge-timestamp-<? echo $charge->id;?>"
      class="inputtexttiny birthday ui-corner-all"
      value="<?php echo $charge->timestamp != "" ? $charge->timestamp : date(" m/d/Y");?>" />
   </td>
   <td><input type="text" name="charge-memo-<? echo $charge->id;?>"
      class="inputtext ui-corner-all"
      value="<?php echo $charge->memo;?>" /></td>
</tr>
