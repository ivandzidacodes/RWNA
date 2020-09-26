<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar fixed-top navbar-dark bg-dark">
        <a class="navbar-brand" href=".">Home</a>

        <div class="navbar-text">
            <span>
                <?php
                echo '<img src=' . $_SESSION["user_image"] . ' class="rounded-circle" style="width:50px;height:50px;">';
                ?>
            </span>
            <span>
                <?php
                echo "Hello, " . $_SESSION['user_first_name'] . " " . $_SESSION['user_last_name'] . "!";
                ?>
            </span>
            <span>
                <?php
                echo '<a class="btn btn-primary" href="../ui/logout.php">Logout</a>';
                ?>
            </span>
        </div>
    </nav>
    <div class="container">
        <br />
        <br />
        <h2>All Employees</h2>
        <br />
        <table class="table">
            <thead>
                <tr>
                    <th>Employee number</th>
                    <th>Birth date</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Gender</th>
                    <th>Hire date</th>
                </tr>
            </thead>
            <?php
            $result = json_decode($_SESSION["employees"]);
            foreach ($result as $row) {
            ?>
                <tr>
                    <td><?php echo $row->emp_no; ?></td>
                    <td><?php echo $row->birth_date; ?></td>
                    <td><?php echo $row->first_name; ?></td>
                    <td><?php echo $row->last_name; ?></td>
                    <td><?php echo $row->gender; ?></td>
                    <td><?php echo $row->hire_date; ?></td>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
</body>

</html>