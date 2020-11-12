<template>
    <div class="comment my-2 row" :id="commentId">
        <div class="up-down d-none d-md-block  h-100 col-1">
            <img class="to-top" @click.once="toTop" src="\images\arrow-up.svg">
            <span class="views">{{views_count_computed}}</span>
            <img class="to-down" @click.once="toDown" src="\images\arrow-down.svg">
        </div>
        <div class="d-flex flex-column align-items-start col-11">
            <div class="posted-by ml-2 row justify-content-start">
                <router-link
                    tag="span"
                    :to="{name:'profile.new',params:{username:user.id,type:'overview'}}"
                    class="user">Опубликовано {{user.name}} &bull;</router-link>   {{date|formNotTimestampDate}}
                <div class="bottom-up-down-bar d-flex d-md-none">
                    <img class="to-top" @click.once="toTop" src="\images\arrow-up.svg">
                    <span class="views">{{views_count_computed}}</span>
                    <img class="to-down" @click.once="toDown" src="\images\arrow-down.svg">
                </div>
            </div>
            <div class="comment-text ml-2">
                {{text}}
            </div>
        </div>
    </div>
</template>

<script>
import {formDate} from "../mixins/formDate"
export default {
    props:['view_count','user','date','text','id'],
    name: "Comment",
    mixins : [formDate],
    data(){
        return {

        }
    },
    methods : {
        doToTop(){
          let selector = '#'+this.commentId+' .to-down';
          this.view_count +=1;
          document.querySelectorAll(selector).forEach(value => {
              value.style.display = 'none';
          });
        },
        doToDown(){
            let selector = '#'+this.commentId+' .to-top';
            this.view_count -=1;
            document.querySelectorAll(selector).forEach(value => {
                value.style.display = 'none';
            });
        },
        toTop(){
            axios.patch(`/api/comment/${this.id}/up`)
            .then(this.doToTop)
            .catch(error => {
                console.log(error);
            })
        },
        toDown(){
            axios.patch(`/api/comment/${this.id}/down`)
            .then(this.doToDown)
            .catch(error => {
                console.log(error);
            })
        }
    },
    computed : {
        commentId(){
            return 'comment'+this.id;
        },
        views_count_computed(){
            return this.view_count;
        }
    }
}
</script>

<style scoped>
.to-top,
.to-down{
    cursor:pointer;
    height:20px;
    width: 20px;
}
.to-down{
    transform: rotate(180deg);
}
.posted-by{
    color:#8f8f92;
    font-weight: lighter;
}
.user{
    cursor:pointer;
    margin-right: 15px;
}
.user:hover{
    text-decoration: underline;
}
.comment-text{
    word-break: break-all;
    text-align:left;
}
.bottom-up-down-bar {
    justify-content: center;
}
</style>
