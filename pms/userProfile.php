<?php
require_once('pages/header.php');

/*
*
*  User informetion view page
*
*/



// check user link is currect
if(isset($_GET) && count($_GET) > 0) {
    $db = $c->db;
    $userInfo = $_SESSION['userinfo'];
    $id = array_values($_GET);
    $id = $c->makeId($id[0]);

    // check user is exists
    $user = $db->get_row('fms_admin', ['id' => $id]);
    $sc_c = json_decode($user['userInfo_sc']);
    unset($user['password']);
    unset($user['userInfo_sc']);
    unset($user['userName']);
    if(isset($user)) {
?>
<section class="content">
   <div class="container-fluid">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="userInfo profile">
            <div class="userProfile-top">
               <div class="background-c">
                  <div class="userimg">
                     <div class="circle">
                        <img src="<?php echo $base_url.$user['photo']; ?>" alt="">
                     </div>
                  </div>
                  <div class="userName">
                     <p><b>Name:</b> <?php echo $user['fullName']; ?></p>
                  </div>
               </div>
               <div class="userdetails">
                  <table>
                     <thead>
                        <tr>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr><td><p><label>Email</label>  <b>:</b> <?php echo $user['email']; ?></p></td></tr>
                        <tr><td><p><label>User name</label>  <b>:</b> <?php echo isset($sc_c->userName_sc) ? $sc_c->userName_sc: '<span style="color:red;">username does not create</span>' ?></p></td></tr>
                        <tr><td><p><label>User Roll</label>  <b>:</b> <?php echo $user['userRoll']; ?></p></td></tr>
                        <?php
                           if($user['userRoll'] === 'Partner') {
                              echo '<tr><td><p><label>Percentage</label> <b>:</b> '.$user['percentage'].'<label>%</label></p></td></tr>';
                           }
                        ?>
                        <tr><td><p><label>Gender</label> <b>:</b> <?php echo $user['gender']; ?></p></td></tr>
                        <tr><td><p><label>Birthday</label> <b>:</b> <?php echo $user['birthday']; ?></p></td></tr>
                        <?php
                           if(empty($user['u_date'])) {
                              echo '<tr><td><p><label>Add date</label> <b>:</b> '.$user['a_date'].' </p></td></tr>';
                           }
                        ?>
                        <?php
                           if(!empty($user['u_date'])) {
                              echo '<tr><td><p><label>Update date</label>  <b>:</b> '.$user['u_date'].' </p></td></tr>';
                           }
                        ?>
                     </tbody>
                  </table>
                    <?php if(isset($user)) {
                        echo '<a style="float:right;color:white;" class="btn btn-info waves-effect cu-btn" href="'.$base_url.'index.php"><i class="material-icons">home</i> Go Home</a>';}
                    ?>
               </div>
            </div>
         </div>
      </div>

   </div>
</section>
<?php

    }else {
        return $c->redirect('users');
    }
}else {
  return $c->redirect('404');
}
include('pages/footer.php'); ?>
