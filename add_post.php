<?php
include 'components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['publish']) || isset($_POST['draft'])) {
    $admin_name = $_POST['admin_name'];
    $admin_name = filter_var($admin_name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $date = $_POST['date'];
    $date = filter_var($date, FILTER_SANITIZE_STRING);
    $status = (isset($_POST['publish'])) ? 'active' : 'deactive';
    $start_time = date('Y-m-d H:i:s'); // Default value for start_time
    $end_time = date('Y-m-d H:i:s'); // Default value for end_time
    
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../frontendPHP/'.$image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ?");
    $select_image->execute([$image]);

    if (isset($image)) {
        if ($select_image->rowCount() > 0 && $image != '') {
            $message[] = 'image name repeated!';
        } elseif ($image_size > 2000000) {
            $message[] = 'images size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = '';
    }

    if ($select_image->rowCount() > 0 && $image != '') {
        $message[] = 'please rename your image!';
    } else {
        $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, admin_name, title, content, date, image, status, start_time, end_time) VALUES(?,?,?,?,?,?,?,?,?)");
        $insert_post->execute([$admin_id, $admin_name, $title, $content, $date, $image, $status, $start_time, $end_time]);
        $message[] = (isset($_POST['publish'])) ? 'post published!' : 'draft saved!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>blogposts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>


<?php include 'components/admin_header.php' ?>

<section class="post-editor">

   <br><br><br>
   <h1 class="heading">add new blogpost</h1>
   <br><br><br>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="admin_name" value="<?= $fetch_profile['name']; ?>">
      
      <p>post image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
     
      <p>post title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add post title" class="box">
      
      <p>description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your description..." cols="30" rows="10"></textarea>
     
      <input type="hidden" name="date" value="<?= date('Y-m-d'); ?>">

      <br> <br><br>
      <div class="flex-btn">
         <input type="submit" value="publish post" name="publish" class="btn">
		 <input type="submit" value="save draft" name="draft" class="option-btn">
      </div>
   </form>

</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>