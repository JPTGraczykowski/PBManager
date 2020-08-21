$(document).ready(function() {
  var today = new Date();
  var day = today.getDate();
  var month = today.getMonth() + 1;
  var year = today.getFullYear();
  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  var enterDate = document.querySelector("#enterDate");
  enterDate.value = year + "-" + month + "-" + day;
});