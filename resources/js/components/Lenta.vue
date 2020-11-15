<template>
    <div class="component-wrapper" :class="toggleTextColor" @scroll="handleScroll">
<!--        <div class="d-flex flex-row col-10  justify-content-between  my-2" :class="toggle_BG_APP">-->
<!--            <div v-for="n in 4" class="col daily" :class="[toggleTextColor,toggleBackGround]">-->
<!--                трендовые ветки-->
<!--            </div>-->
<!--        </div>-->
<!--       Todo:Может,в будущем добавлю-->
        <div class="col-md-10 col-12 row align-items-center  px-5 mt-1 font-weight-bold" >
            <span>Популярные посты</span>
        </div>
        <div  class="d-flex row col-md-10 col-12  align-items-start  my-2" :class="toggle_BG_APP">
            <div class="col-md  col-12 posts-wrapper ">
                <one-post v-for="(post,key) in posts"
                          :date = "post.created_at"
                          :user = "post.user"
                          :community = "post.community"
                          :comments_count = "post.comments_count"
                          :to_top = "post.to_top"
                          :to_down = "post.to_down"
                          :title = "post.title"
                          :post_id = "post.id"
                          :voted = "post.voted_users"
                />
            </div>
            <div class="col-auto d-none d-lg-flex flex-column">
                <div :class="[toggleBackGround]" class="nav-right col  p-0">
                    <div class="head">
                        Популярные комьюнити
                    </div>
                    <ol class="p-1 ">
                        <li v-for="community  in communities" class="popular row align-items-center px-3">
                            <span class="community-ava mr-3"></span>
                            <router-link
                                         :to="{name:'community',params:{id:community.id}}"
                                         class="community">
                               {{community.name}}
                           </router-link>
                        </li>
                    </ol>
                </div>
                <div :class="[toggleBackGround]" class="nav-right col my-2">
                    Тут будет колонка с инфой о сайте
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    import OnePost from "./Post";
    import {colorToggle} from '../mixins/colorToggle.js'
    export default {
        name: "Lenta",
        mixins: [colorToggle],
        components:{OnePost},
        data(){
            return{
                auth : 'fake',
                posts : [],
                communities : [],
                next_page_posts_url : ''
            }
        },
        beforeMount() {
            axios.get('/api/lenta?page=1')
                .then(this.setLenta)
                .catch(e=>{
                    console.log(e);
                })
        },
        mounted(){
            window.addEventListener('scroll',this.handleScroll);
            document.title = 'Лента';
            axios.get('/api/community/popular')
            .then(this.setCommunities);
        },
        beforeDestroy() {
            window.removeEventListener('scroll',this.handleScroll);
        },
        methods: {
            setLenta(posts){
                this.posts = posts.data.data;
                this.next_page_posts_url = posts.data.next_page_url;
                if(posts.data.next_page_url === null){
                    window.removeEventListener('scroll',this.handleScroll);
                }
            },

            addToLenta(additionalPosts){
                this.posts.push.apply(this.posts,additionalPosts.data.data);

                if(additionalPosts.data.next_page_url !== null){
                    this.next_page_posts_url = additionalPosts.data.next_page_url;
                    window.addEventListener('scroll',this.handleScroll);
                }

            },

            setCommunities(communitiesObj) {
                this.communities = communitiesObj.data;
            },

            handleScroll() {
                let fullHeight = document.getElementById('app').scrollHeight,
                    windowHeight = window.innerHeight,
                    scrollHeight = window.scrollY;

                if((windowHeight+scrollHeight)>fullHeight){
                    window.removeEventListener('scroll',this.handleScroll);
                    axios.get(this.next_page_posts_url)
                    .then(this.addToLenta)
                }

            }
        },


    }
</script>

<style scoped>
li{
    list-style-type: decimal;
}
.popular{
    height:50px;
    margin:0;
    border-bottom: 1px solid rgba(170, 170, 170, 0.18);
}
.fake-button{
    text-align:center;
    padding:8px;
    font-weight: bolder;
    border-radius:5px;
}
.popular:last-child{
    border-bottom: 0;
}

.community{
    font-weight: bolder;
    cursor:pointer
}
.community-ava{
    height: 25px;
    width: 25px;
    border:1px solid green;
    border-radius: 50%;
}


.posts-wrapper{
    display: flex;
    flex-direction: column;
    align-items: center;
}

.nav-right
{
    word-break: break-word;
    border:1px solid #8f8f92;
    border-radius: 5px;
    width:300px;
}
.head{
    height: 70px;
    background-image:url("/images/banner-background.png");
    background-position: 50%;
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
}
    /*@media (max-width : 762px) {*/
    /*    .daily:last-child{*/
    /*        display:none;*/
    /*    }*/
    /*}*/
    /*@media(max-width: 500px){*/
    /*    .daily:first-child{*/
    /*        display:none;*/
    /*    }*/
    /*}*/
</style>
