$(document).ready(function () {
  var incomes = $("#incomes li").find(".amountCell");
  var expenses = $("#expenses li").find(".amountCell");
  var balanceWindow = $("#balanceWindow");
  var balance = 0;
  for (let i = 0; i < incomes.length; i++) {
    balance += Number(incomes[i].innerHTML);
  }
  for (let i = 0; i < expenses.length; i++) {
    balance -= Number(expenses[i].innerHTML);
  }
  if (balance >= 0) {
    balanceWindow.css("background-color", "green");
    balanceWindow.html(
      "Your balance is: " + balance.toFixed(2) + "<br/>Well done! You've saved some money!"
    );
  } else {
    balanceWindow.css("background-color", "red");
    balanceWindow.html(
      "Your balance is: " + balance.toFixed(2) + "<br/>Watch Out! You're going into debts!"
    );
  }
});

//Finance chart - Incomes
google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  var incomesItems = $("#incomes li").find(".categoryCell");
  var expensesItems = $("#expenses li").find(".categoryCell");
  var incomes = $("#incomes li").find(".amountCell");
  var expenses = $("#expenses li").find(".amountCell");

  var itemsIncomesArray = [["Income Item", "Amount"]];
  for (let i = 0; i < incomesItems.length; i++) {
    if (Number(incomes[i].innerHTML) > 0)
      itemsIncomesArray.push([
        incomesItems[i].textContent,
        Number(incomes[i].innerHTML),
      ]);
  }

  var itemsExpensesArray = [["Expense Item", "Amount"]];
  for (let i = 0; i < expensesItems.length; i++) {
    if (Number(expenses[i].innerHTML) > 0)
      itemsExpensesArray.push([
        expensesItems[i].textContent,
        Number(expenses[i].innerHTML),
      ]);
  }

  var incomesData = google.visualization.arrayToDataTable(itemsIncomesArray);
  var expensesData = google.visualization.arrayToDataTable(itemsExpensesArray);

  var incomesOptions = {
    title: "Incomes",
    is3D: true,
    backgroundColor: "#696969",
    chartArea: { left: 40, top: 50, width: "100%", height: "90%" },
    legend: {
      position: "right",
      alignment: "center",
      textStyle: { color: "#fefefe", fontSize: 16 },
    },
    titleTextStyle: { color: "#fefefe", fontSize: 20 },
  };

  var expensesOptions = {
    title: "Expenses",
    is3D: true,
    backgroundColor: "#696969",
    chartArea: { left: 40, top: 50, width: "100%", height: "90%" },
    legend: {
      position: "right",
      alignment: "center",
      textStyle: { color: "#fefefe", fontSize: 16 },
      pagingTextStyle: { color: "#fefefe" },
      scrollArrows: {
        activeColor: "#fefefe",
        inactiveColor: "#979797",
      },
    },
    titleTextStyle: { color: "#fefefe", fontSize: 20 },
  };

  var incomesChart = new google.visualization.PieChart(
    document.getElementById("piechart_3d_incomes")
  );

  var expensesChart = new google.visualization.PieChart(
    document.getElementById("piechart_3d_expenses")
  );

  incomesChart.draw(incomesData, incomesOptions);
  expensesChart.draw(expensesData, expensesOptions);
}