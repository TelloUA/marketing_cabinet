<?php
if ($GLOBALS['isLogged']) {
    header('Location: /campaign/list');
}

$email = $data['email'];
$emailErr = $data['emailErr'];
$pwdErr = $data['pwdErr'];
$success = $data['success'];
?>
<body>
<h1>Registration</h1>
<div style="container; width: 500px">
    Requirements for password:
    <ol>
        <li>At least one uppercase or lowercase letter.</li>
        <li>At least one digit.</li>
        <li>At least one special character - @$!%*#?&_.</li>
        <li>Minimum length of 8 characters.</li>
    </ol>
    <form action="/login/registration" method="post">
        <label for="email">Email <span class="error">*</span></label>
        <input type="text" name="email" value="<?php echo $email;?>" class="form-control">
        <span class="error"><?php echo $emailErr; ?></span><br>
        <label for="pwd">Password <span class="error">*</span></label>
        <input type="password" name="pwd" value="" class="form-control">
        <span class="error"><?php echo $pwdErr; ?></span><br>
        <input type="submit">
    </form>
    <?php
    if ($success) { echo "<h2>Your created account!</h2>"; }
    ?>
</div>

</body>