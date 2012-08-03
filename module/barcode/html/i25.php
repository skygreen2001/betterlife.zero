<?php
define('IN_CB', true);
include('header.php');

$default_value['setChecksum'] = '';
$setChecksum = isset($_POST['setChecksum']) ? $_POST['setChecksum'] : $default_value['setChecksum'];
registerImageKey('setChecksum', $setChecksum);
registerImageKey('code', 'BCGi25');

$characters = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
?>

<ul id="specificOptions">
    <li class="option">
        <div class="title">
            <label for="setChecksum">Checksum</label>
        </div>
        <div class="value">
            <?php echo getCheckboxHtml('setChecksum', $setChecksum, array('value' => 1)); ?>
        </div>
    </li>
</ul>

<div id="validCharacters">
    <h3>Valid Characters</h3>
    <?php foreach ($characters as $character) { echo getButton($character); } ?>
</div>

<div id="explanation">
    <h3>Explanation</h3>
    <ul>
        <li>Interleaved 2 of 5 is based on Standard 2 of 5 symbology.</li>
        <li>There is an optional checksum.</li>
    </ul>
</div>

<?php
include('footer.php');
?>