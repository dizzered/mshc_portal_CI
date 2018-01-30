<?php
		$convCommand = '"C:/Program Files (x86)/OfficeToPDF/officetopdf.exe" ' . 
		' /hidden /print /readonly ' .
		'C:/Users/pointmed/Desktop/N5430826.docx ' .
		'C:/Users/pointmed/Desktop/N5430826.pdf';
	shell_exec($convCommand);