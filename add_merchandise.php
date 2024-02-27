<?php
include 'components/connect.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
}

function saveMerchandise($status)
{
    global $conn, $admin_id;
    $admin_name = $_POST['admin_name'];
    $admin_name = filter_var($admin_name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);

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

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $insert_merchandise = $conn->prepare("INSERT INTO `merchandise`(admin_id, admin_name, title, content, image, status) VALUES(?,?,?,?,?,?)");
        $insert_merchandise->execute([$admin_id, $admin_name, $title, $content, $image, $status]);
        return 'Merchandise ' . ($status === 'active' ? 'published' : 'saved as draft');
    } else {
        return 'Error uploading image.';
    }
}

$message = [];
if (isset($_POST['publish'])) {
    $message[] = saveMerchandise('active');
}

if (isset($_POST['draft'])) {
    $message[] = saveMerchandise('deactive');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Merchandise</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>


<?php include 'components/admin_header.php' ?>

<section class="post-editor">

   <br><br><br>
   <h1 class="heading">Add New Merchandise</h1>
   <br><br><br>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="admin_name" value="<?= $fetch_profile['name']; ?>">
      
      <p>Merchandise Image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
     
      <p>Merchandise Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Add merchandise title" class="box">
      
      <p>Description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="Write your description..." cols="30" rows="10"></textarea>

      <br> <br><br>
      <div class="flex-btn">
         <input type="submit" value="Publish Merchandise" name="publish" class="btn">
         <input type="submit" value="Save Draft" name="draft" class="option-btn">
      </div>
   </form>

</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
