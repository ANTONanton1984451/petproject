<template>
    <div class="component-wrapper">
        <div class="row d-flex d-lg-none profile-info-head px-2
                    justify-content-md-center justify-content-start"
             :class="[toggleBackGround,toggleTextColor]">
            <span class="avatar-mini mr-2">
                Аватар
            </span>
            <span class="username-top">
                {{userName}}
            </span>
        </div>
        <div class="row profile-nav" :class="[toggleBackGround,toggleTextColor]">
                <profile-nav :host="isHost" :userName="userName"/>
        </div>
        <div class="col-md-10 col-12 mt-3 row justify-content-around">
            <div class="col sort-wrapper " :class="[toggleTextColor]">
                <div class="order-result p-3 mb-3 row radius" :class="[toggleBackGround]">
                    <router-link exact-active-class="btn-active" class="fake-button" :to="{name:'profile.new'}" :class="[toggleBtnColor]">
                        <span>
                          New
                        </span>
                    </router-link>
                    <router-link exact-active-class="btn-active" class="fake-button" :to="{name:'profile.hot'}" :class="[toggleBtnColor]">
                        <span>
                          Hot
                        </span>
                    </router-link>
                    <router-link exact-active-class="btn-active" class="fake-button" :to="{name:'profile.top'}" :class="[toggleBtnColor]">
                        <span>
                          Top
                        </span>
                    </router-link>
                </div>
                <div class="order-result" :class="[toggleBackGround]">
                    <keep-alive>
                        <router-view :type="type" :needleId="userName" :key="$route.path"></router-view>
                    </keep-alive>

                </div>
            </div>
            <div class="col-auto d-none d-lg-flex flex-column" :class="[toggleTextColor]">
                <div class=" p-0 mb-3 profile radius" :class="[toggleBackGround]">
                        <div class="head p-3 d-flex flex-column align-items-start">
                            <span class="avatar">
                                Аватар
                            </span>
                            <span class="username">
                                {{userName}}
                            </span>
                        </div>
                        <div class="main">

                        </div>
                </div>
                <div class="radius" :class="[toggleBackGround]">
                   About us
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {colorToggle} from '../../mixins/colorToggle.js'
    import ProfileNav from "./ProfileNav";
    export default {
        name: "BaseProfileLayout",
        mixins:[colorToggle],
        components:{ProfileNav},
        data(){
            return{
                auth:true,
                type:'',
            }
        },
        computed:{
            userName(){
               return  this.$route.params.username;
            },
            isHost(){
                return this.$route.params.username === this.$store.getters.getNickName
            }
        },
        watch:{
            $route(to){
                this.type = to.params.type;
            }
        }
    }
</script>

<style scoped>
a{
    text-decoration: none;
}
.profile-info-head{
    width : 100%;
    align-items: center;
    min-height: 50px;
}
.order-result{
    align-items: center;
    width: 100%;
}
.profile-link{
 cursor: pointer;
}
.sort-wrapper{
    display: flex;
    flex-direction: column;
    align-items: center;
    padding:0;
}
.username-top,
.username{
    font-weight: bold;
}
.username{
    color:white;
}
 .radius,
 .head{
     border-radius: 5px;
 }
 .fake-button{
     margin-right: 10px;
     width: auto;
     height: auto;
     text-align: center;
     border-radius: 13px;
     padding: 7px;
 }
 .fake-button>span{
     font-weight: bold;
 }
 .profile-nav{
     padding:0;
     margin:0;
     justify-content: center;
     align-items: center;
     border-top: 1px solid #8f8f92;
     border-bottom: 1px solid #8f8f92;
     min-height:40px;
     width: 100%;
 }
 .profile{
     height:300px;
     width:250px;
 }
 .avatar-mini,
 .avatar{
     border-radius:50%;
     border: 1px solid black;
 }
 .avatar{
     width:70px;
     height:70px;
 }
 .avatar-mini{
     width : 45px;
     height: 45px;
 }
 .head{
     height: 45%;
     background-color: #3490dc !important;
 }
</style>
