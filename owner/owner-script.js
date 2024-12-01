//====================================================== entry-pass.php edit enable ===========================================================
function previewImage(event) {
  const file = event.target.files[0];
  const image = document.getElementById("profileImage");

  // Ensure a file is selected and is an image
  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.onload = function (e) {
      image.src = e.target.result; // Set image src to the loaded file's data URL
    };

    reader.readAsDataURL(file);
  }
}

//====================================================== bill button edit enable ===========================================================

document.addEventListener("DOMContentLoaded", function () {
  const payButtons = document.querySelectorAll(".btn-pay");
  const billDetails = document.getElementById("billDetails");
  const popover = document.getElementById("mydiv");
  const overlay = document.getElementById("overlay");
  const closeBtn = document.querySelector(".close-btn");

  // Function to show popover
  function showPopover() {
    popover.classList.add("show");
    overlay.classList.add("show");
  }

  // Function to hide popover
  function hidePopover() {
    popover.classList.remove("show");
    overlay.classList.remove("show");
  }

  // Event listeners for buttons
  payButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const data = {
        bill_id: this.dataset.billId,
        bill_type: this.dataset.billType,
        bill_month: this.dataset.billMonth,
        amount: this.dataset.amount,
        message: this.dataset.message,
      };

      // Update the popover content
      billDetails.innerHTML = `
                <form method="post" class="payment-form" id="paymentForm">
                    <h2>Bill Details</h2>
                    <input type="hidden" name="bill_id" value="${data.bill_id}">
                    <table class="details-table">
                        <tr>
                            <td><strong>Bill Type:</strong></td>
                            <td>${data.bill_type}</td>
                        </tr>
                        <tr>
                            <td><strong>Month:</strong></td>
                            <td>${data.bill_month}</td>
                        </tr>
                        <tr>
                            <td><strong>Amount:</strong></td>
                            <td>
                                <div class="input-group">
                                    <span class="currency-symbol">Rs.</span>
                                    <input type="number" name="amount" value="${data.amount}" class="amount-input" min="0" required>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Message:</strong></td>
                            <td>${data.message}</td>
                        </tr>
                    </table>
                    <button type="submit" class="btn-pay-form">Pay</button>
                </form>
            `;

      showPopover();
    });
  });

  // Close button event listener
  closeBtn.addEventListener("click", hidePopover);

  // Close popover when clicking outside
  overlay.addEventListener("click", hidePopover);

  // Prevent popover from closing when clicking inside it
  popover.addEventListener("click", function (e) {
    e.stopPropagation();
  });

  // Handle form submission
  document.addEventListener("submit", function (e) {
    if (e.target.id === "paymentForm") {
      e.preventDefault(); // Prevent default form submission

      const formData = new FormData(e.target);
      const bill_id = formData.get("bill_id");
      const amount = formData.get("amount");

      // Redirect to payment-page.php with bill_id and amount
      window.location.href = `payment-page.php?bill_id=${bill_id}&amount=${amount}`;
    }
  });
});

//==================================================== bill request page styles =============================
// Get references to the select field and submit button
const requestTypeSelect = document.getElementById("request_type");
const submitButton = document.getElementById("submit-btn");

// Enable submit button if a valid option is selected
requestTypeSelect.addEventListener("change", function () {
  if (requestTypeSelect.value !== "Select Request Type") {
    submitButton.disabled = false;
  } else {
    submitButton.disabled = true;
  }
});

//=============================================== profile.php file image preview ==========================================================
//Maintenance image display function
function previewImages(event) {
  const imagePreview = document.getElementById("imagePreview");
  imagePreview.innerHTML = ""; // Clear existing previews

  const files = event.target.files;
  if (files) {
    Array.from(files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imgElement = document.createElement("img");
        imgElement.src = e.target.result;
        imgElement.style.maxWidth = "100px";
        imgElement.style.margin = "5px";
        imagePreview.appendChild(imgElement);
      };
      reader.readAsDataURL(file);
    });
  }
}

//Image Preview
function previewImage(event) {
  const profileImage = document.getElementById("profileImage");
  const file = event.target.files[0];

  if (file) {
    // Create a URL for the selected image file
    profileImage.src = URL.createObjectURL(file);
  }
}

//============================================================== profile.php edit enable ===============================================================
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
  document.getElementById("editButtons").style.display = "none"; // Hide the buttons
  document.querySelector(".icon-link").style.display = "inline"; // Show edit icon

  // Re-disable all input fields and the file input
  const inputs = document.querySelectorAll("input, select, textarea");
  inputs.forEach((input) => input.setAttribute("readonly", true));
  document.getElementById("upload").disabled = true;
}

function previewImage(event) {
  const profileImage = document.getElementById("profileImage");
  const file = event.target.files[0];

  if (file) {
    profileImage.src = URL.createObjectURL(file);
  }
}

function changePassword() {
  // Redirect to the change password page or open a modal
  window.location.href = "change-password.php";
}

//============================================================== change-password.php edit enable ===============================================================
// show password change function
function togglePassword(fieldId, iconId) {
  const passwordField = document.getElementById(fieldId);
  const icon = document.getElementById(iconId);

  if (passwordField.type === "password") {
    passwordField.type = "text";
    icon.textContent = "visibility_off";
  } else {
    passwordField.type = "password";
    icon.textContent = "visibility";
  }
}

// Initialize attempts and lockout end time
let attempts = 5;
let lockoutTimer;
const countdownDisplay = document.getElementById("countdown");

// Check if lockout is active and retrieve the end time if it exists
const lockoutEndTime = localStorage.getItem("lockoutEndTime");
if (lockoutEndTime && new Date(lockoutEndTime) > new Date()) {
  const remainingTime = new Date(lockoutEndTime) - new Date();
  lockForm(remainingTime);
} else {
  localStorage.removeItem("lockoutEndTime"); // Remove outdated lockout end time
}

// Attach event listener to form submission
document.getElementById("pass").addEventListener("click", function (event) {
  event.preventDefault();
  const currentPassword = document.getElementById("cpassword").value;
  const newPassword = document.getElementById("npassword").value;
  const confirmPassword = document.getElementById("conpassword").value;

  // Check if new password and confirm password match
  if (newPassword !== confirmPassword) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "New Password and Confirm Password do not match.",
    });
    return;
  }

  // Verify current password using AJAX
  verifyCurrentPassword(currentPassword)
    .then((isValid) => {
      if (isValid) {
        document.querySelector("form").submit(); // Submit form if current password is correct
      } else {
        attempts--;
        showAlertWithAttempts();

        if (attempts <= 0) {
          const lockoutDuration = 30 * 60 * 1000; // 30 minutes
          lockForm(lockoutDuration); // Lock form if out of attempts
        }
      }
    })
    .catch((error) => {
      console.error("Error verifying password:", error);
    });
});

// Function to simulate password verification with server-side code
function verifyCurrentPassword(currentPassword) {
  return fetch("verify-password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ currentPassword }),
  })
    .then((response) => response.json())
    .then((data) => data.success);
}

// Function to display SweetAlert with attempts count
function showAlertWithAttempts() {
  Swal.fire({
    icon: "error",
    title: "Incorrect Password",
    text: `Current Password is incorrect. Attempts remaining: ${attempts}`,
    footer: '<a href="#">Forgot your password?</a>',
  });
}

// Function to lock form submission
function lockForm(lockoutDuration) {
  const submitButton = document.getElementById("pass");
  submitButton.disabled = true;

  const lockoutEndTime = new Date().getTime() + lockoutDuration;

  // Save the lockout end time in localStorage
  localStorage.setItem(
    "lockoutEndTime",
    new Date(lockoutEndTime).toISOString()
  );

  lockoutTimer = setInterval(() => {
    const now = new Date().getTime();
    const remainingTime = lockoutEndTime - now;
    const secondsLeft = Math.floor(remainingTime / 1000);

    if (remainingTime > 0) {
      countdownDisplay.textContent = `Locked for ${secondsLeft} seconds`;
      countdownDisplay.style.display = "inline";
    } else {
      clearInterval(lockoutTimer);
      submitButton.disabled = false;
      countdownDisplay.style.display = "none";
      attempts = 5; // Reset attempts after lockout period
      localStorage.removeItem("lockoutEndTime"); // Clear lockout time from localStorage
    }
  }, 1000);
}

//================================================== Log Out button function =============================
function confirmLogout(event) {
  // Prevent the default link action
  event.preventDefault();

  // Show SweetAlert confirmation dialog
  Swal.fire({
    title: "Are you sure you want to log out?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, Log out!",
  }).then((result) => {
    if (result.isConfirmed) {
      // If confirmed, show logged-out message and redirect
      Swal.fire({
        title: "Logged Out!",
        text: "You have successfully logged out!",
        icon: "success",
        showConfirmButton: false,
        timer: 1500, // Optional: auto close after 1.5 seconds
      }).then(() => {
        // Redirect to logout.php after the success message
        window.location.href = "logout.php";
      });
    }
  });
}
