<template>
    <div>
        <transition name="spinner" mode="out-in">
            <div v-if="showSpinner" class="spinner-grow text-primary" style="width: 80px;height: 80px" :class="themeStatus" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div v-else>
                <div v-if="posts.length === 0">
                    <strong>
                        Ничего не найдено
                    </strong>
                </div>
                <post v-else
                      v-for="post in posts"
                      :date="post.date"
                      :comments_count="post.comments_count"
                      :post_id="post.post_id"
                      :title="post.title"
                      :user="post.user"
                      :view_count="post.view_count"
                />
            </div>
        </transition>

    </div>
</template>

<script>
import Post from "../Post";
export default {
    name: "Hot",
    components : {Post},
    props:['type','needleId'],
    data(){
        return{
            posts : [],
            showSpinner:true
        }
    },
    methods:{
        unsetSpinner(){
            this.showSpinner = false
        },
        handleGetNewResponse(responseObj) {
            this.offset += responseObj.length;
            this.posts.push.apply(this.posts,responseObj.data);
            this.showSpinner = false;
            window.addEventListener('scroll',this.getPosts);
        },
        getPosts() {
            let fullHeight = document.getElementById('app').scrollHeight,
                windowHeight = window.innerHeight,
                scrollHeight = window.scrollY;

            if((windowHeight+scrollHeight)>fullHeight){
                window.removeEventListener('scroll',this.getPosts)
                axios.get(`/api/${this.type}/top?offset=${this.offset}`)
                    .then(this.handleGetNewResponse)
                    .catch(error => {
                        console.log(error);
                    })
            }
        }
    },
    computed:{
        themeStatus(){
            return  this.$store.getters.getTextStatus ? 'text-primary' : 'text-light';
        }
    },
    deactivated() {
        console.log('DestroyTop');
        window.removeEventListener('scroll',this.getPosts);
    },
    activated() {
        window.addEventListener('scroll',this.getPosts);
    },
    mounted(){
        axios.get(`/api/${this.type}/top`)
        .then(this.handleGetNewResponse)
        .catch(error => {
            console.log(error);
        });
    }

}
</script>

<style scoped>
.spinner-enter-active,.spinner-leave-active {
    transition: opacity 0.5s ease-out;
}
.spinner-enter, .spinner-leave-to {
    opacity: 0;
}
</style>
