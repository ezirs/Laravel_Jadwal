import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import momentTimezonePlugin from "@fullcalendar/moment-timezone";
import interactionPlugin from "@fullcalendar/interaction";
import multiMonthPlugin from '@fullcalendar/multimonth';
import moment from 'moment';
import Swal from 'sweetalert2';
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
  

let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
let namaJadwal = document.getElementById('nama-jadwal');
let dateTime = document.getElementById('datetime');
let tanggalMulai = document.getElementById('tanggal-mulai');
let tanggalAkhir = document.getElementById('tanggal-akhir');
let _link = document.getElementById('link');
let warnaJadwal = document.getElementById('warna-jadwal');
let description = document.getElementById('description');
let simpanUbahJadwal = document.getElementById('simpan-ubah-jadwal');
let alertMessage = document.getElementById("alertMessage");
let tambahUbahJadwal = document.getElementById('tambahUbahJadwalModal');
let tambahUbahJadwalLabel = document.getElementById('tambahUbahJadwalLabel');
let tj;
let idSchedule = '';

const form = document.querySelector('#tambahUbahJadwalModal form');
let formIsDirty = false;

function setDefaultModal() {
    tambahUbahJadwalLabel.innerHTML = "Tambah Jadwal";
    simpanUbahJadwal.innerHTML = "Simpan Jadwal";
}

function closeTambahModal() {
    tanggalMulai.setAttribute('type', 'date');
    tanggalAkhir.setAttribute('type', 'date');
    formIsDirty = false;
    alertMessage.innerHTML = '';
    idSchedule = '';
    form.reset();
    tj.hide();
}

let calendar;
document.addEventListener("DOMContentLoaded", function () {
    const tambahUbahJadwalModal = new bootstrap.Modal(tambahUbahJadwal);
    tj = tambahUbahJadwalModal;
    let calendarEl = document.getElementById("calendar");
    calendar = new Calendar(calendarEl, {
        timeZone: "Asia/Jakarta",
        locale: "id",
        height: "100%",
        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
            interactionPlugin,
            momentTimezonePlugin,
            multiMonthPlugin
        ],
        customButtons: {
            tambahUbahJadwal: {
                text: "Tambah Jadwal",
                click: function () {
                    tambahUbahJadwalModal.show();
                },
            },
        },
        headerToolbar: {
            start: "title",
            end: "today prevYear prev,next nextYear",
        },
        footerToolbar: {
            start: "tambahUbahJadwal",
            end: "multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay,listWeek",
        },
        buttonText: {
            today: "Today",
            year: "Year",
            month: "Month",
            week: "Week",
            day: "Day",
            list: "List",
        },
        noEventsContent: "Tidak ada jadwal untuk ditampilkan",
        editable: true,
        selectable: true,
        events: '/api/schedules',
        eventDrop: (info) => handlerEventDrop(info),
        eventClick: (info) => alert(info.event.extendedProps.use_datetime),
        select: (info) => {
            tanggalMulai.value = moment(info.start).format("YYYY-MM-DD");
            tanggalAkhir.value = moment(info.end).subtract(1, 'days').format("YYYY-MM-DD");
            tambahUbahJadwalModal.show();
        },
        eventDidMount: (info) => handlerEventDidMount(info),
    });
    calendar.render();
});

simpanUbahJadwal.addEventListener("click", () => {
    let useDatetime = dateTime.checked;
    let data;
    let start;
    let end;

    if (!(namaJadwal.value && tanggalMulai.value)) {
        return;
    }
    
    if (!useDatetime) {
        start = moment(tanggalMulai.value).format("YYYY-MM-DD");
        end = tanggalAkhir.value ? moment(tanggalAkhir.value).add(1, "day").format("YYYY-MM-DD") : '';
        data = {
            id: idSchedule,
            title: namaJadwal.value,
            use_datetime: useDatetime,
            start: start,
            end: end,
            description: description.value,
            link: _link.value,
            schedule_color: warnaJadwal.value
        };
    } else {
        start = moment(tanggalMulai.value).format("YYYY-MM-DDTHH:mm:ss");
        end = tanggalAkhir.value ? moment(tanggalAkhir.value).format("YYYY-MM-DDTHH:mm:ss") : '';
        data = {
            id: idSchedule,
            title: namaJadwal.value,
            use_datetime: useDatetime,
            start_datetime: start,
            end_datetime: end,
            description: description.value,
            link: _link.value,
            schedule_color: warnaJadwal.value
        };
    }

    if (tanggalAkhir.value && end <= start) {
        alertMessage.innerHTML = '<div class="alert alert-danger" role="alert" id="danger-alert">Tanggal akhir harus lebih besar dari tanggal mulai.</div>';
        return;
    }

    fetch("/admin/schedules", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify(data),
    }).then(response => {
        if (response.ok) {
            closeTambahModal();
            calendar.refetchEvents();
            return response.json();
        }
    }).then(responseData => {
        Toast.fire({
            icon: "success",
            title: responseData.message
        });
    }).catch(error => {
        Toast.fire({
            icon: "error",
            title: "Gagal menghapus jadwal"
        });
    });;
});

form.addEventListener('input', () => {
    formIsDirty = true;
});

tambahUbahJadwal.addEventListener('hide.bs.modal', (e) => {
    if (formIsDirty) {
        const confirmClose = confirm('Apakah Anda yakin ingin menutup modal?\nData yang sudah diinput akan hilang.');
        if (!confirmClose) {
            e.preventDefault();
        } else {
            closeTambahModal();
        }
    } else {
        setDefaultModal();
        form.reset();
    }
});

dateTime.addEventListener('change', () => {
    datetimeChange();
});

function datetimeChange() {
    let mulaiValue = tanggalMulai.value;
    let akhirValue = tanggalAkhir.value;

    if (dateTime.checked) {
        if (mulaiValue) {
            mulaiValue = new Date(mulaiValue).toISOString().slice(0, 16);
        }
        if (akhirValue) {
            akhirValue = new Date(akhirValue).toISOString().slice(0, 16);
        }
        tanggalMulai.setAttribute('type', 'datetime-local');
        tanggalAkhir.setAttribute('type', 'datetime-local');
    } else {
        if (mulaiValue) {
            mulaiValue = mulaiValue.slice(0, 10);
        }
        if (akhirValue) {
            akhirValue = akhirValue.slice(0, 10);
        }
        tanggalMulai.setAttribute('type', 'date');
        tanggalAkhir.setAttribute('type', 'date');
    }

    tanggalMulai.value = mulaiValue;
    tanggalAkhir.value = akhirValue;
}

function handlerEventDrop(info) {
    if (!confirm("Apakah Anda yakin ingin mengubah jadwal tersebut?")) {
        info.revert();
    } else {
        let useDatetime = info.event.extendedProps.use_datetime;
        let data;
        let start;
        let end;
        
        if (!useDatetime) {
            start = moment(info.event.start).format("YYYY-MM-DD");
            end = info.event.end ? moment(info.event.end).format("YYYY-MM-DD") : '';
            data = {
                use_datetime: useDatetime,
                start: start,
                end: end
            };
        } else {
            start = moment(info.event.start).format("YYYY-MM-DDTHH:mm:ss");
            end = info.event.end ? moment(info.event.end).format("YYYY-MM-DDTHH:mm:ss") : '';
            data = {
                use_datetime: useDatetime,
                start_datetime: start,
                end_datetime: end
            };
        }

        fetch(`/admin/schedules/${info.event.id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify(data),
        }).then(response => {
            if (response.ok) {
                calendar.refetchEvents();
                return response.json();
            } else {
                info.revert();
            }
        }).then(responseData => {
            Toast.fire({
                icon: "success",
                title: responseData.message
            });
        }).catch(error => {
            Toast.fire({
                icon: "error",
                title: "Gagal menghapus jadwal"
            });
        });;
    }
}

function handlerEventDidMount(info) {
    info.el.addEventListener("contextmenu", (e) => {
        e.preventDefault();
        let existingMenu = document.getElementById("context-menu");
        existingMenu && existingMenu.remove();

        let menu = document.createElement("div");
        menu.id = "context-menu";
        menu.className = "dropdown-menu";
        menu.style.display = "block";
        menu.style.position = "absolute";
        menu.innerHTML = `
            <button class="dropdown-item">Edit</button>
            <button class="dropdown-item">Delete</button>
        `;
        menu.style.top = e.pageY + "px";
        menu.style.left = e.pageX + "px";

        document.body.appendChild(menu);

        menu.addEventListener("click", (event) => {
            if (event.target.classList.contains("dropdown-item")) {
                let action = event.target.textContent.trim();
                if (action === "Edit") {
                    idSchedule = info.event.id;
                    namaJadwal.value = info.event.title;
                    description.value = info.event.extendedProps.description ? info.event.extendedProps.description : '';
                    _link.value = info.event.url;
                    warnaJadwal.value = info.event.backgroundColor;

                    if (!info.event.extendedProps.use_datetime) {
                        dateTime.checked = false;
                        tanggalMulai.value = moment(info.event.start).format("YYYY-MM-DD");
                        tanggalAkhir.value = info.event.end ? moment(info.event.end).subtract(1, 'days').format("YYYY-MM-DD") : '';
                        datetimeChange();
                    } else {
                        dateTime.checked = true;
                        tanggalMulai.value = moment(info.event.start).format("YYYY-MM-DDTHH:mm:ss");
                        tanggalAkhir.value = info.event.end ? moment(info.event.end).format("YYYY-MM-DDTHH:mm:ss") : '';
                        datetimeChange();
                    }

                    tambahUbahJadwalLabel.innerHTML = "Ubah Jadwal";
                    simpanUbahJadwal.innerHTML = "Ubah Jadwal";
                    tj.show();
                } else if (action === "Delete") {
                    if (confirm("Apakah Anda yakin ingin menghapus jadwal ini?")) {
                        fetch(`/admin/schedules/${info.event.id}`, {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken
                            },
                        }).then(response => {
                            if (response.ok) {
                                calendar.refetchEvents();
                                return response.json();
                            }
                        }).then(responseData => {
                            Toast.fire({
                                icon: "success",
                                title: responseData.message
                            });
                        }).catch(error => {
                            Toast.fire({
                                icon: "error",
                                title: "Gagal menghapus jadwal"
                            });
                        });
                    }
                }
                menu.remove();
            }
        });

        document.addEventListener("click", () => {
            menu.remove();
        }, { once: true });
    });
}
