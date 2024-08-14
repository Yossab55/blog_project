// eye change from hidden or shown by switching between input type password or text
let button_eye = document.getElementById("button_eye");
let input_password_type = document.getElementById("pass");
button_eye.addEventListener("click", function switchEye() {
  if (input_password_type.getAttribute("type") === "password") {
    input_password_type.setAttribute("type", "text");
    button_eye.children[0].remove();
    let eye_close = document.createElement("i");
    eye_close.setAttribute("class", "fa-solid fa-eye");
    button_eye.appendChild(eye_close);
  } else {
    input_password_type.setAttribute("type", "password");
    button_eye.children[0].remove();
    let eye_open = document.createElement("i");
    eye_open.setAttribute("class", "fa-solid fa-eye-slash");
    button_eye.appendChild(eye_open);
  }
});

export { button_eye as buttonEyeSwitch };
// end eye change
// ==========================================================================
// start input date of birth manipulation ;
let input_date_make_it_right = document.getElementById("dob");
input_date_make_it_right.addEventListener('keydown', function makeItRight(event) {
  

});


export {input_date_make_it_right as input_date};
