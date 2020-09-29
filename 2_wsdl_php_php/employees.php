<?php

if(isset($_GET["employeeName"]) && isset($_GET["dateFrom"]) && isset($_GET["dateTo"])) {

  $name = $_GET['employeeName'];
  $dateFrom = $_GET['dateFrom'];
  $dateTo = $_GET['dateTo'];

  try {
    $sClient = new SoapClient(
      'http://localhost/2_wsdl_php_php/employeesService.wsdl',
      array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => 1, 'exceptions' => true)
    );

    $response = $sClient->getEmployees($name, $dateFrom, $dateTo);
  } catch (SoapFault $e) {
    $error = $e->getMessage();
    var_dump($e);
  }
} else {
  $error = "Please provide all data.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </head>

<body class="container">
  <div class="row">
    <?php
    
    if (count($response) == 0) {
      echo '<h1>No employees found for \'' . $name . '\'</h1>';
      return;
    }
    echo '<h3>Employees for <strong>\'' . $name . '\'</strong></h3><br/><br/><br/>';
    echo '<table class="table">
      <thead>
      <tr>
      <th>Employee no</th>
      <th>First name</th>
      <th>Last name</th>
      <th>Department name</th>
      <th>From date</th>
      <th>To date</th>
      </tr>
      </thead>';

    for ($j = 0; $j < sizeof($response); $j++) {
      echo '<tr>
        <td>' . $response[$j]["emp_no"] . '</td>
        <td>' . $response[$j]["first_name"] . '</td>
        <td>' . $response[$j]["last_name"] . '</td>
        <td>' . $response[$j]["dept_name"] . '</td>
        <td>' . $response[$j]["from_date"] . '</td>
        <td>' . $response[$j]["to_date"] . '</td>
        </tr>';
    }
    echo '</table>'
    ?>
  </div>
  </div>
  </div>
</body>
</html>