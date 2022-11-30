<?php 
session_start();
include('includes/config.php');
error_reporting(0);
if(isset($_POST['submit']))
{
$fromdate=$_POST['fromdate'];
$todate=$_POST['todate'];
$city1=$_POST['city1'];
$city2=$_POST['city2'];
$payment=$_POST['textbox'] ;
$message=$_POST['message'];
$useremail=$_SESSION['login'];
$status=0;
$vhid=$_GET['vhid'];
$bookingno=mt_rand(100000000, 999999999);
$ret="SELECT * FROM tblbooking where (:fromdate BETWEEN date(FromDate) and date(ToDate) || :todate BETWEEN date(FromDate) and date(ToDate) || date(FromDate) BETWEEN :fromdate and :todate) and VehicleId=:vhid";
$query1 = $dbh -> prepare($ret);
$query1->bindParam(':vhid',$vhid, PDO::PARAM_STR);
$query1->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
$query1->bindParam(':todate',$todate,PDO::PARAM_STR);
$query1->execute();
$results1=$query1->fetchAll(PDO::FETCH_OBJ);

if($query1->rowCount()==0)
{

$sql="INSERT INTO  tblbooking(BookingNumber,userEmail,VehicleId,FromDate,ToDate,city1,city2,payment,message,Status) VALUES(:bookingno,:useremail,:vhid,:fromdate,:todate,:city1,:city2,:payment,:message,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':bookingno',$bookingno,PDO::PARAM_STR);
$query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
$query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
$query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
$query->bindParam(':todate',$todate,PDO::PARAM_STR);
$query->bindParam(':city1',$city1,PDO::PARAM_STR);
$query->bindParam(':city2',$city2,PDO::PARAM_STR);
$query->bindParam(':payment',$payment,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
  $to=$useremail;
  $subject='Booking successfull';
  $message= 'Thankyou! Your Booking successfull!'
.' '.
'
Full Name : '.$_POST['fname'].'
Name as per card : '.$bookingno.'
City1 : '.$city1.'
City2 : '.$city2.'
Email : '.$useremail.'';
  $header='From:Shift&ship@gmail.com';
 $m=mail($to,$subject,$message,$header);

$_SESSION['pay1']=$_POST['payment'];
echo "<script>alert('Booking successfull.');</script>";
echo "<script type='text/javascript'> document.location = 'my-booking.php'; </script>";
}
else 
{
echo "<script>alert('Something went wrong. Please try again');</script>";
 echo "<script type='text/javascript'> document.location = 'car-listing.php'; </script>";
} }  else{
 echo "<script>alert('This service already booked for these days');</script>"; 
 echo "<script type='text/javascript'> document.location = 'car-listing.php'; </script>";
}

}

?>


<!DOCTYPE HTML>
<html lang="en">
<head>

<title>Movers & Packers </title>
<!--Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
<!--Custome Style -->
<link rel="stylesheet" href="assets/css/style.css" type="text/css">
<!--OWL Carousel slider-->
<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
<!--slick-slider -->
<link href="assets/css/slick.css" rel="stylesheet">
<!--bootstrap-slider -->
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<!--FontAwesome Font Style -->
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- SWITCHER -->
		<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
		<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
<!-- <link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png"> -->
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">


<!-- <script type="text/javascript">
  function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script> -->

</head>
<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php');?>
<!-- /Switcher -->  

<!--Header-->
<?php include('includes/header.php');?>
<!-- /Header --> 

<!--Listing-Image-Slider-->

<?php 
$vhid=intval($_GET['vhid']);
$sql = "SELECT tblvehicles.*,tblbrands.BrandName,tblbrands.id as bid  from tblvehicles join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblvehicles.id=:vhid";
$query = $dbh -> prepare($sql);
$query->bindParam(':vhid',$vhid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{  
$_SESSION['brndid']=$result->bid;  
?>  


<!--Listing-detail-->
<section class="listing-detail">
  <div class="container">
    <div class="listing_detail_head row">
      <div class="col-md-9">
        <h2><?php echo htmlentities($result->BrandName);?> , <?php echo htmlentities($result->VehiclesTitle);?></h2>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9">
        <div class="main_features">
         
        </div>
        <div class="listing_more_info">
          <div class="listing_detail_wrap"> 
            <!-- Nav tabs -->
            <ul class="nav nav-tabs gray-bg" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview"  data-toggle="tab">Services Overview </a></li>
          
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content"> 
              <!-- vehicle-overview -->
              <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                
                <p><?php echo htmlentities($result->VehiclesOverview);?></p>
              </div>
                    </tr>

            </div>
          </div>
          
        </div>
<?php }} ?>
   
      </div>
      
      <!--Side-Bar-->
      <aside class="col-md-3">
      
        <div class="share_vehicle">
          <p>Share:   
          <a href="contact-us.php"><i class="fa fa-envelope" aria-hidden="true"></i></a> 
          <a href="https://www.youtube.com/c/vaibhavLabscode"><i class="fa fa-youtube" aria-hidden="true"></i></a> </p>
        </div>
        <div class="color">
          <p><h4>First you have to login than you will allow to book services.</h4>
        </div>
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><aria-hidden="true"></i>Booking Services</h5>
          </div>
          <form method="post">
          <div class="form-group">
          <label for="fullname" class="control-label">Fullname <small class="text-danger"></small></label>
            <input type="text" name="fname" id="fullname" class="form-control form-control-sm rounded-0" pattern="^[a-zA-Z]{4,}(?: [a-zA-Z]+)?(?: [a-zA-Z]+)?$" title="Fullname should not contain '123' or '@'"required>
            </div>
            <div class="form-group">
            <label for="contact" class="control-label">Contact No<small class="text-danger"></small></label>
             <input type="tel" name="contact" id="contact" class="form-control form-control-sm rounded-0" maxlength="10" required>
            </div>
            <div class="form-group">
            <label for="email" class="control-label">Email</label>
              <input type="email" name="email" id="email" class="form-control form-control-sm rounded-0" onBlur="checkAvailability()" placeholder="Email Address" required="required">
              <span id="user-availability-status" style="font-size:12px;"></span>
            </div>
            <div class="form-group">

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
$(document).ready(function(){

	var dtToday = new Date();

	var month = dtToday.getMonth() + 1;
	var day = dtToday.getDate();
	var year = dtToday.getFullYear();
	if(month < 10)
		month = '0' + month.toString();
	if(day < 10)
		day = '0' + day.toString();
	var maxDate = year + '-' + month + '-' + day;
	
	$('#dateControl').attr('min', maxDate);

})
</script>
              <label>Schedule Date:</label>
              <input type="date" id="dateControl" class="form-control" name="fromdate" placeholder="From Date" required>
            </div>
            <div class="form-group">
              <label>To Date:</label>
              
<script>
$(document).ready(function(){

	var dtToday = new Date();

	var month = dtToday.getMonth() + 1;
	var day = dtToday.getDate();
	var year = dtToday.getFullYear();
	if(month < 10)
		month = '0' + month.toString();
	if(day < 10)
		day = '0' + day.toString();
	var maxDate = year + '-' + month + '-' + day;
	
	$('#dateControl2').attr('min', maxDate);

})
</script>

              <input type="date" id="dateControl2"  class="form-control" name="todate" placeholder="To Date" required>
            </div>
            <div class="form-group">
              <label>Start point</label><br>
              <select name="city1" id="city1" class="form-control" onchange='payment_gen(this.value)' name="startpoint" >
                <option selected>Select</option>
                <option id="vadodara" value="vadodara">Vadodara</option>
                <option id="surat" value="surat">Surat</option>
                <option id="anand" value="anand">Anand</option>
                <option id="ahmedabad" value="ahmedabad">Ahmedabad</option>
                <option id="bharuch" value="bharuch">Bharuch</option>
                <option id="gandhinagar" value="gandhinagar">Gandhinagar</option>
                <option id="gandhidham" value="gandhidham">Gandhidham</option>
                <option id="rajkot" value="rajkot">Rajkot</option>
              </select>
              </div>
              <div>
              <label>End point</label><br>
              <select name="city2" id="city2" class="form-control" onchange='payment_gen(this.value)'  name="endpoint" >
                <option selected>Select</option>
                <option id="vadodara" value="vadodara">Vadodara</option>
                <option id="surat" value="surat">Surat</option>
                <option id="anand" value="anand">Anand</option>
                <option id="ahmedabad" value="ahmedabad">Ahmedabad</option>
                <option id="bharuch" value="bharuch">Bharuch</option>
                <option id="gandhinagar" value="gandhinagar">Gandhinagar</option>
                <option id="gandhidham" value="gandhidham">Gandhidham</option>
                <option id="rajkot" value="rajkot">Rajkot</option>
              </select>
              </div><br>


              <script type="text/javascript">


                function payment_gen() {
                  var city1=document.getElementById("city1").value;
                  var city2=document.getElementById("city2").value;
                  if (city1=="vadodara" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="anand") {
                  var pay="4000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="ahmedabad") {
                  var pay="3500";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="bharuch") {
                  var pay="4500";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="gandhinagar") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="gandhidham") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="vadodara" && city2=="rajkot") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="anand") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="gandhidham") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="anand" && city2=="rajkot") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="surat") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="gandhidham") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="surat" && city2=="rajkot") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="ahmedabad") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="gandhidham") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="ahmedabad" && city2=="rajkot") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="bharuch") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="gandhidham") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="bharuch" && city2=="rajkot") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                }else if (city1=="gandhinagar" && city2=="surat") {
                  var pay="5500";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="gandhinagar") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="gandhidham") {
                  var pay="20000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhinagar" && city2=="rajkot") {
                  var pay="15000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="gandhidham") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="gandhidham" && city2=="rajkot") {
                  var pay="15000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="surat") {
                  var pay="5000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="vadodara") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="anand") {
                  var pay="6000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="ahmedabad") {
                  var pay="9000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="bharuch") {
                  var pay="7000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="gandhinagar") {
                  var pay="8000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="gandhidham") {
                  var pay="20000";
                  document.getElementById("textbox").value=pay;
                } else if (city1=="rajkot" && city2=="rajkot") {
                  var pay="2000";
                  document.getElementById("textbox").value=pay;
                } else {
                  var pay=" ";
                  document.getElementById("textbox").value=pay;
                }
                }
              </script>
              
              <div>
              <label>Payment</label><br>
              <input class="form-control" type="number" name="textbox" id="textbox" name="payment" readonly>
              </div>

            <div class="form-group">
            <label>Proper Address:</label>
              <textarea rows="4" class="form-control" name="message" placeholder=" Address" required></textarea>
            </div>
          <?php if($_SESSION['login'])
              {?>
              <div class="form-group">
                <input type="submit" class="btn"  name="submit" value="Book Now">
              </div>
              <?php } else { ?>
<a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login For Book</a>

              <?php } ?>
          </form>
        </div>
      </aside>
      <!--/Side-Bar--> 
    </div>
    
    <div class="space-20"></div>
    <div class="divider"></div>
     
  </div>
</section>
<!--/Listing-detail--> 

<!--Footer -->
<?php include('includes/footer.php');?>
<!-- /Footer--> 

<!--Login-Form -->
<?php include('includes/login.php');?>
<!--/Login-Form --> 

<!--Register-Form -->
<?php include('includes/registration.php');?>

<!--/Register-Form --> 

<!--Forgot-password-Form -->
<?php include('includes/forgotpassword.php');?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>