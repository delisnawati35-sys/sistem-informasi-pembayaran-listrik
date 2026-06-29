document.addEventListener("DOMContentLoaded", function () {

    const tanggal = document.getElementById("tanggal");

    if (tanggal) {

        const sekarang = new Date();

        const opsi = {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
        };

        tanggal.innerHTML = sekarang.toLocaleDateString("id-ID", opsi);

    }

});