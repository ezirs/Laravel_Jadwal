import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import momentTimezonePlugin from "@fullcalendar/moment-timezone";
import interactionPlugin from "@fullcalendar/interaction";
import multiMonthPlugin from '@fullcalendar/multimonth';

document.addEventListener("DOMContentLoaded", function () {
    let calendarEl = document.getElementById("calendar");
    let calendar = new Calendar(calendarEl, {
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
        editable: false,
        selectable: false,
        events: '/api/schedules',
        eventClick: (info) => alert(info.event.extendedProps.use_datetime),
    });
    calendar.render();
});