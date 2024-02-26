<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <br><br>
   
   <a href="index.php" class="logo"><br><img src="img/logohead.png" alt="logo">
   <br><br>
   Admin<span>Panel</span></a>

   <div class="profile">
      
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <p><?= $fetch_profile['name']; ?></p>
   </div>

   <nav class="navbar">
      <a href="admin_events.php"><i class="fas fa-home"></i> <span>EVENTS</span></a>
      <a href="admin_posts.php"><i class="fas fa-pen"></i> <span>BLOG POSTS</span></a>
      <a href="admin_merchandise.php"><i class="fas fa-eye"></i> <span>MERCHANDISE</span></a>
      <a href="components/admin_logout.php" style="color:var(--red);" onclick="return confirm('logout from the website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
   </nav>

</header>

<div id="menu-btn" class="fas fa-bars"></div>