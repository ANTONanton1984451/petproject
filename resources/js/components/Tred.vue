<template>
    <div class="component-wrapper pt-3">
        <div class="row wrapper col-md-10 col-12">
            <div class="column-wrapper col  mr-lg-4" :class="toggleTextColor">
                <div class="column-wrapper px-1 w-100 py-3" :class="toggleBackGround">
                    <div class="row col-12 px-0">
                        <div class="up-down d-none d-md-block h-100 col-1">
                            <img class="to-top" @click.once="toTop" src="\images\arrow-up.svg">
                            <span class="views">{{post.view_count}}</span>
                            <img class="to-down" @click.once="toDown" src="\images\arrow-down.svg">
                        </div>
                        <div class="post-body col">
                            <div class="posted-by">
                                Опубликовано <span class="user">{{post.user.name}}</span> {{post.created_at|formNotTimestampDate}}
                            </div>
                            <div class="title">
                                {{post.title}}
                            </div>
                            <div class="main-body">
                                {{post.body}}
                            </div>
                            <div class="service-info row align-items-center p-2">
                                <div class="bottom-up-down-bar d-flex d-md-none">
                                    <img class="to-top" @click.once="toTop" src="\images\arrow-up.svg">
                                    <span class="views">{{post.view_count}}</span>
                                    <img class="to-down" @click.once="toDown" src="\images\arrow-down.svg">
                                </div>
                                <img class="comment-img mx-1" src="\images\comment.svg">
                                <span class="comment-count p-1">{{post.comments_count}} comments</span>
                                <img class="comment-img mx-2 ban" @click="banTred" title="Скрыть" src="\images\ban.svg">
                            </div>
                        </div>
                    </div>
                    <div  v-show="showCommentInput" class="add-comment-wrapper  offset-md-1 col-md-10 col-11 align-self-md-start align-self-center">
                        <textarea id="comment-add" class="add-comment col-12"
                                  v-model.trim.lazy="comment"
                                  :class="[toggleBackGround,toggleTextColor]"></textarea>

                        <button class="btn btn-outline-success col-12 my-1 font-weight-bold"
                                type="button"
                                @click="placeComment">
                            Оставить коментарий
                        </button>
                    </div>

                    <div class="comments-block col-12">
                        <div class="sort p-2 row align-items-center">
                        <span class="font-weight-bolder">
                            Сортировать :
                        </span>
                            <router-link exact-active-class="active"
                                         tag="div"
                                         :to="{name:'tred',query:{sort:'top'}}"
                                         class="mx-2 p-1 fake-link font-weight-bolder"
                                         id="top">
                                TOP
                            </router-link>
                            <router-link exact-active-class="active"
                                         tag="div"
                                         :to="{name:'tred',query:{sort:'new'}}"
                                         class="mx-2 p-1 fake-link font-weight-bolder"
                                         id="new">
                                NEW
                            </router-link>
                            <router-link exact-active-class="active"
                                         tag="div"
                                         :to="{name:'tred',query:{sort:'old'}}"
                                         class="mx-2 p-1 fake-link font-weight-bolder"
                                         id="old">
                                OLD
                            </router-link>
                        </div>
                        <span class="hide-comments p-1"
                              @click="getPosts"
                              :class="[showFullComments ? 'd-block' :'d-none',toggleBtnColor ]">
                        Скрыть
                        </span>
                        <div class="comments" :class="[showFullComments ? 'show-full' :'hide-full' ]">

                            <comment v-for="value in comments"
                                    :view_count="value.view_count"
                                    :user="value.user"
                                    :date="value.date"
                                    :text="value.text"
                                    :id="value.id"/>
                        </div>
                        <button class="btn btn-primary font-weight-bold col-12"
                                @click="getComments"
                                :class="[showFullComments ? 'd-none' :'d-block',toggleShadow ]" >
                            Показать ещё коментарии
                        </button>
                    </div>
                </div>

                <div class="my-2 font-weight-bold align-self-start">
                    Другие посты этого сообщества
                </div>
                <div class="post-wrapper">
                  <one-post v-for="(post,key) in other_posts"
                            :date = "post.created_at"
                            :user = "post.user"
                            :community="community"
                            :comments_count = "post.comments_count"
                            :view_count = "post.view_count"
                            :post_id = "post.id"
                            :title = "post.title"/>
                </div>
            </div>
            <div class="d-none d-lg-flex right-column">
                <div class="col-12 community mb-2"
                     :class="[toggleTextColor,toggleBackGround]">
                    <div class="community-top"></div>
                    <div class="row  align-items-center col-11 my-2">
                        <span class="ava mr-2">
                            Ava
                        </span>
                        <router-link :to="{name:'community.new',params:{id:community.id}}" class="community-name">
                            {{community.name}}
                        </router-link>
                    </div>
                    <div class="description mb-2 col-11">
                        Описание
                    </div>
                    <div class="members-count col-11 py-3">
                        <span>
                            Количество
                        </span>
                        <span>
                            Участники
                        </span>
                    </div>
                    <div class="created col-11 pb-2 mb-1">
                        Создано : Число
                    </div>
                    <button @click="communityJoin" disabled id="join" class="btn btn-outline-primary col-11 font-weight-bold mb-4">
                        <span v-if="!isAuth">
                            Необходимо войти
                        </span>
                        <span v-else-if="isJoined">
                            Вы уже вступили
                        </span>
                        <span v-else>
                            Вступить
                        </span>

                    </button>
                </div>
                <div class="about-us border"
                     :class="[toggleTextColor,toggleBackGround]">
                    О нас
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {colorToggle} from '../mixins/colorToggle.js'
    import {formDate} from "../mixins/formDate";
    import OnePost from "./Post";
    import Comment from "./Comment";
    export default {
        name: "Tred",
        mixins:[colorToggle,formDate],
        components:{OnePost,Comment},
        data(){
            return{
                Id:this.$route.params.tredId,
                showFullComments:false,
                showCommentInput :false,
                isJoined : false,
                comment:'',
                post : {
                    body : '',
                    title : '',
                    view_count : 0,
                    comments_count : 0,
                    user : {},
                    created_at : 0,
                },
                community : {
                    name : '',
                    id : ''
                },
                comments : [],
                other_posts : [],
                next_page_comments_url : '',
                next_page_posts_url:'',
            }
        },
        methods:{
            getPosts(){
                this.showFullComments = false;
                this.getPostsByScroll();
            },
            getComments(){
                this.showFullComments = true;
                this.getCommentsByScroll();
            },
            placeComment(){

              if(this.comment.length > 0 && this.comment.length <= 150 ){
                  let body = JSON.stringify({text:this.comment});
                  axios.post(`/api/tred/${this.Id}/comment`,body,{
                      headers: {
                          'Content-Type': 'application/json',
                      }
                  }).then(this.addNewComment)
                      .catch((error)=>{
                      console.log(error);
                  });

              }else{
                  alert("Коментарий не должен быть пустым");
              }
            },

            getCommentsByScroll(){

              window.removeEventListener('scroll',this.getNextPosts);
              if(this.next_page_comments_url !==null){
                  window.addEventListener('scroll',this.getNextComments);
              }
            },

            getNextComments(){
                let comments_next_page = this.next_page_comments_url;

                if(this.isBottom() && comments_next_page !== null){
                    window.removeEventListener('scroll',this.getNextComments);

                    axios.get(comments_next_page)
                        .then(this.handleGetNextComments);
                }

            },

            handleGetNextComments(commentsResponse){
                this.comments.push.apply(this.comments,commentsResponse.data.data);
                this.next_page_comments_url = commentsResponse.data.next_page_url;
                window.addEventListener('scroll',this.getNextComments);
            },

            getPostsByScroll(){
                window.removeEventListener('scroll',this.getNextComments);
                if(this.next_page_posts_url !== null){
                    window.addEventListener('scroll',this.getNextPosts);
                }
            },

            getNextPosts(){
                let posts_next_page = this.next_page_posts_url;

                console.log(this.next_page_posts_url);

                if(this.isBottom() && posts_next_page !== null) {
                    window.removeEventListener('scroll', this.getNextPosts)
                    axios.get(posts_next_page)
                    .then(this.handleGetNextPosts);
                }
            },
            handleGetNextPosts(postsResponse){
               this.other_posts.push.apply(this.other_posts,postsResponse.data.data);
               this.next_page_posts_url = postsResponse.data.next_page_url;

               if(postsResponse.data.next_page_url !== null){
                   window.addEventListener('scroll',this.getNextPosts);
               }
            },
            setGeneral(generalDataObj){

                this.post = generalDataObj.data.main_post;
                this.other_posts = generalDataObj.data.other_posts;
                this.next_page_posts_url = generalDataObj.data.next_page_posts;

                this.showCommentInput = !generalDataObj.data.has_comment;
                this.community = generalDataObj.data.main_post.community;
                if(!(this.isJoined = generalDataObj.data.is_joined) || !this.$store.getters.isAuth){
                    document.getElementById('join').disabled = false;
                }
            },

            addNewComment(addCommentResponse){
              let moment = require('moment'),
              newComment = {
                  view_count: 0,
                  user : addCommentResponse.data.user,
                  date : addCommentResponse.data.created_at,
                  text: addCommentResponse.data.text
              }
              this.comments.unshift(newComment);
              this.post.comments_count +=1;
              this.showCommentInput = false;
            },

            join(response){
                document.getElementById('join').disabled = true;
                this.isJoined = true;
            },

            communityJoin(){
                axios.post(`/api/community/${this.community.id}/join`)
                     .then(this.join)
                     .catch(e=>{
                         console.log(e);
                     });
            },

            handleSortResponse(response){
                this.next_page_comments_url = response.data.next_page_url;
                this.comments.length = 0;
                this.comments = response.data.data;
            },

            sortByQuery(route){
                let sort = route.query.sort === undefined ? 'top' : route.query.sort;
                axios.get(`/api/tred/${this.Id}/comments?sort=`+ sort)
                .then(this.handleSortResponse)
                .catch(error => {
                    console.log(error);
                });
            },

            doToTop() {
                let selector = 'div.row.col-12.px-0 .to-down',
                    downArrows = document.querySelectorAll(selector);
                this.post.view_count +=1;
                downArrows.forEach(function (value){
                    value.style.display = 'none'
                });
            },

            doToDown(){
                let selector = 'div.row.col-12.px-0 .to-top',
                    upArrows = document.querySelectorAll(selector);
                console.log(upArrows);
                this.post.view_count -=1;
                upArrows.forEach(function (value){
                    value.style.display = 'none'
                });
            },

            goToLenta(){
              this.$router.push('/lenta');
            },

            banTred(){
              axios.post(`/api/tred/${this.Id}/ban`)
              .then(this.goToLenta)
              .catch(error => {
                  console.log(error);
              });
            },

            toTop(){
                axios.post(`/api/post/${this.Id}/up`)
                .then(this.doToTop)
                .catch(error => {
                    console.log(error);
                })
            },

            toDown(){
                axios.post(`/api/post/${this.Id}/up`)
                .then(this.doToDown)
                .catch(error => {
                    console.log(error);
                })
            },
            isBottom(){
                let fullHeight = document.getElementById('app').scrollHeight,
                    windowHeight = window.innerHeight,
                    scrollHeight = window.scrollY;
                return (windowHeight+scrollHeight)>fullHeight;
            }
        },
        computed:{
            toggleShadow(){
                if(this.$store.getters.getBackGroundStatus){
                    return 'show-comments-black';
                }else{
                    return 'show-comments-white';
                }
            },
            isAuth(){
                return this.$store.getters.isAuth;
            }
        },
        watch:{
            $route(to){
                this.sortByQuery(to);
                //todo переход к другим постам
            }
        },
        beforeMount() {
            let tredId = this.$route.params.tredId,
                getNextPostsFunc = this.getNextPosts;
            axios.get(`/api/tred/${tredId}/general`)
            .then(this.setGeneral)
            .then(function (){
                window.addEventListener('scroll',getNextPostsFunc);
            })
            .catch((error)=>{
                console.log(error.response)
            });
            this.sortByQuery(this.$route);

        },
    }
</script>

<style scoped>
.wrapper{
    justify-content: center;
    align-items:flex-start;
}
.column-wrapper{
    display: flex;
    flex-direction: column;
    align-items: center;
}
.post-body{
   display: flex;
   flex-direction: column;
   align-items: flex-start;
}
.post-wrapper{
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.show-comments-black{
    box-shadow: 0 -15px 8px 15px  rgba(26, 26, 27, 0.8);
}
.show-comments-white{
    box-shadow: 0 -15px 8px 15px  rgba(255, 255, 255, 0.8);
}
.right-column{
    flex-direction: column;
    width:300px;
}
.community{
    display: flex;
    flex-direction: column;
    font-weight: bold;
    align-items: center;
    padding: 0;
    border-radius: 5px;
}
.ban{
    cursor: pointer;
}
.hide-comments{
    position:fixed;
    z-index: 5;
    right:20px;
    top:50%;
    border-radius: 5px;
    font-weight: bold;
    cursor:pointer;
}
.title{
    text-align:left;
    font-size:1.5rem;
    font-weight: bolder;
    word-break: break-all;
}
.hide-full{
    max-height: 200px;
    overflow: hidden;
}
.show-full{
    height: auto;
}
.sort>div{
    cursor: pointer;
    border-radius: 3px;
}
.add-comment{
    word-break: break-all;
    text-align:left;
}
.add-comment{
    min-height:70px;
    border-radius:4px;
    border:1px solid transparent;
    box-shadow:0 0 0 1px #8f8f92;
}

.community-top{
    height:50px;
    width:100%;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    background-color: #1d68a7;
}
.description{
    text-align: left;
}
.to-top,
.to-down{
    cursor:pointer;
    height:15px;
    width: 15px;
}
.to-down{
    transform: rotate(180deg);
}
.upvoted,
.comment-count {
    color:#8f8f92;
    font-weight: lighter;
}

.ava{
    width:50px;
    height:50px;
    border-radius:50%;
    border:1px solid green;
}
.comment-img{
    width: 18px;
    height: 18px;
}
.members-count{
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.community-name{
    word-break: break-all;
}
.sort,
.members-count{
    border-bottom: 1px solid rgba(131, 131, 131, 0.38);
}
.created{
    text-align:left;
}
</style>
<style>
.bottom-up-down-bar {
    width: 100px;
    flex-direction: row;
    align-items: center;
}
</style>
