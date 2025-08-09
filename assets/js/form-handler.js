function arcShowForm() {
  const selected = document.getElementById("arcSelect").value;
  document.querySelectorAll(".arc-form-box").forEach(el => el.style.display = "none");
  if (selected) {
    const box = document.getElementById("arc-form-" + selected);
    if (box) box.style.display = "block";
  }
}

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".arc-form-box form").forEach((form) => {
    const action = form.getAttribute("action") || "";
    const isOldMailerLite = (
      action.includes("static.mailerlite.com/webforms/submit/") ||
      action.includes("assets.mailerlite.com/jsonp/")
    );


    const responseBox = document.createElement("div");
    responseBox.className = "arc-response-box";
    form.appendChild(responseBox);

    // Inject old MailerLite callback for success display
    if (isOldMailerLite) {
      const container = form.closest(".ml-form-embedContainer");
      if (container && container.id) {
        const idMatch = container.id.match(/mlb2-(\d+)/);
        if (idMatch) {
          const id = idMatch[1];
          window["ml_webform_success_" + id] = function () {
            const successEl = container.querySelector(".row-success");
            const formEl = container.querySelector(".row-form");
            if (successEl && formEl) {
              formEl.style.display = "none";
              successEl.style.display = "block";
              responseBox.textContent = "✅ Subscription successful!";
            }
          };
        }
      }
    }

    form.addEventListener("submit", async function (e) {
      if (!isOldMailerLite) return; // Let MailerLite JS handle the new ones

      e.preventDefault();
      responseBox.textContent = "⏳ Submitting...";

      const formData = new FormData(form);

      try {
        const res = await fetch(form.action, {
          method: "POST",
          body: formData,
        });

        const text = await res.text();

        try {
          const json = JSON.parse(text);
          if (json.success || json.message) {
            responseBox.textContent = "✅ Subscription successful!";
          } else {
            responseBox.textContent = "❌ Subscription failed.";
          }
        } catch {
          responseBox.textContent = "✅ Subscription successful!";
        }
      } catch {
        responseBox.textContent = "❌ Network error.";
      }
    });
  });
});
