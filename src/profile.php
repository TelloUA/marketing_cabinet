<?php
require "blocks/header.php";
?>
    <body>
<h1>Profile page</h1>
<div style="container; width: 500px">

<?php
$name = $email = $country = $company = $communicationChannel = $communicationInfo = NULL;
$nameErr = $emailErr = $countryErr = $companyErr = NULL;
$communicationChannels = array('telegram', 'skype', 'email');
$success_submit = false;

if (isset($_COOKIE['id']) ) {
    $user_id = $_COOKIE['id'];
    $userFullDataSelect = "SELECT `id`, `name`, `email`, `country`, `company`, 
       `communication_channel`, `communication_info` FROM `users` WHERE `id` = '$user_id'";
    $userFullDataArray = selectConnect($userFullDataSelect)->fetch_assoc();
        //print_r($userFullDataArray);
    $name = $userFullDataArray['name'];
    $email = $userFullDataArray['email'];
    $country = $userFullDataArray['country'];
    $company = $userFullDataArray['company'];
    $communicationChannel = $userFullDataArray['communication_channel'];
    $communicationInfo = $userFullDataArray['communication_info'];
} else {
    //header('Location: authorization');
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $success_submit = true;
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $success_submit = false;
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Only letters and white space allowed";
            $success_submit = false;
        } else if (strlen($name) > 40) {
            $nameErr = "Name should be less than 40";
            $success_submit = false;
        }
    }
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $success_submit = false;
    } else {
        $email = test_input($_POST["email"]);
        $emailSelect = "SELECT `email` FROM `users` WHERE `email` = '$email' AND `id` <> '$user_id'";
        $emailExist = selectConnect($emailSelect);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $success_submit = false;
        } else if ($emailExist->num_rows > 0) {
            $emailErr = "Email already exist";
            $success_submit = false;
        }
    }
    if (empty($_POST["country"])) {
        $countryErr = "Country is required";
        $success_submit = false;
    } else {
        $country = test_input($_POST["country"]);
        if (strlen($country) > 40) {
            $countryErr = "Country should be less than 40";
            $success_submit = false;
        }
    }
    $company = test_input($_POST["company"]);
    if (strlen($name) > 40) {
        $companyErr = "Name should be less than 40";
        $success_submit = false;
    }
    $communicationChannel = $_POST["chanel"];
    $communicationInfo = test_input($_POST["communicationInfo"]);

    if ($success_submit) {
        $updateUserData = "UPDATE `users` 
                    SET `name` = '$name', 
                        `email` = '$email',
                        `country` = '$country', `company` = '$company',
                        `communication_channel` = '$communicationChannel', `communication_info` = '$communicationInfo' 
                    WHERE `id` = '$user_id'";
        noReturnConnect($updateUserData);
    }
}

?>

    <form action="<?php echo '/profile'; ?>" method="post" id="profileData">
        <label for="name">Your name <span class="error">*</span></label>
        <input type="text" name="name" value="<?php echo $name;?>" class="form-control">
        <span class="error"><?php echo $nameErr; ?></span><br>
        <label for="email">Email <span class="error">*</span></label>
        <input type="text" name="email" value="<?php echo $email;?>" class="form-control">
        <span class="error"><?php echo $emailErr; ?></span><br>
        <label for="country">Country <span class="error">*</span></label>
        <input type="text" name="country" value="<?php echo $country;?>" class="form-control">
        <span class="error"><?php echo $countryErr; ?></span><br>
        <label for="company">Company</label>
        <input type="text" name="company" value="<?php echo $company;?>" class="form-control">
        <span class="error"><?php echo $companyErr; ?></span><br>
        <label for="chanel">Communication chanel</label>
        <select name="chanel" id="chanel" class="form-control">
            <option value="" <?php if($communicationChannel == "") {echo "selected";}?> ></option>
            <?php echo drawSelectOptions($communicationChannels, $communicationChannel) ?>
        </select><br>
        <div id="ifChanelSelected" style="display: block;">
            <label for="communicationInfo">Info for communication</label>
            <input type="text" name="communicationInfo" value="<?php echo $communicationInfo;?>" class="form-control"><br>
        </div>
        <input type="submit" value="Save">
    </form>
    <?php if ($success_submit) { echo "<h2>Your data successfully saved!</h2>";} ?>
</div>
</body>
<?php
require "blocks/footer.php";
?>