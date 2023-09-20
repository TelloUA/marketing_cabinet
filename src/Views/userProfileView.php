<?php

if (!$GLOBALS['isLogged']) {
    header('Location: /authorization');
}

$name = $data['name'];
$nameErr = $data['nameErr'];
$email = $data['email'];
$emailErr = $data['emailErr'];
$country = $data['country'];
$countryErr = $data['countryErr'];
$company = $data['company'];
$companyErr = $data['companyErr'];
$communicationChannel = $data['communication_channel'];
$communicationChannels = $data['communicationChannelTypes'];
$communicationInfo = $data['communication_info'];
$success_submit = $data['successSubmit'];

?>

<body>
<h1>Profile page</h1>
<div style="container; width: 500px">
    <form action="<?php echo '/user/profile'; ?>" method="post" id="profileData">
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
<label for="channel">Communication chanel</label>
<select name="channel" id="channel" class="form-control">
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
