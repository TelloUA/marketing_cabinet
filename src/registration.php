<?php
require "blocks/header.php";
?>
<body>
<h1>Registration</h1>
<div style="container; width: 500px">
    <?php
    $email = $pwd = ""; //values in forms
    $emailErr = $pwdErr = ""; //error in forms
    $success = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            //setup value in field
            $email = test_input($_POST["email"], true);
            //take current email from DB
            $emailExistSelect = "SELECT `email` FROM `users` WHERE `email` = '$email'";
            $emailExist = selectConnect($emailExistSelect);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format"; //basic email validation
            } else if (strlen($email) > 255) {
                $emailErr = "Email to long";
            } else if ($emailExist->num_rows > 0) {
                $emailErr = "Email already exist";
            }
        }
        if (empty($_POST["pwd"])) {
            $pwdErr = "Password is required";
        } else {
            $pwd = test_input($_POST["pwd"]);
            if (strlen($pwd) < 8) {
                $pwdErr = "Password to short";
            } else if (strlen($pwd) > 255) {
                $pwdErr = "Password to long";
            } else if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&_]).*$/", $pwd)) {
                $pwdErr = "Password does not meet the requirements";
            }
        }
        if ($emailErr == "" && $pwdErr == "") {

            $pwd_hash = md5($pwd);
            $createUserInsert = "INSERT INTO `users` (`email`, `password`) VALUES ('$email', '$pwd_hash')";
            noReturnConnect($createUserInsert);
            $success = true;
        }
    }

    ?>
    Requirements for password:
    <ol>
    <li>At least one uppercase or lowercase letter.</li>
    <li>At least one digit.</li>
    <li>At least one special character - @$!%*#?&_.</li>
    <li>Minimum length of 8 characters.</li>
</ol>
    <form action="<?php echo htmlspecialchars('/registration'); ?>" method="post">
        <label for="email">Email <span class="error">*</span></label>
        <input type="text" name="email" value="<?php echo $email;?>" class="form-control">
        <span class="error"><?php echo $emailErr; ?></span><br>
        <label for="pwd">Password <span class="error">*</span></label>
        <input type="password" name="pwd" value="<?php echo $pwd;?>" class="form-control">
        <span class="error"><?php echo $pwdErr; ?></span><br>
        <input type="submit">
    </form>
    <?php
    if ($success) { echo "<h2>Your created account!</h2>"; }
    ?>
</div>

</body>
<?php
require "blocks/footer.php";
?>