<div class="main-container-header">
	<table border="0" class="main-container-header-tab">
    	<tr><td></td></tr>
    </table>
</div>
<div class="main-container">
<div id="patient_forms-table-container">
	<table width="100%">
        	<?php
				for ($i = 0; $i < count($forms_list); $i++) {
					if ($i % 2 == 0) {
						echo '<tr>';
						echo '<td style="text-align:left; width:50%;">
							<a href="'.MSHC_UPLOAD_FILE_PATH.'/'.$forms_list[$i]['file_name'].'" style="float:left;">
							<img src="images/pdf-icon.jpg" width="24" alt="PDF" style="padding-right:10px;" /></a>';
						echo '<a href="'.MSHC_UPLOAD_FILE_PATH.'/'.$forms_list[$i]['file_name'].'" style="color:#7ca6bc; float:left; margin-top:5px;">'
						.$forms_list[$i]['name'].'</a>';
						echo '</td>';
					} else {
						echo '<td style="text-align:left; width:50%;">
						<a href="'.MSHC_UPLOAD_FILE_PATH.'/'.$forms_list[$i]['file_name'].'" style="float:left;">
						<img src="images/pdf-icon.jpg" width="24" alt="PDF" style="padding-right:10px;" /></a>';
						echo '<a href="'.MSHC_UPLOAD_FILE_PATH.'/'.$forms_list[$i]['file_name'].'" style="color:#7ca6bc;float:left; margin-top:5px;">'
						.$forms_list[$i]['name'].'</a>';
						echo '</td></tr>';
					}
				}
				if (count($forms_list) % 2 == 1) {
					echo '<td></td></tr>';
				} 
			?>
     </table>
     <br />
    <hr style="border: 0; border-bottom:1px solid #999" />
    <br />
    <table>
    	<tr>
        	<td>
    			<a href="http://get.adobe.com/reader"><img src="images/get_adobe_reader.png" alt="Adobe Reader"/></a>
            </td>
            <td align="left">
            	Need Adobe Acrobat Reader? If you do have a copy of Adobe Reader you can get one for free by clicking on the icon 'Get Adobe® Reader'. This will take you<br /> to Adobe's website and enable you to download the Acrobat program to your computer.
            </td>
        </tr>
     </table>
</div>
</div>