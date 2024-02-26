<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_name = 'mysql:host=viaduct.proxy.rlwy.net;port=45564;dbname=railway';
    $user_name = 'root'; // Replace 'your_mysql_username' with your actual MySQL username
    $user_password = 'fEg4GAdG4-AHBb-4EhCceCG-b345cG6C'; // Use your MySQL password here

    try {
        // Create connection
        $conn = new PDO($db_name, $user_name, $user_password);
        
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement
        $sql = "INSERT INTO ambassador_forms (full_name, facebook_link, instagram_username, email, contact_number, address, birthday, inspiration, rating, justification, issue)
        VALUES (:full_name, :facebook_link, :instagram_username, :email, :contact_number, :address, :birthday, :inspiration, :rating, :justification, :issue)";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':full_name', $_POST['full-name']);
        $stmt->bindParam(':facebook_link', $_POST['facebook-link']);
        $stmt->bindParam(':instagram_username', $_POST['instagram-username']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':contact_number', $_POST['contact-number']);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':birthday', $_POST['birthday']);
        $stmt->bindParam(':inspiration', $_POST['inspiration']);
        $stmt->bindParam(':rating', $_POST['rating']);
        $stmt->bindParam(':justification', $_POST['justification']);
        $stmt->bindParam(':issue', $_POST['issue']);

        // Execute statement
        $stmt->execute();

    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn = null;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="volunteerForm.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>SILONG</title>
    </head>

    <body>
        <nav>
            <div class="nav-bar">
                <span class="logo"><img src="logohead.png" class="logo_img"></span>
                
                <div class="menu">
                    <ul class="nav-links">
                        <li> <a href="index.php">HOME</a> </li>
                        <li> <a href="aboutUs.php">ABOUT US</a> </li>
                        <li> <a href="donations.php">DONATIONS</a> </li>
                        <li> <a href="communityNews.php">COMMUNITY NEWS</a> </li>
                        <li class="dropdown">
                            <a href="#">JOIN US ▼</a>
                            <div class="dropdown-content">
                                <a href="volunteerForm.php">VOLUNTEER</a>
                                <a href="ambassadorForm.php">AMBASSADOR</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1>GET INVOLVED</h1>
        <h3>JOIN US IN THE PURSUIT OF ANIMAL AND ENVIRONMENTAL WELFARE!</h3>
        <section class="volunteer-form">
            <div class="form-container">
                <h2>Volunteer Form</h2>
                <form action="#" method="POST">
                    <label for="full-name">Full Name:</label>
                    <input type="text" id="full-name" name="full-name" required>

                    <label for="facebook-link">Facebook Link:</label>
                    <input type="text" id="facebook-link" name="facebook-link" required>

                    <label for="instagram-username">Instagram Username:</label>
                    <input type="text" id="instagram-username" name="instagram-username" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="contact-number">Contact Number:</label>
                    <input type="tel" id="contact-number" name="contact-number" required>

                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>

                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" required>

                    <label for="inspiration">What inspired you to become an advocate for Animal Welfare?</label>
                    <textarea id="inspiration" name="inspiration" required></textarea>

                    <label for="rating">From 1-10, rate the status of the Animal Welfare Program in the Philippines. Justify your answer:</label>
                    <input type="number" id="rating" name="rating" min="1" max="10" required>
                    <textarea id="justification" name="justification" required></textarea>

                    <label for="issue">What's the most pressing Animal Welfare related issue in the Philippines? Explain why:</label>
                    <textarea id="issue" name="issue" required></textarea>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </section>
        <footer>
            <div class="footer-content">
                <div class="footer-center">
                    <div class="footer-links">
                        <div class="footer-logo">
                            <img src="logohead.png" class="silong" alt="Silong Logo">
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
