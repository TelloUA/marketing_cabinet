<?php
if (!$GLOBALS['isLogged']) {
    header('Location: authorization');
}

$name = $data['name'];
$nameErr = $data['nameErr'];
$type = $data['type'];
$types = $data['types'];
$typeErr = $data['typesErr'];
$device = $data['device'];
$devices = $data['devices'];
$deviceErr = $data['deviceErr'];
$geo = $data['geo'];
$geoList = $data['geoList'];
$geoErr = $data['geoErr'];
$url = $data['url'];
$urlErr = $data['urlErr'];

?>
<body>
<h1>Creating campaign</h1>
<div style="container; width: 500px">

    <form action="<?php echo '/new_create'; ?>" method="post">
        <!-- Campaign name field -->
        <label for="name"> Campaign Name <span class="error">*</span></label>
        <input type="text" name="name" value="<?php echo $name;?>" class="form-control">
        <span class="error"><?php echo $nameErr; ?></span><br>

        <!-- Campaign type select -->
        <label for="type">Campaign Type <span class="error">*</span></label>
        <select name="type" id="type" class="form-control">
            <option value="" <?php if($type == "") {echo "selected";}?> ></option>
            <!-- Adding options for select-->
            <?php echo drawSelectOptions2($types, $type); ?>
        </select>
        <span class="error"><?php echo $typeErr; ?></span><br>

        <!-- Campaign device select -->
        <label for="device">Device <span class="error">*</span></label>
        <select name="device" id="device" class="form-control">
            <option value="" <?php if($device == "") {echo "selected";}?> ></option>
            <!-- Adding options for select-->
            <?php echo drawSelectOptions2($devices, $device); ?>
        </select>
        <span class="error"><?php echo $deviceErr; ?></span><br>

        <!-- Campaign geo select -->
        <label for="geo">Country <span class="error">*</span></label>
        <select name="geo" id="device" class="form-control">
            <option value="" <?php if($geo == "") {echo "selected";}?> ></option>
            <?php echo drawSelectOptions2($geoList, $geo); ?>
        </select>
        <span class="error"><?php echo $geoErr; ?></span><br>

        <!-- Campaign url select -->
        <label for="url"> Campaign Url <span class="error">*</span></label>
        <input type="text" name="url" value="<?php echo $url;?>" class="form-control">
        <span class="error"><?php echo $urlErr; ?></span><br>

        <input type="submit" value="Create">
    </form>
</div>
</body>