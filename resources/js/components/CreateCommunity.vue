<template>
    <div class="component-wrapper">
        <div class="col-12 col-md-7 col-sm-9 d-flex flex-column align-items-center"
             :class="[toggleBackGround,toggleTextColor]">

            <div class="inputs-wrapper col-12">
                <input v-model.trim.lazy="communityName"
                       type="text"
                       class="p-2 mb-2"
                       :class="[toggleBackGround,toggleTextColor]"
                       placeholder="Имя сообщества">

                <textarea v-model.trim.lazy="communityDescription"
                          type="text"
                          class="p-2 mb-2"
                          :class="[toggleBackGround,toggleTextColor]"
                          placeholder="Описание сообщества"/>
                <textarea v-model.trim.lazy="communityRules"
                          type="text"
                          class="p-2 mb-2"
                          :class="[toggleBackGround,toggleTextColor]"
                          placeholder="Правила сообщества"/>

                <button @click="registerCommunity" class="btn btn-outline-success font-weight-bold mb-2">
                    Создать
                </button>
            </div>
        </div>
        <div id="notification" class="notification" style="display: none">
            <strong>
                {{notificationMessage}}
            </strong>
        </div>
        <div id="loading" style="display: none" class="w-100 opacity">
            <div v-if="isShowLoadingScreen" class="spinner-grow text-primary" style="width: 80px;height: 80px"  role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center" v-else>
                <span>
                    Ваше сообщество создано , поздравляем!
                </span>
                <router-link :to="{name:'community.new',params:{id:this.communityId}}">
                    <strong>
                        Перейти к сообществу
                    </strong>
                </router-link>
                <router-link :to="{name:'create',params:{type:'new'},query:{community:this.communityName,communityId:communityId}}">
                    <strong>
                        Написать пост в сообществе
                    </strong>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
//комьюнити будет просто создаваться,без какой-либо админки над ней
import {colorToggle} from "../mixins/colorToggle";

export default {
    name: "CreateCommunity",
    mixins:[colorToggle],
    data(){
        return {
            showLoadingScreen : true,
            communityId :' ',

            communityName : '',
            communityDescription : '',
            communityRules : '',

            notificationMessage : ''
        }
    },
    methods: {
        registerCommunity(){
            let isFieldsNotEmpty = (this.communityName !== '') &&
                                   (this.communityRules !== '') &&
                                   (this.communityDescription !== '');

            if(isFieldsNotEmpty){
                let config = {
                    headers:{
                        'Content-type':'application/json'
                    }
                },
                    data = {
                        'name' : this.communityName,
                        'rules' : this.communityRules,
                        'description' : this.communityDescription
                    }

                document.getElementById('loading').style.display='flex'
                axios.post('/api/community',data,config)
                .then(this.handleRegisterResponse)
                .catch(error => {
                   console.log(error);
                });

            }else{
                this.notify('Не все поля заполнены',2500)
            }
        },

        handleRegisterResponse(response) {
            this.communityId = response.data.id;
            this.toggleShowLoadingScreen();
        },

        toggleShowLoadingScreen() {
            this.showLoadingScreen = !this.showLoadingScreen
        },

        notify(message,timeOut){
            let modalNotification = document.getElementById('notification');
            this.notificationMessage = message;
            modalNotification.style.display = 'block';
            setTimeout(function(){
                modalNotification.style.display = 'none';
            },timeOut);
        }
    },

    computed:{
        isShowLoadingScreen(){
            return this.showLoadingScreen
        }
    }
}
</script>

<style scoped>
#loading{
    position: fixed;
    width:100vw;
    height: 100vh;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.inputs-wrapper{
    display: flex;
    flex-direction: column;
}

</style>
