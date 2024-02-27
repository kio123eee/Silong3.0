<?php
    $db_name = 'mysql:host=viaduct.proxy.rlwy.net;port=45564;dbname=railway';
    $user_name = 'root'; // Replace 'your_mysql_username' with your actual MySQL username
    $user_password = 'fEg4GAdG4-AHBb-4EhCceCG-b345cG6C'; // Use your MySQL password here

    // Establish database connection
    try {
        $conn = new PDO($db_name, $user_name, $user_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    $sql = "SELECT * FROM merchandise";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="Donations.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>SILONG</title>
    </head>

    <body>
        <nav>
            <div class="nav-bar">
                <span class="logo"><a href="index.php"><img src="logohead.png" class="silong"></a></span>
                
                <div class="menu">
                    <ul class="nav-links">
                        <li> <a href="index.php">HOME</a> </li>
                        <li> <a href="aboutUs.php">ABOUT US</a> </li>
                        <li> <a href="donations.php">DONATIONS</a> </li>
                        <li> <a href="communityNews.php">COMMUNITY NEWS</a> </li>
                        <li class="dropdown">
                            <a href="#">JOIN US ▼</a>
                            <div class="dropdown-content">
                                <a href="https://bit.ly/SilongCy6MembershipApplication">VOLUNTEER</a>
                                <a href="https://bit.ly/SilongAmbassadorApplication">AMBASSADOR</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    <div class="header2">            
        <h1>LET THEM KNOW SOMEONE CARES.</h1>
    </div>
    <div class="header2"> 
        <h1>DONATE FOR THE FUTURE.</h1>
    </div>
    <br>
    <h3 id="banktransfer">BANK TRANSFER</h3>
    <div id="main-container">
        <div id="left-container">
            <div id="bank-details">
                <br>
                <br>
                <h4 class="bank">BDO SAVINGS</h4>
                <br>
                <h4>ACCOUNT DETAILS</h4>
                <h4 >Patrick Joseph Tiamzon</h4>
                <h4>001271472102</h4>
                <br>
                <h4 class="bank">BPI SAVINGS</h4>
                <br>
                <h4>ACCOUNT DETAILS</h4>
                <h4>Kichi Kyna Lim</h4>
                <h4>0119086031</h4>
            </div>
        </div>
    
        <div id="right-container">
            <div id="qr-container">
            <img id="qr-image" src="QR.png" alt="QR Code">
            <br>
            <br>
            <h2 id="scan-me">SCAN ME!</h2>
            </div>
        </div>
    </div>
<br>
<br>
<br>
        <div id="new-container">
            <h2>FUNDRAISER</h2>
            <h2>PROJECT ALAGA MERCHANDISE</h2>
            <h3>UNITING FOR A CAUSE</h3>
            <div class="image-container">
                <?php
                // Check if there are any merchandise in the result set
                if ($result->rowCount() > 0) {
                    // Output data of each row
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['status'] == "active"){
                            ?>
                            <div class="image-item">
                                <a href="https://docs.google.com/forms/d/e/1FAIpQLSfp_-_yBrH5TVIYnKfYMY3ILpqAGlMQI6NOnqvIcv1iBg12BQ/closedform"> <img src="/storage/uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>"> </a>
                                <h4><?php echo $row['title']; ?></h4>
                                <p><?php echo $row['content']; ?></p>
                            </div>
                            <?php
                        }
                    }
                } else {
                    echo "No merchandise available";
                }
                ?>
            </div>
        </div>

        <div class="header">
        </div>

        <footer>
            <div class="footer-content">
                <div class="footer-center">
                    <div class="footer-links">
                        <div class="footer-logo">
                            <a href="index.php">
                            <img src="logohead.png" class="silong" alt="Silong Logo">
                            </a>
                        </div>
                        <a href="#">CONTACT US</a>
                        <a href="#https://www.facebook.com/SilongPH"><i class="fab fa-facebook"></i></a>
                        <a href="#https://www.instagram.com/silongphilippines/"><i class="fab fa-instagram"></i></a>
                        <span class="separator">|</span>
                        <span class="email">SILONGPHILIPPINES@GMAIL.COM</span>
                    </div>
                </div>
                <div class="footer-right">
                    <a href="admin_login.php" class="admin-login">Admin Login</a>
                </div>
            </div>
        </footer>
    </body>
</html> 
