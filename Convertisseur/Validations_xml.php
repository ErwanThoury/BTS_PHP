

<?php
echo '<meta charset="utf-8" />
	  <link rel="stylesheet" href="css/styleValidationsXml.css" />'; 
	  
function libxml_display_error($error)
{
    $return = "<br/>\n";
    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<b>Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<b>Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<b>Fatal Error $error->code</b>: ";
            break;
    }
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " <b> in $error->file</b>";
    }
    $return .= "<b> on line $error->line</b>\n";

    return $return;
}

function libxml_display_errors() 
{
    $errors = libxml_get_errors();
    foreach ($errors as $error) 
	{
        print libxml_display_error($error);
    }
    libxml_clear_errors();
}

// Enable user error handling
libxml_use_internal_errors(true);

//$xml = new DOMDocument();
//$xml->load("test_validation_xml.xml");
//if (!$xml->schemaValidate('Commande.xsd')) 
//{
//   print '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
//   libxml_display_errors();
//}
?>