<?php

use App\Views\ViewHelper;


// редірект треба винести на інший рівень, при скиданні кукі - помилки
if (!$GLOBALS['isLogged']) {
    header('Location: authorization');
}

$id = $data['id'] ?? '';
$campaignErr = $data['campaignErr'] ?? '';
$name = $data['name'] ?? '';
$nameErr = $data['nameErr'] ?? '';
$type = $data['type'] ?? '';
$types = $data['types'] ?? [];
$typeErr = $data['typeErr'] ?? '';
$device = $data['device'] ?? '';
$devices = $data['devices'] ?? [];
$deviceErr = $data['deviceErr'] ?? '';
$geo = $data['geo'] ?? '';
$geoList = $data['geoList'] ?? [];
$geoErr = $data['geoErr'] ?? '';
$url = $data['url'] ?? '';
$urlErr = $data['urlErr'] ?? '';
$successSubmit = $data['successSubmit'] ?? false;
?>

<body>
<h1>Editing campaign</h1>
<div style="container; width: 500px">

    <?php if ($campaignErr) { echo "<h2 class='error'>".$campaignErr."</h2>";} ?>
    <form action="<?php echo '/campaign/edit/'. $id ; ?>" method="post">
        <!-- Campaign name field -->
        <label for="name"> Campaign Name <span class="error">*</span></label>
        <input type="text" name="name" value="<?php echo $name;?>" class="form-control">
        <span class="error"><?php echo $nameErr; ?></span><br>

        <!-- Campaign type select -->
        <label for="type">Campaign Type <span class="error">*</span></label>
        <select name="type" id="type" class="form-control">
            <option value="" <?php if($type == "") {echo "selected";}?> ></option>
            <!-- Adding options for select-->
            <?php echo ViewHelper::drawOptions($types, $type); ?>
        </select>
        <span class="error"><?php echo $typeErr; ?></span><br>

        <!-- Campaign device select -->
        <label for="device">Device <span class="error">*</span></label>
        <select name="device" id="device" class="form-control">
            <option value="" <?php if($device == "") {echo "selected";}?> ></option>
            <!-- Adding options for select-->
            <?php echo ViewHelper::drawOptions($devices, $device); ?>
        </select>
        <span class="error"><?php echo $deviceErr; ?></span><br>


        <!-- Campaign geo select -->
        <label for="geo">Country <span class="error">*</span></label>
        <input type="text" name="geo" class="form-control" value="<?php echo $geo;?>" disabled>
        <span class="error"><?php echo $geoErr; ?></span><br>

        <!-- Campaign url input -->
        <label for="url"> Campaign Url <span class="error">*</span></label>
        <input type="text" name="url" value="<?php echo $url;?>" class="form-control">
        <span class="error"><?php echo $urlErr; ?></span><br>

        <input type="submit" value="Save changes">
    </form>

    <?php if ($successSubmit) { echo "<h2>Your campaign successfully changed!</h2>";} ?>
</div>
</body>