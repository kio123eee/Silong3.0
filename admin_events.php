<?php

include 'components/connect.php'; //to connect MyPHPAdmin DB Here

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['delete'])) {
	$delete_id = $_POST['event_id'];
	$delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
	$select_image = $conn->prepare("SELECT * FROM `events` WHERE ID = ?");
	$select_image->execute([$delete_id]);
	$fetch_image = $select_image->fetch(PDO::FETCH_ASSOC);
	if($fetch_image['image'] != ''){
		unlink('../frontendPHP/'.$fetch_image['image']);
	}
	$delete_event = $conn->prepare("DELETE FROM `events` WHERE id = ?");
	$delete_event->execute([$delete_id]);
	$message[] = 'post deleted successfuly!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <br>
   <?php
      $select_events = $conn->prepare("SELECT * FROM `events`");
      $select_events->execute();
      $numbers_of_events = $select_events->rowCount();
   ?>
   <br><br><br>
   <h1 class="heading">Welcome! <?= $fetch_profile['name']; ?> </h1>
   <br><br><br>

   <div class="box-container">

   

      <div class="box">
         <br><br>
         <h3> Manage Events</h3>
         <br><br>
         <a href="add_event.php" class="btn">add new event</a>
         <br><br><br><br>
      

      <!-- ---------------------------------------------------------------------- -->
      

         <div class="box-container-post">

            <?php
               $select_events = $conn->prepare("SELECT * FROM `events`");
               $select_events->execute();
               
               if($select_events->rowCount() > 0){
               while($fetch_events = $select_events->fetch(PDO::FETCH_ASSOC)){
                  $event_id = $fetch_events['id'];
            ?>
            <form method="post" class="box">
               <input type="hidden" name="event_id" value="<?= $event_id; ?>">
			   <div class="status" style="background: <?php if($fetch_events['status'] == 'active'){echo 'limegreen';}else{echo 'coral';} ?>;">
			   <?= $fetch_events['status']; ?>
			   </div>
               <?php if($fetch_events['image'] != ''){ ?>
               <img src="../frontendPHP/<?= $fetch_events['image']; ?>" class="image" alt="">
               <?php } ?>
               <div class="title"><?= $fetch_events['title']; ?></div>
               <div class="content"><?= $fetch_events['content']; ?></div>
			   <div class="date"><?= $fetch_events['date']; ?></div>
			   <div class="location"><?= $fetch_events['location']; ?></div>
			   <div class="start_time"><?= $fetch_events['start_time']; ?></div>
			   <div class="end_time"><?= $fetch_events['end_time']; ?></div>
			   <div class="admin">Created by: <?= $fetch_events['admin_name']; ?></div>
			   <div class="mod">Modified by: <?= $fetch_events['mod_by']; ?></div>
               <div class="flex-btn">
                  <a href="edit_event.php?id=<?= $event_id; ?>" class="option-btn">edit</a>
                  <button type="submit" name="delete" class="delete-btn" onclick="return confirm('delete this event?');">delete</button>
               </div>
            </form>
            <?php
               }
               }else{
               echo '<p class="empty">no events added yet!</p>';
               }
            ?>

         </div>
      </div>


   </div>

</section>

<!-- admin dashboard section ends -->




<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>