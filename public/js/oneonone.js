document.addEventListener('DOMContentLoaded', function () {
  var dateInput = document.querySelector('input[name="preferred_date"]');
  if (dateInput) {
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var dd = String(today.getDate()).padStart(2, '0');
    var todayStr = yyyy + '-' + mm + '-' + dd;
    dateInput.setAttribute('min', todayStr);
    dateInput.addEventListener('change', function () {
      if (dateInput.value && dateInput.value < todayStr) {
        dateInput.value = todayStr;
      }
    });
  }
});
