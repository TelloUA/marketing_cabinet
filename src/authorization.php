<?php
require "blocks/header.php";
?>
<body>
<h1>Authorization</h1>
<div style="container; width: 500px">
<?php
$email = $pwd = "";
$emailErr = $authErr = "";
$mainErr = "Email and password didn't match";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"], true);
        $userDataSelect = "SELECT `id`, `email`, `password` FROM `users` WHERE `email` = '$email'";
        $userData = selectConnect($userDataSelect);
        if ($userData->num_rows == 1) {
            if (empty($_POST["pwd"])) {
                $authErr = $mainErr;
            } else {
                $pwd = test_input($_POST["pwd"]);
                $pwd_hash = md5($pwd);
                $row = $userData->fetch_assoc();
                if ($row['password'] == $pwd_hash) {
                    //echo "SUCCESS AUTH";
                    setcookie('id', $row['id'], time() + 1800);
                    //$_SESSION['user'] = $row['email'];
                    //$_SESSION['user_id'] = $row['id'];
                    header('Location: profile');
                    echo $_COOKIE['id'];
                } else {
                    $authErr = $mainErr;
                }
            }
        } else if ($userData->num_rows > 1) {
            $authErr = "Go to support with your email";
        } else {
            $authErr = $mainErr;
        }
    }
}
?>

    <span></span>
    <form action="<?php echo htmlspecialchars('authorization'); ?>" method="post">
        <label for="email">Email <span class="error">*</span></label>
        <input type="text" name="email" value="<?php echo $email;?>" class="form-control">
        <span class="error"><?php echo $emailErr; ?></span><br>
        <label for="pwd">Password <span class="error">*</span></label>
        <input type="password" name="pwd" value="" class="form-control">
        <span class="error"><?php echo $authErr; ?></span><br>
        <input type="submit">
    </form>
    
</div>
</body>
<?php
require "blocks/footer.php";
?>