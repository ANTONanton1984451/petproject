<template>
    <div class="row login-form" :class="[!backGroundAppIsBlack?'opacity':'']">
        <div class="form"
             :class="[!backGroundAppIsBlack?'shadow':'']">
            <img @click="noShowMe" class="cancel" src="\images\close.svg">

            <notice class="notice" v-if="presentNotice">
                {{noticeMessage}}
            </notice>

            <transition name="change-form" mode="out-in">
                <component
                @toggleForm="toggleForm"
                @showNotice="showNotice"
                @stopShowNotice="stopShowNotice"
                @authComplete="noShowMe"
                :is="currentComponent"/>
            </transition>
        </div>
    </div>

</template>

<script>
    import Register from "./Register";
    import Login from "./Login";
    import Notice from "../additional_components/Notice";
    export default {
        name: "Register",
        components:{
            Register,
            Login,
            Notice
        },
        data(){
            return{
                currentComponent:Login,
                noticeMessage:'',
                presentNotice:false
            }
        },
        methods:{
            noShowMe(){
                this.$emit('noShowMe');
            },
            toggleForm(){
                this.currentComponent = (this.currentComponent === Register) ? Login : Register;
            },
            showNotice(message){
                this.presentNotice = true;
                this.noticeMessage = message;
            },
            stopShowNotice(){
                this.presentNotice = false;
            }
        },
        computed:{
            backGroundAppIsBlack(){
                return this.$store.getters.getAPPStatus;
            }
        }

    }
</script>

<style scoped>
    .form{
        color:black;
        position: relative;
    }
    .login-form{
        z-index:15;
        /*ToDo::Пофиксить ширину(Ширина этого блока не на весь экран!)*/
        width:100vw;
        height:100vh;
        justify-content: center;
        position:fixed;
        top:0;
        left:0;
    }
    .form{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        background-color: white;
        border-radius:5px;
        width:50%;
        height:100%;
    }
    .cancel{
        position:absolute;
        width: 20px;
        right: 25px;
        top:25px;
    }
    .cancel:hover{
        cursor:pointer;
    }
    .fake-link{
        color:blue;
    }
    .fake-link:hover{
        text-decoration:underline;
    }
    .shadow{
        box-shadow: 0 0 3px black;
    }
    .notice{
        position: absolute;
        top: 50px;
        width: 100%;
    }
    @media (max-width: 768px) {
        .form{
            width:99%;
        }
    }

</style>
