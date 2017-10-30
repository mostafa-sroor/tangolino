<?php $zip = new ZipArchive;
$res = $zip->open('insurance.zip');
if ($res === TRUE) {
    $zip->extractTo($_SERVER['DOCUMENT_ROOT']);
    $zip->close();
    echo 'woot!'.$_SERVER['DOCUMENT_ROOT'];
} else {
    echo 'doh!'.$_SERVER['DOCUMENT_ROOT'];
}
?>