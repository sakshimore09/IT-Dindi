


var confirmationResult;

function phoneAuth() {
  
    var number = "+91" + document.getElementById('mobileNo').value;
    // Initialize reCAPTCHA
    var recaptcha = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
        'size': 'invisible'
    });

    // Send verification code
    firebase.auth().signInWithPhoneNumber(number, recaptcha)
        .then(function(result) {
            confirmationResult = result;
            console.log("OTP sent");
            showMessage("OTP sent");
        })
        .catch(function(error) {
            showMessage(error.message);
        });
}
function codeverify() {
    var code = document.getElementById('verificationCode').value;
    confirmationResult.confirm(code)
        .then(function(result) {
           
            var user = result.user;
            console.log(user);
            console.log("Verification successful");
            showMessage("Verification successful");
            
            // Enable form submission upon successful verification
            document.getElementById("sign-in-button").disabled = false;
           
        })
        .catch(function(error) {
            var errorCode = error.code;
            var errorMessage = error.message;
            if (errorCode === 'auth/invalid-verification-code') {
                showMessage('Invalid verification code');
            } else {
                showMessage(errorMessage);
            }
            console.log(error);
        });
}

