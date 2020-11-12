<template>
    <div class="component-wrapper">
        <div class=" d-flex flex-column  col-sm-8 col-12 py-5 px-3"  :class="[toggleBackGround,toggleTextColor]">
            <div class="profile row p-3 p-md-0 justify-content-md-center justify-content-start" >
                <div class="ava mx-md-3 mx-1" :style="{'background-image':'url('+avatarSrc+')'}"></div>
                <span class="mx-md-3 mx-1">
                    Профиль
                </span>
            </div>
            <div class="nick-name row align-items-center p-3">
                <span class="mr-2">
                    Никнейм : {{nickName}}
                </span>
                <button v-if="!showNickNameInput"
                        @click="showNickNameInput = true"
                        class="btn btn-outline-primary p-1">
                    Сменить
                </button>
                <button v-else-if="showNickNameInput"
                        @click="changeNickName"
                        class="btn btn-outline-success p-1">
                    Принять
                </button>
                <button v-if="showNickNameInput"
                        @click="showNickNameInput = false"
                        class="btn btn-outline-secondary p-1 ml-1">
                    Скрыть
                </button>
                <input v-if="showNickNameInput"
                       type="text"
                       placeholder="Новый nickname"
                       v-model.trim.lazy="newNickName">
            </div>
            <div class="password row align-items-center p-3">
                <span class="mr-2">
                    Пароль : {{passwordViewSecret}}
                </span>
                <button v-if="!showPasswordInput"
                        @click="showPasswordInput = true"
                        class="btn btn-outline-primary p-1">
                    Сменить
                </button>
                <button v-else-if="showPasswordInput"
                        @click="changePassword"
                        class="btn btn-outline-success p-1">
                    Принять
                </button>
                <button v-if="showPasswordInput"
                        @click="showPasswordInput = false"
                        class="btn btn-outline-secondary p-1 ml-1">
                    Скрыть
                </button>
                <input v-if="showPasswordInput"
                       type="password"
                       placeholder="Новый password"
                       v-model.trim.lazy="newPassword">
            </div>
            <div class="row m-2">
                <button @click="logOut" class="btn btn-outline-warning font-weight-bold">
                    Выйти из аккаунта
                </button>
                <button @click="deleteAccount" class="btn btn-outline-danger font-weight-bold mx-2">
                    Удалить аккаунт
                </button>
            </div>
        </div>
        <div class="notification" id="notification" style="display: none">
            <strong>
                {{notificationMessage}}
            </strong>
        </div>
    </div>
</template>

<script>
import {colorToggle} from '../mixins/colorToggle.js'
import {validation} from "../mixins/validation";

export default {
    name: "Settings",
    mixins : [colorToggle,validation],
    data(){
        return {
            showNickNameInput : false,
            showLoginInput : false,
            showPasswordInput : false,
            newNickName : '',
            newPassword : '',
            passwordLength : 5,
            notificationMessage : '',
        }
    },
    methods: {
        logOut(){
            let store = this.$store,
                router = this.$router;
                axios.delete('api/profile/logOut')
                .then((response) => {
                    store.dispatch('logOut');
                    router.push('/lenta');
                })
        },
        deleteAccount(){
            let store = this.$store,
                router = this.$router,
                notify = this.notify;

            axios.delete('api/setting/profile')
            .then(resp=>{
                store.dispatch('logOut');
                router.push('/lenta');
            })
            .catch(error =>{
                if(error.response){
                    notify(error.response.data,2500);
                }
            })
        },
        changeNickName(){
            let notify = this.notify,
                config = {
                headers:{
                    'Content-type':'application/json'
                }
            };

            if(this.validateNickName(this.newNickName)){
                axios.patch('/api/setting/nickname',{
                    'name':this.newNickName
                },config)
                    .then(this.handleChangeNickName)
                    .catch(error => {
                        if(error.response){
                            notify(error.response.data,2500);
                        }
                    })
            }else{
                this.notify('Неверный формат никнейма',3000)
            }
        },

        changePassword(){
            let notify = this.notify,
                config = {
                headers:{
                    'Content-type':'application/json'
                }
            };
            if(this.validatePassword(this.newPassword)){

                axios.patch('api/setting/password',{
                    'password' : this.newPassword
                },config)
                .then(this.handleChangePassword)
                .catch(error=>{
                    if(error.response){
                        notify(error.response.data,2500);
                    }
                });

            }else{
                this.notify('Неверный формат пароля',2500);
            }
        },
        notify(message,timeOut){
            let notificationModal = document.getElementById('notification');
            notificationModal.style.display = 'block';
            this.notificationMessage = message;
            setTimeout(function () {
                notificationModal.style.display = 'none';
            },timeOut)
        },
        handleChangePassword(changePasswordResponseObj){
            this.passwordLength = changePasswordResponseObj.data.passwordLength;
            this.showPasswordInput = false;
            this.newPassword = '';
            this.notify('Успешно',3000);
        },
        handleChangeNickName(changeNickNameResponseObj) {
            this.$store.dispatch('setNickName',changeNickNameResponseObj.data.name)
            this.showNickNameInput = false;
            this.newNickName = '';
            this.notify('Успешно',3000);
        }
    },
    computed : {
        passwordViewSecret(){
           return "*".repeat(this.passwordLength)
        },
        nickName() {
            return this.$store.getters.getNickName;
        },
        avatarSrc() {
            return this.$store.getters.getAvatarSrc;
        }
    },


}
</script>

<style scoped>
 .profile{
     align-items: center;
     min-height:51px;
     border-bottom: 1px solid #8f8f92;
 }
 .ava{
     width: 40px;
     background-size: cover;
     height: 40px;
     border-radius: 50%;
 }
</style>
