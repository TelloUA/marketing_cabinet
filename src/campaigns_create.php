<?php
require dirname(__DIR__).'/vendor/autoload.php';

use App\Entity\CampaignType;
use App\Entity\Device;

require "blocks/header.php";
?>

<body>
<h1>Creating campaign</h1>
<div style="container; width: 500px">
<?php
$name = $type = $device = $geo = $geoId = $url = "";
$nameErr = $typeErr = $deviceErr = $geoErr = $urlErr = "";
$types = CampaignType::getTypes();
$devices = Device::getDevices();

if (isset($_COOKIE['id']) ) {
    $user_id = $_COOKIE['id'];
} else {
    header('Location: authorization.php');
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success_submit = true;
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $success_submit = false;
    } else {
        $name = test_input($_POST["name"]);
        $nameExistSelect = "SELECT `name` FROM `campaigns` WHERE `user_id` = '$user_id' AND `name` = '$name'";
        $nameExist = selectConnect($nameExistSelect);
        if ($nameExist->num_rows > 0) {
            $nameErr = "Campaign name already exist";
            $success_submit = false;
        } else if (!preg_match("/^[a-zA-Z0-9 ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
            $success_submit = false;
        } else if (strlen($name) > 255) {
            $nameErr = "Name should be less than 255";
            $success_submit = false;
        }
    }
    if (empty($_POST["type"])) {
        $typeErr = "Campaign type is required";
        $success_submit = false;
    } else {
        $type = test_input($_POST["type"]);
        if (!in_array($type, $types)) {
            $typeErr = "Wrong type, select again";
            $success_submit = false;
        }
    }

    if (empty($_POST["device"])) {
        $deviceErr = "Device is required";
        $success_submit = false;
    } else {
        $device = test_input($_POST["device"]);
        if (!in_array($device, $devices)) {
            $deviceErr = "Wrong device, select again";
            $success_submit = false;
        }
    }

    if (empty($_POST["geo"])) {
        $geoErr = "Geo is required";
        $success_submit = false;
    } else {
        $geo = test_input($_POST["geo"]);
        $checkGeo = "SELECT `id`, `name`, `short_name` FROM `geo` WHERE `name` = '$geo';";
        $resultCheckGeo = selectConnect($checkGeo);
        if ($resultCheckGeo->num_rows == 0) {
            $geoErr = "Wrong geo, select again";
            $success_submit = false;
        }
        $geoId = $resultCheckGeo->fetch_assoc()['id'];
    }

    if (empty($_POST["url"])) {
        $urlErr = "Url is required";
        $success_submit = false;
    } else {
        $url = test_input($_POST["url"]);
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $urlErr = "Url contains mistakes";
            $success_submit = false;
        }
    }
    if ($success_submit) {
        $insert = "
            INSERT INTO `campaigns` (`user_id`, `name`, `type`, `device`, `geo`, `url`)
            VALUES ('$user_id', '$name', '$type', '$device', '$geoId', '$url');";
        noReturnConnect($insert);
        header('Location: campaigns_list.php');
    } else {
        echo "<h3>Some mistakes</h3>";
    }

}

?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="name"> Campaign Name <span class="error">*</span></label>
        <input type="text" name="name" value="<?php echo $name;?>" class="form-control">
        <span class="error"><?php echo $nameErr; ?></span><br>

    <label for="type">Campaign Type <span class="error">*</span></label>
    <select name="type" id="type" class="form-control">
        <option value="" <?php if($type == "") {echo "selected";}?> ></option>
        <!-- Adding options for select-->
        <?php echo drawSelectOptions($types, $type); ?>
    </select>
        <span class="error"><?php echo $typeErr; ?></span><br>

    <label for="device">Device <span class="error">*</span></label>
    <select name="device" id="device" class="form-control">
        <option value="" <?php if($device == "") {echo "selected";}?> ></option>
        <!-- Adding options for select-->
        <?php echo drawSelectOptions($devices, $device); ?>
    </select>
        <span class="error"><?php echo $deviceErr; ?></span><br>

    <label for="geo">Country <span class="error">*</span></label>
    <select name="geo" id="device" class="form-control">
        <option value="" <?php if($geo == "") {echo "selected";}?> ></option>
        <?php
        //take geo list and add options in select
        $countriesQuery = "SELECT `id`, `name`, `short_name` FROM `geo` ORDER BY `name`;";
        $countriesSelect = selectConnect($countriesQuery);
        $countries = array();

        if ($countriesSelect->num_rows > 0) {
            while ($row = $countriesSelect->fetch_assoc()) {
                $countries[] = $row['name'];
            }
        }
        echo drawSelectOptions($countries, $geo);
        ?>
    </select>
        <span class="error"><?php echo $geoErr; ?></span><br>

    <label for="url"> Campaign Url <span class="error">*</span></label>
        <input type="text" name="url" value="<?php echo $url;?>" class="form-control">
        <span class="error"><?php echo $urlErr; ?></span><br>
        <input type="submit" value="Create">
    </form>
</div>
</body>
<?php
require "blocks/footer.php";
?>


