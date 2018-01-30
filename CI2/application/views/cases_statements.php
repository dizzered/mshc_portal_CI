<?php

if (count($pdf_files)) 
{
	foreach ($pdf_files as $file)
	{
		echo '<a href="'.base_url().'cases/statement/'.$file.'" target="_blank" style="visibility:hidden" class="fnPDF">'.$file.'</a>';
	}
	?>
	<div style="width:60%; margin:0 auto;">
	<h1>Your statement was opened in new tabs or windows.</h1>
	<h3>If your browser blocks popup windows, please change browser settings to enable popup of windows and refresh this page. Thank you!</h3>
	</div>
	<script>
	$(function() {
		$('a.fnPDF').each(function () {
			if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)
			{
				var event = new MouseEvent('click', {
					'view': window,
					'bubbles': true,
					'cancelable': true
				});
				
				this.dispatchEvent(event);
			}
			else
			{
				var clk = document.createEvent("MouseEvents");
				clk.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
				this.dispatchEvent(clk);
			}
		});
	});
	</script>
	<?php
}
else
{
	?>
	<!--<h1>Error occuring while pdf generates. Please try again later or contact with administration of portal.</h1>-->
	<h1>No statements available.</h1>
	<?php
}
?>
