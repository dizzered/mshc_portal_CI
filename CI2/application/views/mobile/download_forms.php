<div style="padding: 11px;">

  <div class="roundbox">

        <div class="roundbox-header2 header2" style="height: 28px">

        </div>

        <div style="padding: 16px">

            <ul>
<?php				
                for ($i = 0; $i < count($forms_list); $i++) {
					echo '<li class="pdf"><a href="'.MSHC_UPLOAD_FILE_PATH.'/'.$forms_list[$i]['file_name'].'" >'.$forms_list[$i]['name'].'</a></li>';
				}
  ?>              
            </ul>

        </div>

    </div>

</div>