<?php

include 'components/connect.php'; //to connect MyPHPAdmin DB Here

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['delete_merchandise'])) {
	$delete_id = $_POST['merchandise_id'];
	$delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
	$select_image = $conn->prepare("SELECT * FROM `merchandise` WHERE id = ?");
	$select_image->execute([$delete_id]);
	$fetch_image = $select_image->fetch(PDO::FETCH_ASSOC);
	if($fetch_image['image'] != ''){
		// Use the correct file path
		unlink('/storage/uploads/'.$fetch_image['image']);
	}
	$delete_merchandise = $conn->prepare("DELETE FROM `merchandise` WHERE id = ?");
	$delete_merchandise->execute([$delete_id]);
	$message[] = 'Merchandise deleted successfully!';
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
      $select_merchandises = $conn->prepare("SELECT * FROM `merchandise`");
      $select_merchandises->execute();
      $numbers_of_merchandise = $select_merchandises->rowCount();
   ?>
   <br><br><br>
   <h1 class="heading">Welcome! <?= $fetch_profile['name']; ?> </h1>
   <br><br><br>

   <div class="box-container">

      <div class="box">
         <br><br>
         <h3> Manage Merchandise</h3>
         <br><br>
         <a href="add_merchandise.php" class="btn">add new merchandise</a>
         <br><br><br><br>

         <div class="box-container-post">

            <?php
               $select_merchandises = $conn->prepare("SELECT * FROM `merchandise`");
               $select_merchandises->execute();
               
               if($select_merchandises->rowCount() > 0){
               while($fetch_merchandises = $select_merchandises->fetch(PDO::FETCH_ASSOC)){
                  $merchandise_id = $fetch_merchandises['id'];
            ?>
            <form method="post" class="box">
               <input type="hidden" name="merchandise_id" value="<?= $merchandise_id; ?>">
			   <div class="status" style="background: <?php if($fetch_merchandises['status'] == 'active'){echo 'limegreen';}else{echo 'coral';} ?>;">
			   <?= $fetch_merchandises['status']; ?>
			   </div>
               <?php if($fetch_merchandises['image'] != ''){ ?>
               <img src="/storage/uploads/<?= $fetch_merchandises['image']; ?>" class="image" alt="">
               <?php } ?>
               <div class="title"><?= $fetch_merchandises['title']; ?></div>
               <div class="content"><?= $fetch_merchandises['content']; ?></div>
			   <div class="admin">Created by: <?= $fetch_merchandises['admin_name']; ?></div>
			   <div class="mod">Modified by: <?= $fetch_merchandises['mod_by']; ?></div>
               <div class="flex-btn">
                  <a href="edit_merchandise.php?id=<?= $merchandise_id; ?>" class="option-btn">edit</a>
                  <button type="submit" name="delete_merchandise" class="delete-btn" onclick="return confirm('delete this merchandise?');">delete</button>
               </div>
            </form>
            <?php
               }
               }else{
               echo '<p class="empty">no merchandise added yet!</p>';
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
