<?php
function showError() {
    header('Content-Type: image/png');
    readfile('error.png');
    exit;
}

$requiredKeys = array('code', 'filetype', 'dpi', 'thickness', 'scale', 'rotation', 'font_family', 'font_size', 'text');
$possibleCodes = array('BCGcodabar', 'BCGcode11', 'BCGcode39', 'BCGcode39extended', 'BCGcode93', 'BCGcode128', 'BCGean8', 'BCGean13', 'BCGgs1128', 'BCGi25', 'BCGisbn', 'BCGmsi', 'BCGs25', 'BCGupca', 'BCGupce', 'BCGupcext2', 'BCGupcext5', 'BCGothercode', 'BCGpostnet', 'BCGintelligentmail');

// Check if everything is present in the request
foreach ($requiredKeys as $key) {
    if (!isset($_GET[$key])) {
        showError();
    }
}

// Check if the code is valid
if (!in_array($_GET['code'], $possibleCodes)) {
    showError();
}

$class_dir = '../class';
require_once($class_dir . DS . 'BCGColor.php');
require_once($class_dir . DS . 'BCGBarcode.php');
require_once($class_dir . DS . 'BCGDrawing.php');
require_once($class_dir . DS . 'BCGFontFile.php');

if (!include_once($class_dir . DS . $_GET['code'] . '.barcode.php')) {
    showError();
}

$barcodeSupports = array(
    'setChecksum' => array('BCGcode39', 'BCGcode39extended', 'BCGi25', 'BCGs25', 'BCGmsi'),
    'setStart' => array('BCGcode128', 'BCGgs1128'),
    'barcodeIdentifier' => array('BCGintelligentmail'), // Requires also serviceType, mailerIdentifier, serialNumber
    'setLabel' => array('BCGothercode')
);

$filetypes = array('PNG' => BCGDrawing::IMG_FORMAT_PNG, 'JPEG' => BCGDrawing::IMG_FORMAT_JPEG, 'GIF' => BCGDrawing::IMG_FORMAT_GIF);

$drawException = null;
try {
    $color_black = new BCGColor(0, 0, 0);
    $color_white = new BCGColor(255, 255, 255);
    $font = 0;
    if ($_GET['font_family'] !== '0' && intval($_GET['font_size']) >= 1) {
        $font = new BCGFontFile($class_dir . '/font/' . $_GET['font_family'], intval($_GET['font_size']));
    }

    $code_generated = new $_GET['code']();

    // Since image.php supports all barcode, we must check here
    // Which one support which option
    foreach ($barcodeSupports as $option => $barcodeNames) {
        if (isset($_GET[$option])) {
            if (in_array($_GET['code'], $barcodeNames)) {
                $value = $_GET[$option];
                switch ($value) {
                    case 'true':
                        $value = true;
                        break;
                    case 'false':
                        $value = false;
                        break;
                    case 'NULL':
                        $value = null;
                        break;
                    default:
                        if (is_numeric($value)) { // We accept only integer...
                            $value = intval($value);
                        }
                }

                switch ($option) {
                    case 'setChecksum':
                    case 'setStart':
                    case 'setLabel':
                        $code_generated->$option($value);
                        break;
                    case 'barcodeIdentifier':
                        // Make sure we have all we need
                        if (!isset($_GET['serviceType']) || !isset($_GET['mailerIdentifier']) || !isset($_GET['serialNumber'])) {
                            break;
                        }
                        $code_generated->setTrackingCode(intval($_GET['barcodeIdentifier']), intval($_GET['serviceType']), intval($_GET['mailerIdentifier']), intval($_GET['serialNumber']));
                        break;
                }

            }
        }
    }

    $code_generated->setThickness($_GET['thickness']);
    $code_generated->setScale($_GET['scale']);
    $code_generated->setBackgroundColor($color_white);
    $code_generated->setForegroundColor($color_black);
    $code_generated->setFont($font);

    $code_generated->parse($_GET['text']);
} catch(Exception $exception) {
    $drawException = $exception;
}

$drawing = new BCGDrawing('', $color_white);
if($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code_generated);
    $drawing->setRotationAngle($_GET['rotation']);
    $drawing->setDPI($_GET['dpi'] === 'NULL' ? null : intval($_GET['dpi']));
    $drawing->draw();
}

switch ($_GET['filetype']) {
    case 'PNG':
        header('Content-Type: image/png');
        break;
    case 'JPEG':
        header('Content-Type: image/jpeg');
        break;
    case 'GIF':
        header('Content-Type: image/gif');
        break;
}

$drawing->finish($filetypes[$_GET['filetype']]);
?>