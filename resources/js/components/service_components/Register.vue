<template>
    <div class="component-wrapper">
        <div v-if="!registerIsProcessed" class="d-flex flex-column">
            <strong class="mb-5 title">
                Регистрация
            </strong>
            <div class="my-2 row justify-content-around align-items-center">
                <input v-model.trim="password"
                       @input="checkInput"
                       @focusout="stopShowNotice"
                       @focus="callNickNameNotice"
                       type="text"
                       class="p-1"
                       placeholder="Ваше имя">
            </div>
            <div class="my-2 row justify-content-around align-items-center">
                <input v-model.trim="login"
                       @input="checkInput"
                       @focusout="stopShowNotice"
                       @focus="callLoginNotice"
                       type="text"
                       class="p-1"
                       placeholder="Логин">
            </div>
            <div class="my-2 row justify-content-around align-items-center">
                <input v-model.trim="nickName"
                       @input="checkInput"
                       @focusout="stopShowNotice"
                       @focus="callPasswordNotice"
                       type="password"
                       class="p-1"
                       placeholder="Пароль">
            </div>
            <button @click="register" disabled id="register-btn" class="btn col btn-outline-primary">
                <strong>
                    Регистрация
                </strong>
            </button>

            <span>
            Уже имеете профиль?
            </span>
                <span @click="callLogin" class="fake-link font-weight-bold">
                Войти
            </span>
        </div>
        <div v-else class="spinner-border text-primary" style="width:3rem; height:3rem" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="notification" id="notification" style="display: none">
            <strong>
                {{notificationMessage}}
            </strong>
        </div>
    </div>
</template>

<script>
    import {validation} from "../../mixins/validation";
    export default {
        name: "Register",
        mixins : [validation],
        data(){
            return{
                notificationMessage : '',
                registerIsProcessed : false,
                password:'',
                nickName:'',
                login:''
            }
        },
        methods:{
            callLogin(){
                this.$emit('toggleForm')
            },
            callNickNameNotice(){
                let message = "Никнейм может состоять только из букв английского алфавита,арабских цифр,символа нижнего подчёркивания и точки.\r" +
                    "Имя должно быть уникальным."+
                    "Максимальная длинна : 15 символов";
                this.$emit('showNotice',message)
            },
            callLoginNotice(){
                let message = "Логин может сотоять из букв английского алфавита и арабских цифр.\r" +
                    "Максимальная длинна 15 символов";
                this.$emit('showNotice',message);
            },
            callPasswordNotice(){
                let message = "Пароль может состоять из букв английского алфавита,арабских цифр и любых символов.\r" +
                    "Максимальная длинна : 255 символов";
                this.$emit('showNotice',message);
            },
            stopShowNotice(){
                this.$emit('stopShowNotice');
            },
            checkInput(){
                let registerBtn = document.getElementById('register-btn');
                registerBtn.disabled = !(this.login !== '' && this.password !== '' && this.nickName !== '');
            },
            register(){
                if(this.validateInputs()){
                    this.registerIsProcessed = true;
                    let data = {
                            'password' : this.password,
                            'email' : this.login,
                            'name' : this.nickName
                        },
                        config = {
                            headers : {
                                'Content-type':'application/json'
                            }
                        }
                    axios.post('/api/register',data,config)
                        .then(this.initAuth)
                        .catch(error => {
                            console.log(error)
                        });
                }else{
                  let message = 'Ваши данные введены некоректно';
                  this.notify(message,3000);
                }
            },
            validateInputs(){
              return this.validateLogin(this.login)
                     && this.validatePassword(this.password)
                     && this.validateNickName(this.nickName);
            },
            notify(message,timeOut){
                let notificationModal = document.getElementById('notification');
                notificationModal.style.display = 'block';
                this.notificationMessage = message;
                setTimeout(function () {
                    notificationModal.style.display = 'none';
                },timeOut)
            },
            initAuth(registerResponseObj) {
                console.log(registerResponseObj.data);
                this.$store.dispatch('auth',registerResponseObj.data);
                this.$emit('authComplete');
            }
        }
    }
</script>

<style scoped>
    #notification{
        position: absolute;
        left: 35%;
        top:55px;
    }
    input{
        width:80%;
    }
    button{
        width:auto;
    }
    .title{
        font-size:3rem;
    }
</style>
