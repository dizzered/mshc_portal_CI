<?php
// echo exec("copy \\\\docstar\\d$\\VOLUME_0.119\ARCHIVE\00000052\N2291052.doc C:/apache-webapps/portal/converts/03120649.tif");

 echo exec("copy \\\\docstar\\d$\\docs\\VOLUME_0.042\\ARCHIVE\\00000000\\03120649.tif C:\\apache-webapps\\portal\\converts\\03120649.tif 2>&1");

/*
$result = copy("\\\\docstar\\d$\\docs\\VOLUME_0.042\\ARCHIVE\\00000000\\03120649.tif", 
				"C:\\apache-webapps\\portal\\converts\\03120649.tif");
if($result) {
	echo 'copied';
}
else {
	echo 'failed copying';
}
*/

/*
$file = 'C:\\apache-webapps\\portal\\converts\\people.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "John Smith\n";
// Write the contents back to the file
file_put_contents($file, $current);
*/

/*
$file = 'C:\\apache-webapps\\portal\\converts\\people.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
echo $current;
*/

/*$d = dir("\\\\docstar\\d$\\VOLUME_0.119\ARCHIVE\00000052");
echo "Handle: " . $d->handle . "\n";
echo "Path: " . $d->path . "\n";
$d->close();*/
