<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['save'])){

   $event_id = isset($_GET['id']);
   $event_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
   $mod_by = $_POST['mod_by'];
   $mod_by = filter_var($mod_by, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);
   $date = $_POST['date'];
   $date = filter_var($date, FILTER_SANITIZE_STRING);
   $start_time = $_POST['start_time'];
   $start_time = filter_var($start_time, FILTER_SANITIZE_STRING);
   $end_time = $_POST['end_time'];
   $end_time = filter_var($end_time, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   
   $update_event = $conn->prepare("UPDATE `events` SET  mod_by = ?, title = ?, content = ?, location = ?, date = ?, start_time = ?, end_time = ?, status = ? WHERE id = ?");
   $update_event->execute([ $mod_by, $title, $content, $location, $date, $start_time, $end_time, $status, $event_id]);

   $message[] = 'event updated!';
   
   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../frontendPHP/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `events` WHERE image = ?");
   $select_image->execute([$image]);

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'images size is too large!';
      }elseif($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'please rename your image!';
      }else{
         $update_image = $conn->prepare("UPDATE `events` SET image = ? WHERE id = ?");
         move_uploaded_file($image_tmp_name, $image_folder);
         $update_image->execute([$image, $event_id]);
         if($old_image != $image AND $old_image != ''){
            unlink('../frontendPHP/'.$old_image);
         } 
         $message[] = 'image updated!';
      }
   }
}

if(isset($_POST['delete_event'])){

   $event_id = $_POST['event_id'];
   $event_id = filter_var($event_id, FILTER_SANITIZE_STRING);
   $delete_image = $conn->prepare("SELECT * FROM `events` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../frontendPHP/'.$fetch_delete_image['image']);
   }
   $delete_event = $conn->prepare("DELETE FROM `events` WHERE id = ?");
   $delete_event->execute([$event_id]);
   $message[] = 'event deleted successfully!';

}

if(isset($_POST['delete_image'])){

   $empty_image = '';
   $event_id = $_POST['event_id'];
   $event_id = filter_var($event_id, FILTER_SANITIZE_STRING);
   $delete_image = $conn->prepare("SELECT * FROM `events` WHERE id = ?");
   $delete_image->execute([$event_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `events` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $event_id]);
   $message[] = 'image deleted successfully!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>events</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>


<?php include 'components/admin_header.php' ?>

<section class="post-editor">

   <br><br><br>
   <h1 class="heading">edit event</h1>
   <br><br><br>

	<?php
      $event_id = $_GET['id'];
      $select_events = $conn->prepare("SELECT * FROM `events` WHERE id = ?");
      $select_events->execute([$event_id]);
      if($select_events->rowCount() > 0){
         while($fetch_events = $select_events->fetch(PDO::FETCH_ASSOC)){
   ?>
	
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mod_by" value="<?= $fetch_profile['name']; ?>">
	
	  <input type="hidden" name="old_image" value="<?= $fetch_events['image']; ?>">	
	
	  <select name="status" class="box" required>
      <option value="<?= $fetch_events['status']; ?>" selected><?= $fetch_events['status']; ?></option>
      <option value="active">active</option>
      <option value="deactive">deactive</option>
      </select>
	
      <p>event title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add event title" class="box" value="<?= $fetch_events['title']; ?>">
      
      <p>description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your description..." cols="30" rows="10"><?= $fetch_events['content']; ?></textarea>
     
      <p>place / platform<span>*</span></p>
      <input type="text" name="location" maxlength="100" required placeholder="add event location" class="box" value="<?= $fetch_events['location']; ?>">
      
      <p>date<span>*</span></p>
      <input type="date" name="date" required placeholder="select date" class="box" value="<?= $fetch_events['date']; ?>">
      
      <p>starting time<span>*</span></p>
      <input type="time" name="start_time" min="1:00" max="24:00" required placeholder="select time" class="box" value="<?= $fetch_events['start_time']; ?>">
	  
	  <p>ending time<span>*</span></p>
      <input type="time" name="end_time" min="1:00" max="24:00" required placeholder="select time" class="box" value="<?= $fetch_events['end_time']; ?>">

		<p>image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_events['image'] != ''){ ?>
         <img src="../uploaded_img/<?= $fetch_events['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image">
      <?php } ?>
      <div class="flex-btn">
         <input type="submit" value="save event" name="save" class="btn">
         <a href="admin_events.php" class="option-btn">go back</a>
         <input type="submit" value="delete event" class="delete-btn" name="delete_event">
      </div>
   </form>
    
	<?php
         }
      }else{
         echo '<p class="empty">no events found!</p>';
   ?>
   <div class="flex-btn">
      <a href="admin_events.php" class="option-btn">view events</a>
      <a href="add_event.php" class="option-btn">add events</a>
   </div>
   <?php
      }
   ?>


</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>