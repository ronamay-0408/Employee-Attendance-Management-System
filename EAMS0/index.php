<!DOCTYPE html>
<html>
<head>
	<title>SignUp and Login</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="./ATLOG/login.php" method="post">
			<h1>EMPLOYEE</h1>
			<div class="social-container">
			<a href="#" class="social"><i class="fa fa-facebook"></i></a>
			<a href="#" class="social"><i class="fa fa-google"></i></a>
			<a href="#" class="social"><i class="fa fa-linkedin"></i></a>
		</div>
		<span>or use your account</span>
		<input type="text" name="username" placeholder="username" required>
		<input type="password" name="password" placeholder="Password" required>
		<a href="#">Forgot Your Password</a>

		<button type="submit" name="login">Login</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="./ATLOG/admin_login.php" method="post">
			<h1>ADMIN</h1>
			<div class="social-container">
			<a href="#" class="social"><i class="fa fa-facebook"></i></a>
			<a href="#" class="social"><i class="fa fa-google"></i></a>
			<a href="#" class="social"><i class="fa fa-linkedin"></i></a>
		</div>
		<span>or use your account</span>
		<input type="text" name="username" placeholder="username" required>
		<input type="password" name="password" placeholder="Password" required>
		<a href="#">Forgot Your Password</a>

		<button type="submit" name="admin_login">Login</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Administrator Log-in page</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Admin</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Employees Log-in page</h1>
				<p>Enter your details and start journey with us</p>
				<button class="ghost" id="signUp">Employee</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	const signUpButton = document.getElementById('signUp');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');

	signUpButton.addEventListener('click', () => {
		container.classList.add("right-panel-active");
	});
	signInButton.addEventListener('click', () => {
		container.classList.remove("right-panel-active");
	});
</script>
</body>
</html>








