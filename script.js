/**
 * The function `togglePassword` toggles the visibility of a password field and updates the text of a
 * button accordingly.
 */
function togglePassword() {
  const passwordField = document.getElementById("password");
  const showPasswordText = document.querySelector(".show-password");

  if (passwordField.type === "password") {
    passwordField.type = "text";
    showPasswordText.textContent = "Hide";
  } else {
    passwordField.type = "password";
    showPasswordText.textContent = "Show";
  }
}

/* The code snippet is selecting all elements with the class "nav-item" on the document, then for each
of these elements, it is adding a click event listener to the element with the class "nav-link"
inside it. When the "nav-link" element is clicked, it toggles the "expanded" class on the parent
"nav-item" element. This functionality allows for expanding and collapsing navigation items when
their corresponding links are clicked. */
document.querySelectorAll(".nav-item").forEach((item) => {
  item.querySelector(".nav-link").addEventListener("click", () => {
    item.classList.toggle("expanded");
  });
});

// Function to update the background color

function updateBorderColors() {
  // Get all card containers
  const cards = document.querySelectorAll('[id="colors"]');
  
  cards.forEach(card => {
      // Find the status dropdown within this card
      const statusDropdown = card.querySelector('.status-dropdown');
      if (!statusDropdown) return;

      // Get the selected value
      const selectedValue = statusDropdown.value;

      // Change the border color based on the selected value
      switch (selectedValue.toLowerCase()) {
          case 'pending':
              card.style.border = '5px solid red';
              break;
          case 'working_on':
              card.style.border = '5px solid purple';
              break;
          case 'assigned':
              card.style.border = '5px solid yellow';
              break;
          case 'completed':
              card.style.border = '5px solid green';
              break;
          default:
              card.style.border = 'none';
              break;
      }
  });
}

// Call the function when the page loads
window.onload = function() {
  updateBorderColors();
};

// Add event listeners to all status dropdowns
document.addEventListener('DOMContentLoaded', function() {
  const statusDropdowns = document.querySelectorAll('.status-dropdown');
  statusDropdowns.forEach(dropdown => {
      dropdown.addEventListener('change', updateBorderColors);
  });
});
// Call the function when the select option is changed
document
  .getElementsByClassName("status1")
  .addEventListener("change", updateBorderColor);

  //============================================================== Profile Editing functions ================================================================
function enableEditing() {
  document
    .querySelectorAll(
      "input:not(#block, #floor, #apartment, #username), textarea"
    )
    .forEach((element) => {
      element.removeAttribute("readonly");
      element.removeAttribute("disabled");
    });
  document.getElementById("upload").disabled = false;
  document.getElementById("editButtons").style.display = "flex";
  document.querySelector(".icon-link").style.display = "none";
}

function cancelEditing() {
  document.getElementById("editButtons").style.display = "none";
  document.querySelector(".icon-link").style.display = "inline";

  const inputs = document.querySelectorAll("input, select, textarea");
  inputs.forEach((input) => {
    input.setAttribute("readonly", true);
    if (input.id === "upload") {
      input.disabled = true;
    }
  });
}

function previewImage(event) {
  const profileImage = document.getElementById("profileImage");
  const file = event.target.files[0];

  if (file) {
    profileImage.src = URL.createObjectURL(file);
  }
}

function changePassword() {
  window.location.href = "change-password.php";
}

//================================================== Change password =============================
function togglePasswordVisibility(inputId, toggleIcon) {
  const inputField = document.getElementById(inputId);

  // Toggle the password field type
  if (inputField.type === "password") {
    inputField.type = "text"; // Show password
    toggleIcon.textContent = "visibility_off"; // Change icon to visibility_off
  } else {
    inputField.type = "password"; // Hide password
    toggleIcon.textContent = "visibility"; // Change icon to visibility
  }
}