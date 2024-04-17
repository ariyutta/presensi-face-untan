// Attendance Frontend Scripts


setInterval(displayClock, 500);

function displayClock() {
    var time = new Date();
    var hrs = time.getHours();
    var min = time.getMinutes();
    var sec = time.getSeconds();

    // Tambahkan nol di depan angka jika perlu
    hrs = (hrs < 10) ? '0' + hrs : hrs;
    min = (min < 10) ? '0' + min : min;
    sec = (sec < 10) ? '0' + sec : sec;

    document.getElementById("clock").innerHTML = hrs + ':' + min + ':' + sec + ' WIB';
}
