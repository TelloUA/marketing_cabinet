<?php
if ($GLOBALS['isLogged']) {
    header('Location: /campaign/list');
}

$email = $data['email'];
$emailErr = $data['emailErr'];
$authErr = $data['authErr'];
?>

<body>
<h1>Authorization</h1>
<div style="container; width: 500px">
    <form action="/login/authorization" method="post">
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
