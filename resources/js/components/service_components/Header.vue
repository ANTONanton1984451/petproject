<template>
  <header class="col m-0 row align-items-center justify-content-between p-1"
          :class="toggleBackGround">
          <log-reg v-if="showLoginForm" @noShowMe="toggleShowLoginForm"></log-reg>
         <keep-alive>
             <in-queue v-if="showQueueForm" @noShowMe="toggleShowQueueForm"></in-queue>
         </keep-alive>

         <router-link :class="[toggleTextColor,'logo col-auto']"
                      :to="{name:'lenta'}"
                       >
             OFFTop
         </router-link>
     <span class="lk-message row mx-1 p-1 col-auto" id="lk"
           :class="toggleTextColor"
           @click="toggleShowSettings">
         <img src="/images/user.svg" class="user">
          <span v-if="isAuth">
              {{nickName}}
          </span>
         <img src="/images/open.svg" class="arrow">
     </span>

      <div class="options p-2 flex-column" id="options"
           v-show="showProfileSettings"
           :class="toggleBackGround"
           v-click-outside="clickOutSide">
          <span class="label ">
             Тёмная тема
          </span>
          <span :class="btnClass" class="main-chapter switch-btn" @click.self="toggleColor">
          </span>
          <span class="label">
              Аккаунт
          </span>
          <div v-if="isAuth" class="d-flex flex-column align-items-start">
              <router-link  :to="{name:'profile.new',params:{type:'overview',username:userId}}"
                            :class="toggleTextColor">
                  Профиль
              </router-link>
              <router-link  :to="{name:'settings'}"
                            :class="toggleTextColor">
                  Настройки
              </router-link>
              <router-link  :to="{name:'statistic'}"
                            :class="toggleTextColor">
                  Моя статистика
              </router-link>
              <router-link  :to="{name :'community.create'}"
                            :class="toggleTextColor">
                  Создать сообщество
              </router-link>
              <span class="fake-link" @click="toggleShowQueueForm"
                    :class="toggleTextColor">
              Ожидают обработки
             </span>
              <router-link :to="{name:'create',params:{type:'new'}}"
                           :class="toggleTextColor">
                  Новый пост
              </router-link>
          </div>

          <strong class="pointer" v-else @click="toggleShowLoginForm" :class="toggleTextColor">
              Войти
          </strong>
      </div>
  </header>
</template>

<script>
    import {colorToggle} from '../../mixins/colorToggle.js';
    import vClickOutside from 'v-click-outside'
    import LogReg from './LogRegTemplate.vue'
    import InQueue from "./InQueue";
    export default {
        name: "my-header",
        mixins:[colorToggle],
        components:{'log-reg':LogReg,InQueue},
        directives: {
            clickOutside: vClickOutside.directive
        },
        data(){
            return{
                showQueueForm : false,
                showProfileSettings : false,
                showLoginForm : false,
            }
        },
        methods:{
            toggleColor(){
                this.$store.dispatch('toggleColor');
            },
            toggleShowSettings(){
                this.showProfileSettings = !this.showProfileSettings;
            },
            toggleShowLoginForm(){
                this.showLoginForm = !this.showLoginForm;
                this.showProfileSettings = false;
            },
            toggleShowQueueForm(){
                this.showQueueForm = !this.showQueueForm;
                this.showProfileSettings = false;
            },
            clickOutSide(e){
             let lk = document.getElementById('lk');
             let target = e.target;

                if(this.showProfileSettings && target !== lk && target.parentNode !== lk ){
                    this.showProfileSettings = false;
                }
            }
        },
        computed:{
            nickName(){
                return this.$store.getters.getNickName;
            },
            userId(){
              return this.$store.getters.getID;
            },
            isAuth(){
              return this.$store.getters.isAuth;
            },
            btnClass(){
                if(this.$store.getters.getTextStatus){
                    return '';
                }else{
                   return 'switch-on';
                }
            }
        },
        mounted(){
            if(this.$store.getters.getAPPStatus){
                document.body.style="background-color:black";
            }else{
                document.body.style="background-color:#e4e0ff";
            }
        },
        updated() {
            if(this.$store.getters.getAPPStatus){
                document.body.style="background-color:black";
            }else{
                document.body.style="background-color:#e4e0ff";
            }
        }
    }
</script>

<style scoped>
    .fake-link,
    a{
        font-weight: bold;
        font-size: 1rem;
    }
     header{
         height:52px;
         position:fixed;
         top:0;
         z-index:2;
     }
     .pointer{
         cursor:pointer
     }
     .logo{
         font-size: 1.5rem;
         font-weight: bolder;
         transition: font-size 0.1s linear;

     }
    .logo:hover{
        text-decoration:none;
        font-size: 1.7rem;
    }
    .lk{
        border-left: 1px solid black;
    }
    .lk-message{
        justify-content: space-around;
        align-items: center;
        border:1px solid #8f8f92;
        border-radius: 5px;
    }
     .lk-message:hover{
         cursor:pointer;
     }
    .label{
        color:#8f8f92;
    }
    .user{
        width: 32px;
    }
    .arrow {
        margin-left:10px;
        width: 18px;
    }
    .main-chapter{

        height:40px;
    }

    .options{
        display: flex;
        position: fixed;
        justify-content: flex-start;
        align-items:flex-start;
        right: 0;
        top: 53px;
        width: 200px;
        height: 250px;
        z-index:10;
        border-radius: 5px;
        border:1px solid #8f8f92;
    }
     .switch-btn {
         display: inline-block;
         width: 62px; /* ширина переключателя */
         height: 20px; /* высота переключателя */
         border-radius: 10px; /* радиус скругления */
         background: #bfbfbf; /* цвет фона */
         z-index: 0;
         margin: 0;
         padding: 0;
         border: none;
         cursor: pointer;
         position: relative;
         transition-duration: 300ms; /* анимация */
     }
     .switch-btn::after {
         content: "";
         height: 24px; /* высота кнопки */
         width: 36px; /* ширина кнопки */
         border-radius: 18px; /* радиус кнопки */
         background: #fff; /* цвет кнопки */
         top: -2px; /* положение кнопки по вертикали относительно основы */
         left: -6px; /* положение кнопки по горизонтали относительно основы */
         transition-duration: 300ms; /* анимация */
         box-shadow: 0 0 10px 0 #999999; /* тень */
         position: absolute;
         z-index: 1;
     }
     .switch-on {
         background: #fff;
         box-shadow: inset 0 0 10px 0 #999999; /* тень */
     }
     .switch-on::after {
         left: 30px;
         background: #00AD97;
     }



</style>
