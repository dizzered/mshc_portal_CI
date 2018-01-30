<?php
$tmpfname = tempnam(sys_get_temp_dir(), 'DSC');
$tmpfname .= '.pdf';
/*exec('convert \\\\docstar\\Archive\\VOLUME_0.05\\ARCHIVE\\00000003\\00552963.TIF ' . $tmpfname . ' 2>&1');
header("Content-Type: application/pdf"); 
*/
$word = new COM('Word.Application') or die('no word');
$word->Visible = 0;
$word->Documents->Open('C:\\apache-webapps\\portal\\uploads\\Zyuzin-Dogovor-Akt.doc');
$word->ActiveDocument->ExportAsFixedFormat($tmpfname, 17, false);
readfile($tmpfname); 