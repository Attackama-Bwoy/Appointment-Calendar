<template>
  <div>
    <fullcalendar :options="calendarOptions"/>
  </div>
</template>

<script>
import '@fullcalendar/core/vdom'
import Fullcalendar from '@fullcalendar/vue3'
import daygrid from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGrid from '@fullcalendar/timegrid';
import route from '../../../vendor/tightenco/ziggy/src/js';

var Emitter = require('tiny-emitter');
var emitter = new Emitter();

export default {
    components:{
        Fullcalendar
    },
    data(){
        return {
            calendarOptions: {
                plugins: [ daygrid, interactionPlugin, timeGrid ],
                initialView: 'dayGridMonth',
                headerToolbar:{
                    left: 'prev,next,today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                allDaySlot: false,
                slotMinTime: '09:00:00',
                slotMaxTime: '19:00:00',
                weekends: false,
                slotDuration: '00:60:00',
                selectOverlap: false,
                dateClick: this.handleDateClick,
                }
            }
        },
        beforeMount(){
            this.$data.calendarOptions.events = {
                url: route('appointment.index'),
                method: 'GET',
                failure: error=>{
                    console.log('tenemos este error ', error.message);
                }
            }
        },
        mounted(){
            emitter.on('refreshCalendar', function (){
            this.refreshCalendar()
            });
        },
        methods: {
            handleDateClick(clickInfo){
                this.$emit('dateClick', clickInfo)
            },
            refreshCalendar(){
                
            }
        }
    }
</script>

<style>

</style>