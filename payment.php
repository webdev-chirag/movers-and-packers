<?php


error_reporting(0);
session_start();

$db=mysqli_connect("localhost","root","","s&s");

$bookid=$_SESSION['bookid'];
$sql3="SELECT * FROM tblbooking WHERE BookingNumber=$bookid";
        $q4=mysqli_query($db,$sql3);
        $info2=mysqli_fetch_assoc($q4);
#$pay1=$_SESSION['pay1'];
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}else{


	if(isset($_POST['done'])){
	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zipcode = $_POST['zipcode'];
	$cardname = $_POST['cardname'];
	$cardnum = $_POST['cardnum'];
	$expmonth = $_POST['expmonth'];
	$expyear = $_POST['expyear'];

	if($expyear<="2022")
	{
		$success="Card is already Expired";

	}
	else{
	
	
	

	$sql = "INSERT INTO card_payment(fullname,email,address,city,state,zipcode,nameoncard,cardnumber,expmonth,expyear) VALUES('$fullname','$email','$address','$city','$state','$zipcode','$cardname','$cardnum','$expmonth','$expyear')";

	$result=mysqli_query($db,$sql);


	$bookid=$_SESSION['bookid'];
	$sql2="UPDATE tblbooking SET pay=1 WHERE BookingNumber=$bookid";
	$q1=mysqli_query($db,$sql2);


#$pay1=$_SESSION['pay1'];
	if ($result) {
		$to=$email;
        $subject='Payment successfully';
        $message= 'Thankyou! Your Payment successfully!'
.' '.'
Full Name : '.$fullname.'
Payment : '.'Rs.'.$info2['payment'].'
Address : '.$address.'
City : '.$city.'
State : '.$state.'
Email : '.$email.'';
        $header='From:Shift&ship@gmail.com';
       $m=mail($to,$subject,$message,$header);


 $success = "Payment successful  <p>You will be redirected in <span id='counter'>5</span> second(s).</p>
                                                        <script type='text/javascript'>
                                                        function countdown() {
                                                            var i = document.getElementById('counter');
                                                            if (parseInt(i.innerHTML)<=0) {
                                                                location.href = 'index.php';
                                                            }
                                                            i.innerHTML = parseInt(i.innerHTML)-1;
                                                        }
                                                        setInterval(function(){ countdown(); },1000);
                                                        </script>";


                                                        header("refresh:5;url=my-booking.php"); // redireted once inserted success



			}

		}
	}

}






?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment</title>
	<link rel="stylesheet" type="text/css" href="assets/css/payment.css">

</head>
<body>
<div class="container">
	
	<form action="" method="POST">
		<span style="color:green;">
			<?php echo $success; ?>
		</span>
		<div class="row">
			<div class="col">
				<h3 class="title">billing address</h3>
				<div class="inputBox">
					<span>full name :</span>
					<input type="text" placeholder="Darpan Pandey" name="fullname" required>
				</div>
				<div class="inputBox">
					<span>email :</span>
					<input type="text" placeholder="example@example.com" name="email" required>
				</div>
				
				<div class="inputBox">
					<span>address :</span>
					<input type="text" placeholder="house no. - street - city" name="address" required>
				</div>
				<div class="inputBox">
					<span>city :</span>
					<input type="text" placeholder="Vadodara" name="city" required>
				</div>
				<div class="flex">
					<div class="inputBox">
						<span>state :</span>
						<input type="text" placeholder="Gujarat" name="state" required>
					</div>
					<div class="inputBox">
						<span>zip code :</span>
						<input type="text" maxlength="6" minlength="6" pattern="^[0-9]{6}$" placeholder="123 456" name="zipcode" required>
					</div>
				</div>
			</div>

			<div class="col">
				<h3 class="title">payment</h3>
				<div class="inputBox">
					<span>cards accepted :</span>
					<img src="assets/images/card_img.jpeg">
				</div>
				<div class="inputBox">
					<span>name on card :</span>
					<input type="text" placeholder="Darpan Pandey" name="cardname" required>
				</div>
				<div class="inputBox">
					<span>credit card number :</span>
					<input type="text" pattern="^[0-9]{12}$" minlength="12" maxlength="12" placeholder="1111-2222-3333-4444" name="cardnum" required>
				</div>
				
				<div class="inputBox">
					<span>exp month :</span>	
					<select name="expmonth" required>
		                <option id="january" value="january" selected>January</option>
		                <option id="february" value="february">February</option>
		                <option id="march" value="march">March</option>
		                <option id="april" value="april">April</option>
		                <option id="may" value="may">May</option>
		                <option id="june" value="june">June</option>
		                <option id="july" value="july">July</option>
		                <option id="august" value="august">August</option>
		                <option id="september" value="september">September</option>
		                <option id="october" value="october">October</option>
		                <option id="november" value="november">November</option>
		                <option id="december" value="december">December</option>
              		</select>
				</div>
				<div class="flex">
					<div class="inputBox">
						<span>exp year :</span>
						<input type="text" placeholder="2023" maxlength="4" minlength="4" name="expyear" required>
					</div>
					<div class="inputBox">
						<span>CVV :</span>
						<input type="password" pattern="^[0-9]{3}$" minlength="3" maxlength="3" placeholder="123" name="cvv" required>
					</div>
					<div class="inputBox">
						<span>payment :</span>
						<input type="text" name="payment" value="<?php echo 'Rs.'.$info2['payment']; ?>" readonly>
					</div>
					
				</div>
			</div>
		</div>

		
		<input type="submit" value="proceed to checkout" class="submit-btn" name="done">
	</form>
</div>


</body>
</html>