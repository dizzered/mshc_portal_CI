<?php

set_time_limit(0);

function MakePropertyValue($name, $value, $osm) {
  $oStruct = $osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
  $oStruct->Name = $name;
  $oStruct->Value = $value;
  return $oStruct;
}

function word2pdf($doc_url, $output_url) {

  //Invoke the OpenOffice.org service manager
  $osm = new COM("com.sun.star.ServiceManager") or die("Please be sure that OpenOffice.org is installed.\n");
  //Set the application to remain hidden to avoid flashing the document onscreen
  $args = array(MakePropertyValue("Hidden", true, $osm));
  //Launch the desktop
  $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
  //Load the .doc file, and pass in the "Hidden" property from above
  $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url, "_blank", 0, $args);
  //Set up the arguments for the PDF output
  $export_args = array(MakePropertyValue("FilterName", "writer_pdf_Export", $osm), MakePropertyValue("PageRange", "1", $osm));
  //print_r($export_args);
  //Write out the PDF
  $oWriterDoc->storeToURL($output_url, $export_args);
  $oWriterDoc->close(true);
}

$output_dir = "C:/apache-webapps/portal/";
$doc_file = 'W:/volume_0.119/archive/00000045/N2293345.doc';//"C:/apache-webapps/portal/N3603952.docx";
$pdf_file = "N2293345.pdf";

$output_file = $output_dir . $pdf_file;
echo $doc_file = "file:///" . $doc_file;
$output_file = "file:///" . $output_file;
word2pdf($doc_file, $output_file);