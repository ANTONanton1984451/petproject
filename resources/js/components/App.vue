<template>
    <div id="app" class="container-fluid p-0 h-auto">
        <my-header id="header"></my-header>
        <transition name="fade" mode="out-in">
            <router-view style="margin-top:52px"/>
        </transition>
        <!--todo:Сделать анимацию для этого скроллера -->
        <transition name="to-up">
        <span @click="toUp" :class="[toggleBtnColor,toggleTextColor]" class="scroller" v-show="showScroller">
            UP
        </span>
        </transition>

    </div>
</template>

<script>
    import MyHeader from "./service_components/Header";
    import {colorToggle} from '../mixins/colorToggle.js'
    export default {
        name: "App",
        mixins:[colorToggle],
        components:{MyHeader},
        data(){
            return{
                showScroller:false
            }
        },
        methods:{
            scrollChecker(){
                this.showScroller = window.innerHeight - window.scrollY < 0;
            },
            toUp(){
                window.scrollBy(0, - window.scrollY);
            },
            handleAuthResponse(authResponseObj){
                this.$store.dispatch('auth',authResponseObj.data);
            },
            handleAuthError(authErrorObj){

            }
        },
        created() {
            window.addEventListener('scroll',this.scrollChecker);
            this.$store.dispatch('checkLocalStorage');
        },
        beforeMount() {
            axios.get('/api/auth')
            .then(this.handleAuthResponse)
            .catch(this.handleAuthError);

        }
    }
</script>

<style>
    #app {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: white;
    }
    .fade-enter-active,.fade-leave-active {
        transition: opacity 0.5s ease-out;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }
    .scroller{
        padding: 10px;
        border-radius: 6px;
        font-weight:bold;
        position:fixed;
        right:20px;
        bottom:50px;
        cursor: pointer;
    }
</style>
