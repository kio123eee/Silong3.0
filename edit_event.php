<?php

error_reporting(E_ALL & ~E_DEPRECATED);

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

function updateEvent($status)
{
    global $conn, $admin_id;
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
    $event_id = $_GET['id'];
    $event_id = filter_var($event_id, FILTER_SANITIZE_NUMBER_INT);

    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $uploadDir = '/app/storage/uploads/';
    $image_folder = $uploadDir . $image;

    // Check if the upload directory exists, create it if not
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    } else {
        // If directory already exists, ensure permissions are set correctly
        chmod($uploadDir, 0777);
    }

    // Check if a new image is uploaded
    if (!empty($image)) {
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_folder_path = $image_folder . basename($image);

        if ($image_size > 2000000) {
            return 'Image size is too large!';
        } else {
            // Move the uploaded image to the storage directory
            if (move_uploaded_file($image_tmp_name, $image_folder_path)) {
                // Update the event details including the image
                $update_event = $conn->prepare("UPDATE `events` SET mod_by = ?, title = ?, content = ?, location = ?, date = ?, start_time = ?, end_time = ?, image = ?, status = ? WHERE id = ?");
                $update_event->execute([$mod_by, $title, $content, $location, $date, $start_time, $end_time, $image, $status, $event_id]);
                return 'Event updated!';
            } else {
                return 'Error uploading image.';
            }
        }
    } else {
        // If no new image is uploaded, update only the event details
        $update_event = $conn->prepare("UPDATE `events` SET mod_by = ?, title = ?, content = ?, location = ?, date = ?, start_time = ?, end_time = ?, status = ? WHERE id = ?");
        $update_event->execute([$mod_by, $title, $content, $location, $date, $start_time, $end_time, $status, $event_id]);
        return 'Event updated!';
    }
}

$message = [];
if (isset($_POST['save'])) {
    $message[] = updateEvent('deactive');
}

if (isset($_POST['publish'])) {
    $message[] = updateEvent('active');
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
      
      <p>event image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_events['image'] != ''){ ?>
         <img src="/storage/uploads/<?= $fetch_events['image']; ?>" class="image" alt="">
      <?php } ?>

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

      <br> <br><br>
      <div class="flex-btn">
         <input type="submit" value="save event" name="save" class="btn">
         <input type="submit" value="publish event" name="publish" class="option-btn">
      </div>
   </form>
    
   <?php
         }
      }else{
         echo '<p class="empty">no events found!</p>';
      }
   ?>

</section>

<?php include 'components/admin_footer.php' ?>

<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
