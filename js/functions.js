// eye change from hidden or shown by switching between input type password or text
let buttonEyeSwitch = document.getElementById("button_eye");
let input_password_type = document.getElementById("pass");
buttonEyeSwitch.addEventListener("click", function switchEye() {
  if (input_password_type.getAttribute("type") === "password") {
    input_password_type.setAttribute("type", "text");
    buttonEyeSwitch.children[0].remove();
    let eye_close = document.createElement("i");
    eye_close.setAttribute("class", "fa-solid fa-eye");
    buttonEyeSwitch.appendChild(eye_close);
  } else {
    input_password_type.setAttribute("type", "password");
    buttonEyeSwitch.children[0].remove();
    let eye_open = document.createElement("i");
    eye_open.setAttribute("class", "fa-solid fa-eye-slash");
    buttonEyeSwitch.appendChild(eye_open);
  }
});

export { buttonEyeSwitch };
// end eye change
// ==========================================================================

// start input date of birth manipulation ;

// button prevent submit
let buttonSignup = document.getElementById("signup");
let inputsFiled = document.getElementsByTagName("input");
buttonSignup.addEventListener("click", function stopData(e) {
  for (let i = 0; i < inputsFiled.length; i++) {
    if (inputsFiled[i].value == null) {
      let spanError = document.createElement("span");
      spanError.setAttribute("class", "error");
      let spanErrorMessage =
        inputsFiled[i].getAttribute("name").split("-").join(" ") +
        "Can't be empty";
      spanError.append(spanErrorMessage);
      spanError.after(inputsFiled[i]);
      e.preventDefault();
    }
  }
});

export { buttonSignup };
