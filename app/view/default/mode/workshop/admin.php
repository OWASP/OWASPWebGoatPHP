<script src="<?php echo jf::url()."/script/workshop.js"?>"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    var noOfLessonsData = <?php echo json_encode($this->analytics)?>;

    function drawChart() {
        var data = google.visualization.arrayToDataTable(noOfLessonsData);
        var options = {
            title: 'No of lessons in each category',
            pieHole: 0.4,
            height: 500,
            width: 800
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>
<link rel="stylesheet" type="text/css" href="<?php echo jf::url().'/style/dashboard.css'?>">

<!--navbar
============-->
<div class="navbar navbar-inverse navbar-fixed-top">
    <a href="#" class="navbar-brand">Workshop Mode</a>
    <div class="container">
        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo jf::url()?>">Home</a></li>
                <li><a href="<?php echo jf::url().'/about'?>">About</a></li>
                <li><a href="#">Documentation</a></li>
                <li><a href="<?php echo GITHUB_URL;?>" target="_blank">Github</a></li>
                <li><a href="#contact" data-toggle="modal">Contact</a></li>
                <li><a href="<?php echo jf::url().'/user/logout'?>">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar" id="side-nav">
            <ul class="nav nav-sidebar">
                <li id="overview"><a href="#overview">Overview</a></li>
                <li id="reports"><a href="#reports">Reports</a></li>
                <li id="analytics"><a href="#analytics">Analytics</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li id="create"><a href="#create">Create Users</a></li>
                <li id="delete"><a href="#delete">Delete Users</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li id="settings"><a href="#settings">Lesson Settings</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header" id="heading"></h1>
            <div id="main-content">

                <div class="hidden" id="overview-content">
                    <p>Total Users: <?php echo $this->totalUsers;?></p>
                    <p>Total Categories: <?php echo $this->totalCategories;?></p>
                    <p>Total Lessons: <?php echo $this->totalLessons;?></p>
                    <p>Total Visible Lessons: <?php echo $this->totalVisibleLessons;?></p>
                </div>

                <div class="hidden" id="reports-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Lesson Name</th>
                                    <th>Completed By</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($this->reports as $lesson => $users): ?>
                                    <tr>
                                        <td><?php echo $lesson; ?></td>
                                        <td><?php foreach ($users as $userName) { echo $userName."<br>";} ?></td>
                                        <td><?php echo count($users);?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="analytics-content">
                    <div id="donutchart" style="width: 800px; height: 500px;"></div>
                </div>

                <div class="hidden" id="create-user-content">
                    <form class="form-horizontal" role="form" method="POST" action="user/create">
                        <div class="form-group">
                            <label for="username" class="col-sm-2">User Name:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="username" id="username" placeholder="User Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2">Password:</label>
                            <div class="col-sm-3">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-3">
                                <input type="submit" name="submit" value="Create" class="btn btn-default">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="hidden" id="delete-user-content">
                    <form class="form-inline" role="form" method="POST" id="" action="user/delete">
                        <div class="form-group">
                            <label for="username" class="sr-only">User Name:</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="User Name">
                        </div>
                        <input type="submit" name="submit" value="Delete" class="btn btn-default">
                    </form>
                    <br>
                    <h4>List of Users:</h4>
                    <div class="row">
                        <div class="col-sm-3">
                            <table class="table table-triped">
                                <thead>
                                <tr>
                                    <th>User Name</th>
                                </tr>
                                </thead>
                                <tbody id="delete-user-list">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="lesson-settings-content">
                    <h4>List of lessons:</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Lesson Name</th>
                                    <th>Visibility</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($this->allCategoryLesson as $category => $lessons): ?>
                                    <?php foreach($lessons as $lesson):?>
                                        <tr>
                                            <td><?php echo $category; ?></td>
                                            <td><?php echo $lesson[0]; ?></td>
                                            <td>
                                                <div class="btn-group btn-toggle">
                                                    <?php if (!empty($this->hiddenLessons)
                                                        && in_array($lesson[0], $this->hiddenLessons)):?>
                                                        <button class="btn btn-xs btn-default">ON</button>
                                                        <button class="btn btn-xs btn-primary active">OFF</button>
                                                    <?php else:?>
                                                        <button class="btn btn-xs btn-primary active">ON</button>
                                                        <button class="btn btn-xs btn-default">OFF</button>
                                                    <?php endif;?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--End main-content-->
            </div>
        </div>
    </div>
</div>
