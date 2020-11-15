<template>
    <div  :class="[toggleBackGround,toggleTextColor]" class="post col-12 my-1" :id="id">
                   <span v-if="voted.length === 0 || voted === undefined " class="left-up-down-bar d-md-flex d-none">
                       <img class="to-up" @click.prevent.once="toTop" src="\images\arrow-up.svg">
                       <span class="views">{{votes}}</span>
                       <img class="to-down" @click.prevent.once="toDown" src="\images\arrow-down.svg">
                   </span>
                   <span v-else-if="voted[0].vote_type === 'TOP'" class="left-up-down-bar d-md-flex d-none">
                       <img class="to-up"  src="\images\arrow-up.svg" style="display:none">
                       <span class="views">{{votes}}</span>
                       <img class="to-down"  src="\images\arrow-down.svg">
                   </span>
                   <span v-else-if="voted[0].vote_type === 'DOWN'" class="left-up-down-bar d-md-flex d-none">
                       <img class="to-up"  src="\images\arrow-up.svg" style="display:none">
                       <span class="views">{{votes}}</span>
                       <img class="to-down" src="\images\arrow-down.svg">
                   </span>
        <router-link :to="{name:'tred',params:{tredId:post_id},query:{sort:'new'}}"
                     :class="[toggleTextColor]"
                     class="main-body col p-2">
            <div class="row align-items-center">
                <span class="community-ava mx-2"></span>
                <router-link
                             class="community mx-2"
                             :to="{name:'community.new',params:{id:community.id}}">
                               {{community.name}}
                           </router-link>
                <router-link tag="span" class="user mx-2" :to="{name:'profile.new',params:{type:'overview',username:user.id}}">
                               Опубликовал {{user.name}} &bull;
                </router-link>
                <span class="time mx-2">
                     {{date|formNotTimestampDate}}
                </span>
            </div>
            <div class="post-body">
                {{title}}
            </div>
            <div class="row comment align-items-center">
                <div v-if="voted.length === 0" class="bottom-up-down-bar align-items-center d-flex d-md-none">
                    <img class="to-up" @click.prevent.once="toTop" src="\images\arrow-up.svg">
                    <span class="views">{{votes}}</span>
                    <img class="to-down" @click.prevent.once="toDown" src="\images\arrow-down.svg">
                </div>
                <div v-else-if="voted[0].vote_type === 'TOP'" class="bottom-up-down-bar align-items-center d-flex d-md-none">
                    <img class="to-up" src="\images\arrow-up.svg">
                    <span class="views">{{votes}}</span>
                </div>
                <div v-else-if="voted[0].vote_type === 'DOWN'" class="bottom-up-down-bar align-items-center d-flex d-md-none">
                    <span class="views">{{votes}}</span>
                    <img class="to-down" src="\images\arrow-down.svg">
                </div>
                <img class="comment-img mx-1" src="\images\comment.svg">
                <span class="comment-count p-1">{{comments_count}} коментарии</span>
                <img class="comment-img mx-2" @click.once.prevent = "ban" title="Скрыть" src="\images\ban.svg">
            </div>
        </router-link>
    </div>
</template>

<script>
    import {colorToggle} from '../mixins/colorToggle.js'
    import {formDate} from "../mixins/formDate";

    export default {
        name: "one-post",
        // props:['date','user','comments_count','to_top','to_down','title','post_id','community','voted'],
        props : {
            date : String,
            user : Object,
            comments_count : Number,
            to_top : Number,
            to_down : Number,
            title : String,
            post_id : Number,
            community : Object,
            voted: {
                type : Array,
                default : function (){
                    return []
                }
            }
        },
        mixins:[colorToggle,formDate],
        data(){
            return{
                top : this.to_top,
                down : this.to_down
            }
        },
        methods : {
          ban(){
             axios.post(`/api/tred/${this.post_id}/ban`)
             .then(this.removeMe)
             .catch(e=>{
                console.log(e);
             });
          },
          removeMe() {
              console.log('Удаляюсь',this.post_id);
              document.getElementById('post-'+this.post_id).remove();
              this.$destroy();
          },
          doToTop(response) {
            let selector = '#post-'+this.post_id + ' .to-down';
            let downArrows = document.querySelectorAll(selector);
            this.top +=1;
            downArrows.forEach(function (value){
               value.style.display = 'none'
            });
          },
          doToDown(){
              let selector = '#post-'+this.post_id + ' .to-up';
              let upArrows = document.querySelectorAll(selector);
              this.down +=1;
              upArrows.forEach(function (value){
                  value.style.display = 'none'
              });
          },
          toTop(){
             axios.patch(`/api/post/${this.post_id}/up`)
             .then(this.doToTop)
             .catch(error => {
                 console.log(error);
             })
          },
          toDown(){
              axios.patch(`/api/post/${this.post_id}/down`)
              .then(this.doToDown)
              .catch(error => {
                  console.log(error);
              });
          }
        },
        computed : {
            id(){
                return 'post-' + this.post_id;
            },
            votes(){
                return this.top - this.down;
            },
        },
        mounted(){
        }
    }
</script>

<style scoped>
    .post-body{
        text-align: left;
        font-size: 1.5rem;
        font-weight: bold;
        word-break: break-all;
    }

    .post{
        display: flex;
        flex-direction: row;
        align-items:center;
        border-radius:5px;
        border:1px solid transparent
    }
    .bottom-up-down-bar{
        width:100px;
        flex-direction: row;
    }
    .post:hover{
        box-shadow:0 0 0 1px #8f8f92;
    }
    .left-up-down-bar{
        height:100%;
        width:40px;
        word-break: break-all;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .to-up,
    .to-down{
        cursor:pointer;
        height:20px;
        width: 20px;
    }
    .to-down{
        transform: rotate(180deg);
    }
    .main-body{
        display: flex;
        flex-direction: column;
        text-decoration: none;
    }
    .main-body:hover{
        text-decoration: none;
    }
    .community-ava{
        height: 25px;
        width: 25px;
        border:1px solid green;
        border-radius: 50%;
    }
    .community{
        font-weight: bolder;
    }
    .user:hover,
    .community:hover{
        text-decoration:underline;
        cursor:pointer;
    }
    .comment-count,
    .user,
    .time{
        color:#8f8f92;
    }
    .comment-count{
        border-radius:3px;
    }
    .comment-count{
        font-weight:bold;
    }
    .comment-count,
    .user,
    .time{
        color:#8f8f92;
    }
    .comment-count,
    .user{
        font-weight: lighter;
    }
    .user:hover,
    .community:hover{
        text-decoration:underline;
        cursor:pointer;
    }
    .comment-img{
        width: 18px;
        height: 18px;
    }
</style>
