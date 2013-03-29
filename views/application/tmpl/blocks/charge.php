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
   <td><select name="charges-chargetypeid-<? echo $charge->chargeid;?>"
      class="ui-corner-all">
         <option value="0">Charge Type</option>
         <?php if($charge->chargeid > 0) {
            echo "          <option value='delete' style='background-color: indianred'>Delete Charge</option>\n";
         }
         foreach($this->chargetypes as $chargetype) {
            $selected = $charge->chargetypeid == $chargetype->chargetypeid ? " selected" : "";
            echo "          <option value='$chargetype->chargetypeid'$selected>$chargetype->name</option>\n";
         }?>
   </select><input type="hidden"
      name="charges-chargeid-<?php echo $charge->chargeid;?>"
      value="<?php echo $charge->chargeid;?>" /></td>
   <td align="right"><input type="text"
      name="charges-amount-<? echo $charge->chargeid;?>"
      class="inputtexttiny onlymoney recalc ui-corner-all"
      value="<?php echo $charge->amount;?>" /></td>
   <td align="center"><input type="text"
      name="charges-timestamp-<? echo $charge->chargeid;?>"
      class="inputtexttiny birthday ui-corner-all"
      value="<?php echo $charge->timestamp != "" ? $charge->timestamp : date(" m/d/Y");?>" />
   </td>
   <td><input type="text"
      name="charges-memo-<? echo $charge->chargeid;?>"
      class="inputtext ui-corner-all"
      value="<?php echo $charge->memo;?>" /></td>
</tr>
