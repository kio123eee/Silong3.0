<?php

include 'components/connect.php'; //to connect MyPHPAdmin DB Here

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['delete'])) {
	$delete_id = $_POST['post_id'];
	$delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);
	$select_image = $conn->prepare("SELECT * FROM `posts` WHERE ID = ?");
	$select_image->execute([$delete_id]);
	$fetch_image = $select_image->fetch(PDO::FETCH_ASSOC);
	if($fetch_image['image'] != ''){
		unlink('..uploaded_img/'.$fetch_image['image']);
	}
	$delete_post = $conn->prepare("DELETE FROM `posts` WHERE id = ?");
	$delete_post->execute([$delete_id]);
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
      $select_posts = $conn->prepare("SELECT * FROM `posts`");
      $select_posts->execute();
      $numbers_of_posts = $select_posts->rowCount();
   ?>
   <br><br><br>
   <h1 class="heading">Welcome! <?= $fetch_profile['name']; ?> </h1>
   <br><br><br>

   <div class="box-container">

   

      <div class="box">
         <br><br>
         <h3> Manage Blogposts</h3>
         <br><br>
         <a href="add_post.php" class="btn">add new post</a>
         <br><br><br><br>
      

      <!-- ---------------------------------------------------------------------- -->
      

         <div class="box-container-post">

            <?php
               $select_posts = $conn->prepare("SELECT * FROM `posts`");
               $select_posts->execute();
               
               if($select_posts->rowCount() > 0){
               while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                  $post_id = $fetch_posts['id'];
            ?>
            <form method="post" class="box">
               <input type="hidden" name="post_id" value="<?= $post_id; ?>">
			   <div class="status" style="background: <?php if($fetch_posts['status'] == 'active'){echo 'limegreen';}else{echo 'coral';} ?>;">
			   <?= $fetch_posts['status']; ?>
			   </div>
               <?php if($fetch_posts['image'] != ''){ ?>
               <img src="../frontendPHP/<?= $fetch_posts['image']; ?>" class="image" alt="">
               <?php } ?>
               <div class="title"><?= $fetch_posts['title']; ?></div>
               <div class="content"><?= $fetch_posts['content']; ?></div>
			   <div class="date"><?= $fetch_posts['date']; ?></div>
			   <div class="admin">Created by: <?= $fetch_posts['admin_name']; ?></div>
			   <div class="mod">Modified by: <?= $fetch_posts['mod_by']; ?></div>
               <div class="flex-btn">
                  <a href="edit_post.php?id=<?= $post_id; ?>" class="option-btn">edit</a>
                  <button type="submit" name="delete" class="delete-btn" onclick="return confirm('delete this post?');">delete</button>
               </div>
            </form>
            <?php
               }
               }else{
               echo '<p class="empty">no posts added yet!</p>';
               }
            ?>

         </div>
      </div>


   </div>

</section>

<!-- admin dashboard section ends -->




<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>