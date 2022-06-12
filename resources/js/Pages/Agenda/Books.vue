<template>
    <app-layout>
      <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Dance Calendar 💀 You want to dance? ☠️
            </h1>
            <div role="alert" v-if="showAlert" class="mt-2">
                <div class="bg-green-500 text-white font-bold rounded-t px-4 py-2">
                  <p>{{$page.props.flash.success}}</p>
                </div>
              </div>
        </template>
      <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Calendar @dateClick="dateClick" :key="componentKey"/>
                </div>
            </div>
        </div>
        <modal-calendar v-if="showModal" :form="newEvent" @closeModal="closeModal" @saveAppt="saveAppt"/>
    </app-layout>
</template>

<script>
import AppLayout from '../../Layouts/AppLayout'
import Calendar from '../../components/Calendar'
import ModalCalendar from '../../components/Modals/CalendarModal'
import formatTime from '../../Mixins/transformTime'
import { Inertia } from '@inertiajs/inertia'

var Emitter = require('tiny-emitter');
var emitter = new Emitter();

export default {
    name: 'Books',
    components: {
        Calendar,
        AppLayout,
        ModalCalendar
    },
    computed:{
      showAlert(){
        if(this.$page.props.flash.success !== null){
          setTimeout(()=>{
            this.$page.props.flash.success = null
          },3000)
          return true
        }
        return false
      }
    },
    data(){
      return {
        showModal: false,
        componentKey: 0,
        newEvent: {
          title: '',
          date_at: '',
          hour: '',
          user_id: '',
          session: 3600
        }
      }
    },
    methods:{
      dateClick(arg){
        this.$data.showModal=true
        this.setModalOpen(arg)
      },
      closeModal(){
        this.$data.showModal=false
      },
      setModalOpen(obj){
        const dateAndTime = obj.dateStr.split("T")

        this.newEvent.date_at = dateAndTime[0]
        this.newEvent.hour = dateAndTime[1].substr(0, 8)
        this.newEvent.user_id = this.$page.props.user.id
        return
      },
      resetModal(){
        this.newEvent = {
          title: '',
          date_at: '',
          hour: '',
          user_id: '',
          session: 3600
        }
      },
      saveAppt(param){
        if(param.title === ''){
          alert('No puedes dejar el campo motivo vacio')
        }
        //variable
        let dataAppt = this.setDurationSesion(param)

        Inertia.post(route('appointment.store'), dataAppt,{
          onSuccess: page =>{
            this.closeModal();
            this.resetModal();
            this.componentKey += 1;
            /* if(Object.entries(page.props.errors).lenght === 0){
              this.closeModal();
              this.resetModal();
            } */
            //emitter
            emitter.emit('refreshCalendar');
          }
        })
        // exceptions
        Inertia.on("error", event => {
          event.preventDefault();
          console.log('capturamos este error ', error.message);
        })
      },
      setDurationSesion(form){
        let dateApt = form.date_at + " "+ form.hour

        let initSesion = new Date(dateApt)

        const getSecondsSesion = initSesion.getSeconds() + form.session

        initSesion.setSeconds(getSecondsSesion)

        return {
          title: form.title,
          start: dateApt,
          end: formatTime(initSesion),
          session: form.session,
          user_id: form.user_id
        }
      }
    }
}
</script>

<style>

</style>