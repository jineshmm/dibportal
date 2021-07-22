/// <reference path="../directives/jquery.d.ts" />
/// <reference path="../directives/underscore.d.ts" />
/// <reference path="../directives/jqueryui.d.ts" />
var Appointment = /** @class */ (function () {
    function Appointment(options) {
        this.defaultSettings = {
            defaultEvents: '',
            addUrl: '',
            deleteUrl: ''
        };
        this.body = $("body");
        this.calendar = $('#calendar');
        this.event = ('#calendar-events div.calendar-events');
        this.categoryForm = $('#add-new-event form');
        this.extEvents = $('#calendar-events');
        this.modal = $('#my-event');
        this.saveCategoryBtn = $('.save-category');
        this.settings = $.extend(true, {}, this.defaultSettings, options);
    }
    Appointment.prototype.initialSetting = function (calenderId) {
        this.initCalender(calenderId);
    };
    Appointment.prototype.initCalender = function (calenderId) {
        this.enableDrag();
        var $this = this;
        $this.calendar = $('#' + calenderId);
        $this.calendarObj = $this.calendar.fullCalendar({
            slotDuration: '00:15:00',
            minTime: '08:00:00',
            maxTime: '19:00:00',
            defaultView: 'month',
            handleWindowResize: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            events: this.settings.defaultEvents,
            editable: false,
            droppable: true,
            eventLimit: true,
            selectable: true,
            drop: function (date) { $this.onDrop($(this), date); },
            select: function (start, end, allDay) { $this.onSelect(start, end, allDay); },
            eventClick: function (calEvent, jsEvent, view) { $this.onEventClick(calEvent, jsEvent, view); },
            eventResize: function (info) {
                alert(info.event.title + " end is now " + info.event.end.toISOString());
            }
        });
        //on new event
        this.saveCategoryBtn.on('click', function () {
            var categoryName = $this.categoryForm.find("input[name='category-name']").val();
            var categoryColor = $this.categoryForm.find("select[name='category-color']").val();
            if (categoryName !== null && categoryName.length != 0) {
                $this.extEvents.append('<div class="calendar-events" data-class="bg-' + categoryColor + '" style="position: relative;"><i class="fa fa-circle text-' + categoryColor + '"></i>' + categoryName + '</div>');
                $this.enableDrag();
            }
        });
    };
    Appointment.prototype.enableDrag = function () {
        $(this.event).each(function () {
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()),
                id: $.trim($(this).attr('data-event-id')) // use the element's text as the event title
            };
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,
                revertDuration: 0 //  original position after the drag
            });
        });
    };
    Appointment.prototype.onSelect = function (start, end, allDay) {
        var $this = this;
        var template = _.template($("#multiple_events_add_template").html());
        var result = template();
        $("#add-multiple-new-event").remove();
        $('body').append(result);
        var form = $("form#form_multipleday_events_add");
        $this.modal = $("#add-multiple-new-event");
        $("form#form_multipleday_events_add").append("<input class='form-control' value='" + moment(start).format('YYYY-MM-DD HH:MM') + "' type='hidden' name='beginning' />");
        $("form#form_multipleday_events_add").append("<input class='form-control' value='" + moment(end).format('YYYY-MM-DD HH:MM') + "' type='hidden' name='ending'/>");
        $this.modal.modal({
            backdrop: 'static'
        });
        $this.modal.find('.save-event').unbind('click').click(function () {
            //$("form#form_multipleday_events_add").submit();
            $.ajax({
                url: form.attr('action'),
                type: "post",
                data: {
                    'formData': $("form#form_multipleday_events_add").serializeArray()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (data) {
                if (data.status) {
                    window.location.href = data.redirect;
                }
            });
        });
        $this.modal.find('form').on('submit', function () {
            var title = form.find("input[name='category-name']").val();
            var beginning = form.find("input[name='beginning']").val();
            var ending = form.find("input[name='ending']").val();
            var categoryClass = form.find("select[name='category-color'] option:checked").val();
            if (title !== null && title.length != 0) {
                $this.calendarObj.fullCalendar('renderEvent', {
                    title: title,
                    start: start,
                    end: end,
                    allDay: false,
                    className: categoryClass
                }, true);
                $this.modal.modal('hide');
            }
            else {
                alert('You have to give a title to your event');
            }
            return false;
        });
        $this.calendarObj.fullCalendar('unselect');
    };
    /* on drop */
    Appointment.prototype.onDrop = function (eventObj, date) {
        var $this = this;
        // retrieve the dropped element's stored Event Object
        var originalEventObject = eventObj.data('eventObject');
        var $categoryClass = eventObj.attr('data-class');
        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject);
        // assign it the date that was reported
        copiedEventObject.start = date;
        if ($categoryClass)
            copiedEventObject['className'] = [$categoryClass];
        // render the event on the calendar
        $this.calendar.fullCalendar('renderEvent', copiedEventObject, true);
        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
            // if so, remove the element from the "Draggable Events" list
            eventObj.remove();
        }
        // ajax call 
    };
    /* on click on event */
    Appointment.prototype.onEventClick = function (calEvent, jsEvent, view) {
        var $this = this;
        var dataobj = { id: calEvent.id, title: calEvent.title, start: calEvent.start, end: calEvent.end, appointmentId: calEvent.appointId };
        var template = _.template($("#dpib-new-appointments").html());
        var result = template(dataobj);
        $("#add-appointment-new-event").remove();
        $('body').append(result);
        $this.modal = $("#add-appointment-new-event");
        $this.modal.modal({
            backdrop: 'static'
        });
        var form = $("form#form_add_appointments_add");
        $this.modal.find('.delete-event').unbind('click').click(function () {
            $this.calendarObj.fullCalendar('removeEvents', function (ev) {
                return (ev._id == calEvent._id);
            });
            $this.modal.modal('hide');
        });
        $this.modal.find('.save-appointment').off('click');
        $this.modal.find('.save-appointment').on('click', function () {
            $.ajax({
                url: form.attr('action'),
                type: "post",
                data: {
                    'formData': $("form#form_add_appointments_add").serializeArray()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (data) {
                if (data.status) {
                    //window.location.href = data.redirect;
                }
            });
            calEvent.title = form.find("input[type=text]").val();
            $this.calendarObj.fullCalendar('updateEvent', calEvent);
            $this.modal.modal('hide');
            return false;
        });
        $this.modal.find('.delete-event').off('click');
        $this.modal.find('.delete-event').on('click', function () {
            $.ajax({
                url: $this.settings.deleteUrl,
                type: "post",
                data: {
                    'id': $("form#form_add_appointments_add").find("input[name='appointmentId']").val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (data) {
                if (data.status) {
                    //window.location.href = data.redirect;
                }
            });
            $this.calendarObj.fullCalendar('removeEvents', function (ev) {
                return (ev._id == calEvent._id);
            });
            $this.modal.modal('hide');
        });
    };
    Appointment.prototype.resizeTime = function (calEvent, jsEvent, view) {
        var _that = this;
        $.ajax({
            url: form.attr('action'),
            type: "post",
            data: {
                'formData': $("form#form_multipleday_events_add").serializeArray()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }).done(function (data) {
            if (data.status) {
                window.location.href = data.redirect;
            }
        });
    };
    return Appointment;
}());
