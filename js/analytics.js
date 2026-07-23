/* ==========================================
   ALIGN Analytics Charts
========================================== */

// ---------------------------
// Expense by Category
// ---------------------------

const categoryCanvas = document.getElementById("categoryChart");

if (categoryCanvas) {

    new Chart(categoryCanvas, {

        type: "doughnut",

        data: {

            labels: categoryLabels,

            datasets: [{

                label: "Expense",

                data: categoryData,

                backgroundColor: [

                    "#D78FA6",
                    "#BF6F89",
                    "#F6C6D5",
                    "#F4B183",
                    "#57B894",
                    "#7C8CF8",
                    "#FFD166",
                    "#F06A7F"

                ],

                borderColor: "#ffffff",
                borderWidth: 2,
                hoverOffset: 12

            }]

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "bottom"

                }

            }

        }

    });

}



// ---------------------------
// Monthly Income vs Expense
// ---------------------------

const incomeCanvas = document.getElementById("incomeExpenseChart");

if (incomeCanvas) {

    new Chart(incomeCanvas, {

        type: "bar",

        data: {

            labels: months,

            datasets: [

                {

                    label: "Income",

                    data: incomeData,

                    backgroundColor: "#57B894",

                    borderRadius: 10

                },

                {

                    label: "Expense",

                    data: expenseData,

                    backgroundColor: "#F06A7F",

                    borderRadius: 10

                }

            ]

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: "top"

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: function(value){

                            return "₹" + value;

                        }

                    }

                }

            }

        }

    });

}



// ---------------------------
// Last 30 Days Spending Trend
// ---------------------------

const trendCanvas = document.getElementById("trendChart");

if (trendCanvas) {

    new Chart(trendCanvas, {

        type: "line",

        data: {

            labels: trendMonths,

            datasets: [{

                label: "Daily Expense",

                data: trendAmounts,

                borderColor: "#BF6F89",

                backgroundColor: "rgba(215,143,166,0.20)",

                fill: true,

                tension: 0.4,

                pointRadius: 6,

                pointHoverRadius: 8,

                pointBackgroundColor: "#BF6F89",

                pointBorderColor: "#ffffff",

                pointBorderWidth: 2

            }]

        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                legend: {

                    display: false

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: function(value){

                            return "₹" + value;

                        }

                    }

                }

            }

        }

    });

}