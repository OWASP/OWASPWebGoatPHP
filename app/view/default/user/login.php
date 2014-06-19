<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/signin.css'?>">

<div class="container">
    <form class="form-signin" role="form" method="post" action="">
        <?php
            if (isset($this->Result) and !$this->Result)
            {
                echo "<div class='alert alert-danger'>Invalid credentials</div>";
                $this->Username=$_POST["Username"];
            }
        ?>

        <h2 class="form-signin-heading">Please sign in</h2>
        <input type="text" class="form-control"  name="Username" placeholder="Username" value="<?php if(isset($this->Username)) echo $this->Username ?>" required autofocus>
        <input type="password" class="form-control" name="Password" placeholder="Password" required>
        <label class="checkbox">
            <input type="checkbox" value="remember" name="remember-me"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    </form>
</div>