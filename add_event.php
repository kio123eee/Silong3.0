<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['publish'])){

   $admin_name = $_POST['admin_name'];
   $admin_name = filter_var($admin_name, FILTER_SANITIZE_STRING);
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
   $status = 'active';
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../frontendPHP/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `events` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if(isset($image)){
      if($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'image name repeated!';
      }elseif($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   }else{
      $image = '';
   }

   if($select_image->rowCount() > 0 AND $image != ''){
      $message[] = 'please rename your image!';
   }else{
      $insert_event = $conn->prepare("INSERT INTO `events`(admin_id, admin_name, title, content, location, date, start_time, end_time, image, status, mod_by) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
$insert_event->execute([$admin_id, $admin_name, $title, $content, $location, $date, $start_time, $end_time, $image, $status, $admin_id]);

      $message[] = 'event published!';
   }
}

if(isset($_POST['draft'])){

   $admin_name = $_POST['admin_name'];
   $admin_name = filter_var($admin_name, FILTER_SANITIZE_STRING);
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
   $status = 'deactive';
   
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../frontendPHP/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `events` WHERE image = ?");
   $select_image->execute([$image]);

   if(isset($image)){
      if($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'image name repeated!';
      }elseif($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   }else{
      $image = '';
   }

   if($select_image->rowCount() > 0 AND $image != ''){
      $message[] = 'please rename your image!';
   }else{
      $insert_event = $conn->prepare("INSERT INTO `events`(admin_id, admin_name, title, content, location, date, start_time, end_time, image, status) VALUES(?,?,?,?,?,?,?,?,?,?)");
      $insert_event->execute([$admin_id, $admin_name, $title, $content, $location, $date, $start_time, $end_time, $image, $status]);
      $message[] = 'draft saved!';
   }
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
   <h1 class="heading">add new event</h1>
   <br><br><br>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="admin_name" value="<?= $fetch_profile['name']; ?>">
      
      <p>event image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
     
      <p>event title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add event title" class="box">
      
      <p>description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your description..." cols="30" rows="10"></textarea>
     
      <p>place / platform<span>*</span></p>
      <input type="text" name="location" maxlength="100" required placeholder="add event location" class="box">
      
      <p>date<span>*</span></p>
      <input type="date" name="date" required placeholder="select date" class="box">
      
      <p>starting time<span>*</span></p>
      <input type="time" name="start_time" min="1:00" max="24:00" required placeholder="select time" class="box" />
	  
	  <p>ending time<span>*</span></p>
      <input type="time" name="end_time" min="1:00" max="24:00" required placeholder="select time" class="box" />

      <br> <br><br>
      <div class="flex-btn">
         <input type="submit" value="publish event" name="publish" class="btn">
		 <input type="submit" value="save draft" name="draft" class="option-btn">
      </div>
   </form>

</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>