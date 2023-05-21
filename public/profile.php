<?php 
session_start();
if(isset($_SESSION['email'])){
  $email = $_SESSION['email'];
  $id = $_SESSION['id'];
include('./header.php');
include_once ('../private/conn.php');


if(isset($_POST['submit'])){
  if (empty( $_FILES["picture"]["name"])) {
    $errors[] = 'Picture is required';
  }






if(!isset($errors)){
  $image = $_FILES['picture']['name'];
  $image_size = $_FILES['picture']['size'];
  $image_error = $_FILES['picture']['error'];
  $file = explode('.',$image);
  $fileActual = strtolower(end($file));
  $allowed = array('png','jpg','jpge','svg');
  if(in_array($fileActual,$allowed)){
      if($image_error === 0){
          if($image_size < 4000000){
                  $image_new_name = uniqid('',true).'-'.$image;
                  $target = '../private/profile_picture/'.$image_new_name;
                  $sql = "UPDATE users SET picture='$image_new_name' WHERE email='$email'";
                  if(!empty($image)){
                      mysqli_query($conn,$sql);
                      if(move_uploaded_file($_FILES['picture']['tmp_name'],$target)){
                          header('location:profile.php');
                      }
                  } 
              }else{
              $errors[] = 'Your picture is to Big';
          }
  
          }
  
      }else{
          $errors[] = 'Check picture type';
      }
}

}

$user_info = mysqli_query($conn,"SELECT * FROM `users`");
while($data = mysqli_fetch_array($user_info)){


?>

<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../css/style.css" >
</head>
<html>
<body>
    
<div class="container">

<?php if(!empty($errors)): ?>

<div class="alert alert-danger">
<?php foreach($errors as $error): ?>
    <div><?php echo '- '.$error ;?></div>

<?php endforeach ?>
</div>

<?php endif; ?> 


<div class="text-center">
<table class="table">
  <thead>
    <tr>
      <th scope="col">Id</th>
      <th scope="col">Email</th>
      <th scope="col">Username</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><?php echo $data['id'];?> </th>
      <td ><?php echo $data['email'];?> </td>
      <td><?php echo $data['username'];?> </td>
    </tr>
  </tbody>
</table>
<!-- Start -->
<?php 
$getcount = mysqli_query($conn,"SELECT * FROM `posts` WHERE user_id='$id'");
if(mysqli_num_rows($getcount) < 1){
echo '<div class="alert alert-danger" role="alert">No Posts Here</div>';

}else{



?>
<h1>Your Posts</h1>
<table class="table">
  <thead>
    <tr>

      <th scope="col">Text</th>
      <th scope="col">Picture</th>
      <th scope="col">Create At</th>
      <th scope="col">Delete</th>
     
    </tr><?php }; ?>
  </thead>
  <tbody>
    <tr>
<?php
$user = mysqli_query($conn,"SELECT * FROM `posts` WHERE user_id='$id'");
while($data= mysqli_fetch_array($user)){
?>


      <td><?php echo $data['text'];?> </td>
      <td> <?php echo "<img id='img' src='../private/posts_picture/".$data['picture']."'' width='50px'  class='rounded' >";?> </td>
      <td ><?php echo $data['create_at'];?> </td>
      <td><a href="delete.php?id=<?php echo $data['post_id'];?>"  class="btn btn-danger">Delete</a></td>
  </tr>
<!-- End -->
<?php } ?>


  </tbody>
</table>
<?php 
$user_info = mysqli_query($conn,"SELECT * FROM `users` WHERE email='$email'");
while($data = mysqli_fetch_array($user_info)){
?>

<h2>Welcome, <span><?php echo $data['username'] ; ?></span></h2>

<?php echo "<img src='../private/profile_picture/".$data['picture']."'' width='200px'  class='rounded' >"; ?>


</div>
<?php  

}

}; ?>










<form action="" method="POST" enctype="multipart/form-data">


<label for="formFile" class="form-label">Update Profile Picture</label>
  <p>use this dimensions <span>750x750</span> For looks nice</p>
  <input class="form-control" name="picture" type="file" id="formFile">



                               
                          
                 
                          <br> <button name="submit" type="submit" class="btn btn-primary">
                               Update
                           </button>
</form>                   
                      
                    
                 


</div>
</body>
</html>
<?php
}else{
    header('location:index.php');
}

?>


