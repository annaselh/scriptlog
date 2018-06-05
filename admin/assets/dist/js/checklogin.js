//JavaScript Document
function validasi(form){
if (formlogin.email.value == ""){
alert("Please enter your email address");
formlogin.email.focus();
return false;
}
     
if (formlogin.password.value == ""){
alert("Please enter your password");
formlogin.password.focus();
return false;
}
return true;
}