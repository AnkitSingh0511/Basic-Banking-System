<?php
include 'config.php';

if (isset($_POST['submit'])) {
  $from = $_GET['id'];
  $toUser = $_POST['to'];
  $amnt = $_POST['amount'];

  $sql = "SELECT * from users where id=$from";
  $query = mysqli_query($conn, $sql);
  $sql1 = mysqli_fetch_array($query);

  $sql = "SELECT * from users where id=$toUser";
  $query = mysqli_query($conn, $sql);
  $sql2 = mysqli_fetch_array($query);


  if ($amnt > $sql1['credits']) {

    echo '<script type="text/javascript">';
    echo ' alert("Insufficient Balance")';
    echo '</script>';
  } else if ($amnt == 0) {
    echo "<script type='text/javascript'>
    alert('Enter Amount Greater than Zero');
    </script>";
  } else {

    $newCredit = $sql1['credits'] - $amnt;
    $sql = "UPDATE users set credits=$newCredit where id=$from";
    mysqli_query($conn, $sql);

    $newCredit = $sql2['credits'] + $amnt;
    $sql = "UPDATE users set credits=$newCredit where id=$toUser";
    mysqli_query($conn, $sql);

    $sender = $sql1['name'];
    $receiver = $sql2['name'];
    $sql = "INSERT INTO transaction(`sender`, `receiver`, `credits`) VALUES ('$sender','$receiver','$amnt')";
    $tns = mysqli_query($conn, $sql);
    if ($tns) {
      echo "<script type='text/javascript'>
                    alert('Transaction Successfull!');
                    window.location='transactionDetails.php';
                </script>";
    }
    $newCredit = 0;
    $amnt = 0;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Basic Banking System</title>
  <!-- CSS only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
  <!-- JavaScript Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="style.css">

</head>

<body style="background-image: url(https://securionpay.com/wp-content/uploads/2020/02/OCT1.png);
  background-repeat: no-repeat; background-size: cover;">
  <?php
  require 'config.php';
  $query = "SELECT * FROM users";
  $result = mysqli_query($conn, $query);
  ?>
  <!----NAVBAR------->
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark navbar sticky-top" style="height: 85px">  
    <div class="container-fluid">
      <a class="navbar-brand">
      <p class="logo-text">
            <img
              src="https://th.bing.com/th/id/R37ba32d7b3e19674d360bc14aec478d5?rik=vTyUv83VD606dA&riu=http%3a%2f%2fwww.pngmart.com%2ffiles%2f5%2fBank-PNG-Pic.png&ehk=UmyQNOcFFTlp1XR9Fmps7TdQD5fplbcaHFgveUF%2brqA%3d&risl=&pid=ImgRaw"
              alt="LOGO"
              width="14%"
              height="9%"
            >
            TSF Bank
        </p>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link " href="index.html">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active " href="viewusers.php">Customer Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="transactionDetails.php">Transaction History</a>
          </li>
  </nav>

  <div class="container">
    <h1 style="font-size: 50px; letter-spacing: 5px; text-shadow: 2.5px 3.5px 3px whitesmoke; color: #2d4262">Money Transfer</h1>

    <?php
    include 'config.php';
    $sid = $_GET['id'];
    $sql = "SELECT * FROM  users where id=$sid";
    $query = mysqli_query($conn, $sql);
    if (!$query) {
      echo "Error " . $sql . "<br/>" . mysqli_error($conn);
    }
    $rows = mysqli_fetch_array($query);
    ?>
    <form method="post" name="tcredit" class="tabletext"><br />
      <label style="text-align: left;"> FROM: </label><br />
      <div>
        <table class="table table-dark table-hover table-striped table-bordered">
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Amount Transferred</th>
          </tr>
          <tr class="table-light">
            <td><?php echo $rows['id'] ?></td>
            <td><?php echo $rows['name'] ?></td>
            <td><?php echo $rows['email'] ?></td>
            <td><?php echo $rows['credits'] ?></td>
          </tr>
        </table>
      </div>
      <br />
      <label>TO:</label>
      <select class=" form-control" name="to" style="margin-bottom:5%;" required>
        <option value="" disabled selected> </option>
        <?php
        include 'config.php';
        $sid = $_GET['id'];
        $sql = "SELECT * FROM users where id!=$sid";
        $query = mysqli_query($conn, $sql);
        if (!$query) {
          echo "Error " . $sql . "<br/>" . mysqli_error($conn);
        }
        while ($rows = mysqli_fetch_array($query)) {
        ?>
          <option class="table text-center table-striped " value="<?php echo $rows['id']; ?>">

            <?php echo $rows['name']; ?>
            <!--(Credits:
                    <?php echo $rows['credits']; ?> )-->

          </option>
        <?php
        }
        ?>
      </select>
      <label>AMOUNT:</label>
      <input type="number" id="amm" class="form-control" name="amount" min="0" required /> <br />
      <div class="text-center btn3">
        <button class="button2" name="reset" type="reset" id="myBtn" style="margin:8px;">Reset</button>
        <button class="button2" name="submit" type="submit" id="myBtn" style="margin:8px;">Proceed</button>

      </div>
    </form>
  </div>

  <script>
    var d = new Date();
    document.getElementById("demo").innerHTML = d;
  </script>
  <!-----FOOTER-->
  <div class="footer" style="    
        
        background-color: #212529;
        color: white;
        font-family: Cambria, Cochin, Georgia, Times, serif;
      ">
    <p>© Ankit Singh</p>
  </div>
</body>

</html>