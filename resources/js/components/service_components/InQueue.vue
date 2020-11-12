<template>
    <div class="component-wrapper login-form"
         :class="[!backGroundAppIsBlack ? 'opacity' : '']">
        <div class="form"
             :class="[!backGroundAppIsBlack ? 'shadow' : '']">
            <img @click="noShowMe" class="cancel" src="\images\close.svg">
            <div class="row align-items-center justify-content-center w-100">
                <strong class="queue-title">
                    Посты в обработке
                </strong>
                <div v-if="showSpinner" class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <button v-else @click="updateQueueInfo" class="offset-1 col-auto btn btn-outline-secondary font-weight-bold">
                    Обновить
                </button>
            </div>
            <div v-if="queue.posts !== []" class="w-100 posts-wrapper d-flex flex-column justify-content-start">
                <div v-for="post in queue.posts" class="post p-2">
                    <span class="title">
                        {{post.title|cutTitle}}
                    </span>
                    <span class="date">
                        Дата публикации :  {{post.date|timestampToDate}}
                    </span>
                    <span :inner-html.prop="post.status|toUserUnderstandStatus"></span>
                </div>
            </div>
            <div v-else>
                <span class="nothing">
                    Все ваши посты обработанны и опубликованы
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import {colorToggle} from "../../mixins/colorToggle";

export default {
    name: "InQueue",
    mixins: [colorToggle],
    data(){
      return {
            queue : {
                posts : []
            },
            showSpinner:false
      }
    },
    mounted() {
        axios.get('/api/in_queue')
        .then(this.handleGetQueueResponse)
        .catch(error=>{
           console.log(error);
        });
    },
    methods:{
        noShowMe(){
            this.$emit('noShowMe')
        },
        handleGetQueueResponse(queueObj) {
            this.queue.posts = queueObj.data;
        },
        updateQueueInfo() {
            this.showSpinner = true;
            axios.get('/api/in_queue')
            .then(this.handleUpdateQueueInfoResponse)
            .catch(error => {
                console.log(error);
            })
        },
        handleUpdateQueueInfoResponse(queueObj) {
            this.queue.posts = queueObj.data;
            this.showSpinner = false;
        }
    },
    computed:{
        backGroundAppIsBlack(){
            return this.$store.getters.getAPPStatus;
        }
    },
    filters : {
        cutTitle(value){
            if(value.length > 15){
                return value.substr(0,15) + '...';
            }
            return  value;

        },
        timestampToDate(value){
            return value;
        },
        toUserUnderstandStatus(statusFromServer){
            let userUnderstandStatus = ''
            switch (statusFromServer){
                case 'waiting-server':
                    userUnderstandStatus = 'Статус : <strong style="color:#856404;">Ожидает обработки сервером</strong>';
                    break;
                case 'waiting-admin':
                    userUnderstandStatus = 'Статус : <strong style="color:#856404;">Ожидает ответа администратора</strong>';
                    break;
                case 'published':
                    userUnderstandStatus = 'Статус : <strong style="color:green">Опубликовано</strong>';
                    break;
                case 'server-error':
                    userUnderstandStatus = 'Статус : <strong style="color:red">Ошибка при проверке на сервере</strong>';
                    break;
                case 'admin-not-allowed':
                    userUnderstandStatus = 'Статус : <strong style="color:red">Администратор отклонил пост</strong>';
                    break;
                default :
                    userUnderstandStatus = 'Статус : <strong>Неизвестно</strong>';
            }
            return userUnderstandStatus;
        }
    }
}
</script>

<style scoped>
    .post{
        max-height: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        border-top : 1px solid black;
    }
    .posts-wrapper{
        max-height: 70%;
        overflow-y: scroll;
    }
    .posts-wrapper:last-child{
        border-bottom: 1px solid black;
        overflow-y: scroll;
    }
    .queue-title {
        font-size: 1.6rem;
    }

    .title {
        font-weight: bold;
        font-size: 1.3rem;
    }
    .form{
        color:black;
        position: relative;
    }
    .login-form{
        z-index:15;
        /*ToDo::Пофиксить ширину(Ширина этого блока не на весь экран!)*/
        width:100vw;
        height:100vh;
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
    @media (max-width: 768px) {
        .form{
            width:99%;
        }
    }
</style>
