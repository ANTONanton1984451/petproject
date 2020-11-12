<template>
    <div  class="component-wrapper">
        <div v-if="!loginIsProcessed">
            <strong class="mb-5 title">
                Вход
            </strong>
            <div class="my-2 row justify-content-around align-items-center">
                <input @input="checkInput" class="p-1" type="text" v-model.trim="login" placeholder="Логин">
            </div>
            <div class="mb-4 row  justify-content-around  align-items-center">
                <input @input="checkInput" class="p-1" v-model.trim="password" type="password" placeholder="Пароль">
            </div>
            <button disabled id="login-btn" @click="signIn" class="btn  btn-outline-primary">
                <strong>
                    Войти
                </strong>
            </button>
            <span>
                Впервые в OFFTop?
            </span>
            <span @click="callRegister" class="fake-link font-weight-bold">
                Регистрация
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

export default {
        name: "Login",
        data(){
          return{
                notificationMessage : '',
                loginIsProcessed : false,
                login : '',
                password : ''
          }
        },
        methods:{
            callRegister(){
                this.$emit('toggleForm')
            },
            signIn(){
                this.loginIsProcessed = true;
                //todo::Переделать логин!
                let data = {
                    'email':this.login,
                    'password':this.password
                }
                let config = {
                    headers:{
                        'Content-type':'application/json'
                    }
                }
                axios.post('/api/login',data,config)
                .then(this.initAuth)
                .catch(this.catchError)
            },

            catchError(loginErrorObj){
                if(loginErrorObj.response.status === 401){

                    this.loginIsProcessed = false;
                    this.notify(loginErrorObj.response.data,2500);

                }else{

                    this.loginIsProcessed = false;
                    this.notify('Неизвестная ошибка,попробуйте ещё раз');

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
            checkInput(){
              let loginButton = document.getElementById('login-btn')
              loginButton.disabled = !(this.login !== '' && this.password !== '');
            },
            initAuth(loginResponseObj){
                this.$store.dispatch('auth',loginResponseObj.data);
                this.$emit('authComplete');
            }
        }
    }
</script>

<style scoped>
    input{
        width:80%;
    }
    button{
        width: 100%;
    }
    .title{
        font-size: 3rem;
    }
</style>
