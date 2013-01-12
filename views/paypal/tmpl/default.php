<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="ja-content">
<div class="componentheading">Welcome Back</div>
<table class="blog" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top">
		<div>
		<div class="contentpaneopen"><?php
		if($this->amount) {
		   $msg = "successful";
		} else {
		   $msg = "failed";
		}
		echo "		   <h2 class='contentheading'>Payment $msg!</h2>\n";
		echo "		   </div>\n";
		echo "		   <div class='article-content'>\n";
		if($msg == "successful") {
		   echo "		   Thank you, $this->name. MUUSA received your payment for \$" . number_format($this->amount,2) . ". That payment will be reflected on your bill.</div>\n";
		}?> <span class="article_separator">&nbsp;</span></div>
		
		</td>
	</tr>
</table>
</div>
