(function() {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');
  Array.prototype.slice.call(forms).forEach(function(form) {
    form.addEventListener('submit', function(event) {
      const inputs = form.querySelectorAll('input, textarea, select');

      inputs.forEach(input => {
        const feedbacks = input.parentElement.querySelectorAll('.invalid-feedback');

        feedbacks.forEach(f => f.style.display = 'none');
        input.setCustomValidity("");

        for (let key in input.validity) {
          if (key === 'valid') continue;
          if (input.validity[key]) {
            input.setCustomValidity(key);
            const f = input.parentElement.querySelector(`.invalid-feedback[data-type="${key}"]`);
            if (f) f.style.display = 'block';
            break;
          }
        }
      });

      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }

      form.classList.add('was-validated');
    }, false);
  });
})();
