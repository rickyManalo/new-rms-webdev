<?php
include_once "../../../includes/public-user/templates/header.php";
include_once 'functionalities/modals.php';
?>
<link rel="stylesheet" href="../../../css/public-user/templates/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<div id="whole_page">
    <img src='../../../assets/images/background_img.jpg' style='opacity: 0.7; max-width: 100%; height: 100%;'/>
    <img src='../../../assets/images/batStateUNeu-logo.png' style='width: 100px; height: 100px; position: fixed; border:solid 3px #EF1F3B; background-color: white; border-radius: 50%; z-index: 1; transform: translate(-50%, -300%); top: 50%; left: 50%;'/>

        <div id="login_container">
            <div id='container-header'>
            <h2 id='h2Login'>SIGN IN</h2>
            </div>
            <br>
            <form id='user_input' action='functionalities/login-account.php' method='POST'>
                <label class='labelSubHeader'>EMAIL</label>
                <input id="email" name="emailAddress" placeholder="example@g.batstate-u.edu.ph" type="text"><br>
                <label class='labelSubHeader'>PASSWORD</label>
                <div id='password_container'>
                    <input class="passwordInput" name="password" placeholder="Password" type="password" maxlength='8'>
                    <i class="toggle-password2 fas fa-eye-slash"></i>
                </div>  
                <br>
                <input name="submit" type="submit" value=" Login ">
                <div style="display: flex; align-items: center; margin-top: 10px; margin-bottom: 10px;">
                    <hr style="flex: 1; margin-right: 10px; border-top: 1px solid gray;">
                    <label style="text-align: center; color: gray;">OR</label>
                    <hr style="flex: 1; margin-left: 10px; border-top: 1px solid gray;">
                </div>

                <input name="submit" type="button" onclick="window.location.href='sign-up.php'" value="Sign Up">
                <label id='labelSignUp' style='margin-top: 10px;'>Forgot Password?<a id='a_SignUp' onclick="showModal()">&nbsp;Click Here!</a></label>
                <br>
            </div>
            </form>
        </div>
</div>
<!-- HTML code for change password modal -->
<div id="myModal" class="modal">
    <div class="modal-container">
        <span class="close">&times;</span>
        <h2 id='h2pass'>Change Password</h2>
        <form id="change-password-form" action='sample_db/change-password.php' method='POST'>
            <label for="current-password">Email:</label>
            <div class='password-container'>
                <input type="text" id="userEmailAddressInput" name="email" placeholder='example@g.batstate-u.edu.ph' required>
            </div>
            <input id="otpVerification" type="text" name="otpBox" placeholder="Enter Your One Time Password" maxlength="6">
            <button name="send" id='sendOtpLink' type='button'>SEND OTP</button>
            <span id="emailTooltip" style="display:none;color:red"></span>
            <label for="new-password">New Password:</label>
            <div class='password-container'>
                <input type="password" class="passwordInput" name="new-password"  maxlength='8' required>
                <i class="toggle-password fas fa-eye-slash"></i>
            </div>  
            <label for="confirm-password">Confirm Password:</label>
            <div class='password-container'>
                <input type="password" id="confirmPasswordInput" name="confirm-password"  maxlength='8' required>
                <i class="toggle-password fas fa-eye-slash"></i>
            </div>
            <span id="passwordTooltip" style="display:none;color:red"></span>  
            <button name='confirm' type="button" id="submit-password" onclick='submitPss()'>Submit</button>
        </form>
    </div>
</div>

<script src='../../../js/public-user/show-hide-password.js'></script>
<script src='../../../js/public-user/close-modal.js'></script>
<script src='../../../js/public-user/send-otp.js'></script>

<script>

var disableotpInput = document.getElementById("otpVerification");
var disableSubmitBtn = document.getElementById('submit-password');
var email = document.getElementById("userEmailAddressInput");
var pass = document.getElementsByClassName('passwordInput')[1];
var confirmpass = document.getElementById('confirmPasswordInput');
var otpBox = document.getElementById('otpVerification');

disableotpInput.disabled = true;

email.addEventListener('input', validateForm);
pass.addEventListener('input', validateForm);
confirmpass.addEventListener('input', validateForm);
otpBox.addEventListener('input', validateForm);
disableotpInput.addEventListener('input', validateForm);

function validateForm() {
    if (email.value === '' || pass.value === '' || confirmpass.value === '' || otpBox.value ==='' || disableotpInput.value==='') {
        disableSubmitBtn.disabled = true;
        disableSubmitBtn.style.backgroundColor = "gray";
        disableSubmitBtn.style.pointerEvents = "none";
    } else {
        disableSubmitBtn.disabled = false;
        disableSubmitBtn.style.backgroundColor = "";
        disableSubmitBtn.style.pointerEvents = "";
    }
}

window.onload = validateForm;

function showModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
    setTimeout(function() {
        modal.querySelector(".modal-container").style.transform = "translate(-50%, -50%)";
    }, 10);
}





///////////////////////////////////////////////////////////submit button password///////////////////////////////////////////////////////

function submitPss() {
    var emailAddress = document.getElementById("userEmailAddressInput").value;
    var passwordInput = document.getElementsByClassName('passwordInput')[1].value;
    var confirmPasswordInput = document.getElementById('confirmPasswordInput').value;
    var emailTooltip = document.getElementById("emailTooltip");
    var passwordTooltip = document.getElementById("passwordTooltip");
    var otpTextBox = document.getElementById("otpVerification").value;

    
    if (!/^[^@\s]+@g.batstate-u.edu.ph$/.test(emailAddress)) {
        emailTooltip.innerHTML = "Please use a valid email address!";
        emailTooltip.style.display = "inline-block";
        passwordTooltip.style.display = "none"; // hide password tooltip
        return;
    }

    if (passwordInput !== confirmPasswordInput) {
        passwordTooltip.innerHTML = 'Password does not match.';
        passwordTooltip.style.display = "block";
        emailTooltip.style.display = "none";
    } 
    else if ( passwordInput === '' || confirmPasswordInput ==='' ){
        passwordTooltip.innerHTML = 'Passwords cannot be Empty.';
        passwordTooltip.style.display = "block";
        emailTooltip.style.display = "none";
    }
    else {
        passwordTooltip.innerHTML = 'Passwords match.';
        passwordTooltip.style.display = "block";
        emailTooltip.style.display = "none";

        // send the OTP and email address to verify_otp.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../account/functionalities/change-password.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "successful") {
                // redirect the user to a success page
                window.location.href = "login.php?edit=successful";
                console.log(otpTextBox);

            } else {
                // display an error message
                window.location.href = "login.php?error=wrong";
                console.log(otpTextBox);

            }
            }
        };
        xhr.send("email=" + encodeURIComponent(emailAddress) + "&otp=" + encodeURIComponent(otpTextBox) + "&password=" + encodeURIComponent(confirmPasswordInput));
    }

}


</script>


<?php
include_once "../../../includes/public-user/templates/footer.php";
?>