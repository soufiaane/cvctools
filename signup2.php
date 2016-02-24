<html>
<head>
    <title>Cap Value Consulting</title>
    <?php include("includes/inc.php"); ?>
</head>

<body>
<?php include("navbar.php"); ?>
<?php include("navbar-end.php"); ?>
<center><h1><span class="label label-default">Creat new account</span></h1></center>
<br><br><br>

<div class="col-md-4"></div>
<div class="col-md-4">
    <form method="post" action="signupexec.php">
        <label for="username" class="col-md-3 control-label">Username:</label>

        <div class="col-md-7">
            <input type="text" class="form-control" placeholder="Your connection username" name="username">
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br><br>
        <label for="password" class="col-md-3 control-label">Password:</label>

        <div class="col-md-7">
            <input type="password" class="form-control" placeholder="Enter your password" name="password">
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br><br>
        <label for="password2" class="col-md-3 control-label">Password:</label>

        <div class="col-md-7">
            <input type="password" class="form-control" placeholder="Retype your password" name="password2">
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br><br>
        <label for="fullname" class="col-md-3 control-label">Full Name:</label>

        <div class="col-md-7">
            <input type="text" class="form-control" placeholder="Enter your display name" name="fullname">
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br><br>
        <label for="entite" class="col-md-3 control-label">entity:</label>

        <div class="col-md-7">
            <label class="radio-inline"><input type="radio" name="entity" value="cvc1">cvc 1</label>
            <label class="radio-inline"><input type="radio" name="entity" value="cvc2">cvc 2</label>
            <label class="radio-inline"><input type="radio" name="entity" value="cvc3">cvc 3</label>
        </div>
        <div class="col-md-2"><font color="red" size="2">*</font></div>
        <br><br><br>
        <center>
            <button type="submit" class="btn btn-primary">Submite</button>
        </center>
    </form>
</div>
</body>
</html>