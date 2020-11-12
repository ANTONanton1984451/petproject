<template>
    <div class="component-wrapper" :class="toggleTextColor">
       <div class="community-logo d-md-block d-none">
        Лого комунити
       </div>
       <div class="community-title row justify-content-center align-items-center"
            :class="toggleBackGround">
           <span class="community-ava mr-3 border-green">
               Ava
           </span>
           <span class="mr-3 font-weight-bold d-sm-block d-none">
               Название сообщества
           </span>
           <button class="btn btn-outline-primary font-weight-bold">
               Вступить
           </button>
       </div>
       <div class="row col-11 col-md-10 my-3 align-items-start p-md-2 p-0">
           <div class="col d-flex flex-column align-items-center p-md-2 p-0">
               <div class="col p-3 row justify-content-sm-start justify-content-center align-items-center  mb-3" :class="[toggleBackGround]">
                   <router-link active-class="active" class="fake-button" :to="{name:'community.new'}" :class="[toggleBtnColor]">
                           <strong>
                               New
                           </strong>
                   </router-link>
                   <router-link active-class="active" class="fake-button"  :to="{name:'community.hot'}" :class="[toggleBtnColor]">
                            <strong>
                                Hot
                            </strong>
                   </router-link>
                   <router-link active-class="active" class="fake-button" :to="{name:'community.top'}" :class="[toggleBtnColor]">
                            <strong>
                                Top
                            </strong>
                   </router-link>
               </div>
               <div class="col-12" :class="[toggleBackGround]">
                   <keep-alive>
                       <component :is="currentSortComponent" :needleId="777" :type="'community'" />
                   </keep-alive>
               </div>
           </div>
           <div class="d-none d-lg-flex right-column p-2">
               <div class="col-12 community mb-5"
                    :class="[toggleTextColor,toggleBackGround]">
                   <div class="community-top ">
                       О сообществе
                   </div>
                   <div class="description mb-2 col-11">
                       Описание
                   </div>
                   <div class="members-count col-11 py-3">
                       Количество  Участники
                   </div>
                   <div class="created col-11 pb-2 mb-1">
                       Создано : Число
                   </div>
               </div>
               <div class="col-12 community  mb-5"
                    :class="[toggleTextColor,toggleBackGround]">
                   <div class="community-top ">
                       Администраторы
                   </div>
                    <span v-for="n in 5" class="row admin px-3">
                        <span class="ava mr-3">
                            Ava
                        </span>
                        <router-link tag="span"
                                     :to="{name:'profile.new',params:{type:'overview',username:n}}"
                                     class="fake-link">
                            Админ
                        </router-link>
                    </span>
               </div>
               <div class="col-12 community d-flex"
                    :class="[toggleTextColor,toggleBackGround]">
                   <div class="community-top ">
                       Правила поведения сообщества
                   </div>
                   <div class="rules p-1">
                       Правила sfdfsdf
                        sdf
                       s
                       fsd
                       f
                       sfd
                       sf
                       s
                       f            sd
                       fs
                       df
                       sdf

                   </div>
               </div>
           </div>
       </div>
    </div>
</template>

<script>
    import {colorToggle} from '../mixins/colorToggle.js'
    import New from "./get_components/New";
    import Hot from "./get_components/Hot";
    import Top from "./get_components/Top";
    export default {
        name: "Community",
        mixins:[colorToggle],
        data(){
           return{
               id:'',
               currentSortComponent : New
           }
        },
        watch:{
            $route(to){
                this.user = to.params.username
                switch (to.name) {
                    case 'community.new':
                        this.currentSortComponent = New;
                        break;
                    case 'community.hot':
                        this.currentSortComponent = Hot;
                        break;
                    case 'community.top':
                        this.currentSortComponent = Top;
                        break;
                }

            }

        }
    }
</script>

<style scoped>
.admin{
    align-items: center;
    width: 100%;
    border-bottom: 1px solid rgba(131, 131, 131, 0.38);
    height: 40px;
}
.admin:last-child{
    border:0;
}
.ava{
    width:35px;
    height:35px;
    border-radius:50%;
    border:1px solid green;
}
.right-column{
    flex-direction: column;
    width:300px;
}
.community-title{
    padding : 0;
    height: 70px;
    width:100%;
}
.community-logo{
    height:65px;
    padding: 0;
    width:100%;
    background-color: #1d68a7;
}
.rules{
    font-weight: bold;
    word-break: break-word;
    width: 100%;
    text-align: left;
}
.community{
    display: flex;
    flex-direction: column;
    font-weight: bold;
    align-items: center;
    padding: 0;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px
}
.community-ava{
    height:65px;
    width:65px;
    border-radius:50%;
    border:1px solid green;
}
.community-top{
    height:50px;
    width:100%;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    background-color: #1d68a7;
}
.members-count{
    border-bottom: 1px solid rgba(131, 131, 131, 0.38);
    text-align: left;
}
.description{
    text-align: left;
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
@media (max-width : 762px) {
    .community-title{
        border-top:1px solid #8f8f92;
    }
}
</style>
