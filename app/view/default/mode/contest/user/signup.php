<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/signin.css'?>">

<div class="container">
    <form class="form-signin" role="form" method="POST" action="">
        <?php
            if (isset($this->Error)) {
                echo "<div class='alert alert-danger'>$this->Error</div>";
            } elseif (isset($this->Success)) {
                echo "<div class='alert alert-success'>$this->Success</div>";
            }
        ?>

        <h2 class="form-signin-heading">Contest sign up</h2>
        <input type="text" class="form-control"  name="Username" placeholder="Username" required autofocus>
        <input type="password" class="form-control" name="Password" placeholder="Password" required>
        <input type="password" class="form-control" name="Confirm" placeholder="Confirm password" required>
        <input type="email" class="form-control" name="Email" placeholder="Email" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
    </form>
</div>
