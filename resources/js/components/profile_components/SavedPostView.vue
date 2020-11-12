<template>
    <div class="row opacity view-post-wrapper">
            <div class="col-sm-7 col-12  view-post p-3"
                :class="toggleBackGround">
                <img @click.stop="noShowMe"
                     @esc="noShowMe"
                     class="cancel" src="\images\close.svg">
                <div class="row w-100 align-items-center">
                    <router-link :to="{name:'create',params:{type:'saved'},query:{postId:post.id}}"
                                 class="btn btn-outline-primary mx-2">
                        Редактировать
                    </router-link>
                    <button @click="deleteSaved" class="btn btn-outline-danger mx-2">
                        Удалить
                    </button>
                </div>
                <div class="content-wrapper mt-3"
                    :class="toggleTextColor">
                    <div class="title">
                        {{post.title}}
                    </div>
                    <div class="post-body">
                        {{post.body}}
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
import {colorToggle} from '../../mixins/colorToggle.js'
export default {
    props:['post','needToShow'],
    name: "SavedPostView",
    mixins:[colorToggle],
    methods:{
        noShowMe(){
            this.$emit('noShowMe');
        },
        deleteSaved(){
            axios.delete(`/api/saved/${this.post.id}`)
            .then(this.handleDeleteResponse)
            .catch(error => {
                console.log(error);
            });
        },
        handleDeleteResponse(responseObj){
            console.log(responseObj.data);
            this.$emit('completeDelete',this.post.id)
            this.noShowMe();
        }
    }

}
</script>

<style scoped>
    .view-post-wrapper{
        background-color: rgba(100, 100, 100, .6);
        justify-content: center;
        width:103vw;
        height: 100vh;
        position:fixed;
        z-index: 100;
        top: 0;
        left: 0;
    }
    .view-post{
        display: flex;
        flex-direction: column;
        align-items: center;
        max-height: 100%;
        overflow-y: scroll;
    }
    .content-wrapper{
        width: 100%;
        display: flex;
        flex-direction: column;
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
    .title{
        font-weight: bold;
        font-size:1.5rem;
    }
    .title,
    .post-body{
        word-break:break-word;
        text-align: left;
    }
    .btn{
        font-weight: bold;
    }
</style>
