let button_eye = document.getElementById("button_eye");
let input_password_type = document.getElementById("pass");
console.log(button_eye.children);
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
