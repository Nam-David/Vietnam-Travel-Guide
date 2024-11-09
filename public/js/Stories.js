fetch("/include/footer.html")
    .then(response => response.text())
    .then(data => {
        document.getElementById("footer1").innerHTML = data;
    });

fetch("/include/header3.html")
    .then(response => response.text())
    .then(data => {
        document.getElementById("header1").innerHTML = data;
    });