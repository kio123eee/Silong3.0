<?php
    $db_name = 'mysql:host=viaduct.proxy.rlwy.net;port=45564;dbname=railway';
    $user_name = 'root'; // Replace 'your_mysql_username' with your actual MySQL username
    $user_password = 'fEg4GAdG4-AHBb-4EhCceCG-b345cG6C'; // Use your MySQL password here

    try {
        $conn = new PDO($db_name, $user_name, $user_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="communityNews.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SILONG</title>
    </head>

    <body>
        <nav>
            <div class="nav-bar">
                <span class="logo"> <a href="index.php"> <img src="logohead.png" class="logo_img"> </a> </span>
                
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

        <div class="events-section">
            <?php
            // Check if there are any events in the result set
            if ($result->rowCount() > 0) {
                // Output data of each row
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['status'] == "active"){
                        ?>
                        <div class="event-card" id="event-<?php echo $row['id']; ?>">
                            <img src="/storage/uploads/<?php echo $row['image']; ?>" alt="Event Image">
                            <div class="event-details">
                                <h2><?php echo $row['title']; ?></h2>
                                <p><?php echo $row['content']; ?></p>
                                <!-- Add more details here as needed -->
                            </div>
                        </div>
                        <?php
                    }
                }
            } else {
                echo "0 results";
            }
            ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewLinks = document.querySelectorAll('.view-link');

        viewLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const eventCard = this.parentElement;
                const additionalInfo = eventCard.querySelector('.additional-info');

                additionalInfo.style.display = additionalInfo.style.display === 'none' ? 'block' : 'none';
            });
        });
    });
</script>
