<template>
    <div class="component-wrapper">
        <div class="row col-11 col-md-10 bg-white py-3">
            <button @click="publicPost" class="btn btn-outline-success mx-2">
                Опубликовать
            </button>
            <button @click="savePost" class="btn btn-outline-secondary mx-2">
                Сохранить
            </button>
            <button v-if="isSaved" @click="deletePost" class="btn btn-outline-danger mx-2">
                Удалить
            </button>
            <div class="sendCommunity col-3">
                Сообщество : {{communityForSend}}
            </div>
            <div class="wrapper">
                <div class="w-100 position-relative" v-click-outside="hideSearchResults">
                    <input type="text"
                           @input="searchCommunity"
                           :bind = 'communityForSend'
                           @focus="showSearchResults = true"
                           class="p-1"
                           placeholder="Поиск комьюнити">
                    <div  v-if="showSearchResults" class="search-results">
                        <span v-for="results in searchTarget"
                              class="search-values p-1"
                              @click="communityForSendId = results.id;showSearchResults = false;communityForSend = results.name"
                              >
                            {{results.name}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <textarea v-model.trim.lazy="title" contenteditable="true" placeholder="Заголовок поста" class="title col-11 col-md-10 border">

        </textarea>
        <vue-editor class="col-11 col-md-10 text-editor"
                    v-model.trim.lazy="body"
                    :placeholder="'Пора писать посты!'"
                    :editorToolbar="customToolBar"/>
        <div id="loadScreen" class="loadScreen" style="display: none">
            <div class="spinner-grow text-primary" style="width: 150px;height: 150px"  role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <transition name="modal-notification">
            <div id="notification" class="notification" style="display:none">
                <strong>
                   {{notificationMessage}}
                </strong>
            </div>
        </transition>
    </div>
</template>

<script>
import { VueEditor } from 'vue2-editor'
import {colorToggle} from "../mixins/colorToggle";
import vClickOutside from 'v-click-outside'


export default {
    name: "Editing",
    mixins:[colorToggle],
    props:['fromWhere'],
    components:{ VueEditor},
    directives: {
        clickOutside: vClickOutside.directive
    },
    data(){
          return{
              showSearchResults:false,
              body:'',
              title:'',
              notificationMessage:'',
              searchTarget:[],
              communityForSend:'',
              communityForSendId:0,
              customToolBar:[
                    ['bold','italic','underline','strike'],
                    [{'align':''},{'align':'center'},{'align':'right'}],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link','image']
              ],
          }
    },
    methods:{
      savePost(){
        if(this.title !== '' && this.body !== ''){
            let body = JSON.stringify({body:this.body,
                                            title:this.title});
            axios.patch('/api/post/save',body,{
                headers : {
                    'Content-Type': 'application/json',
                }
            }).then(this.handleSaveResponse)
        }
      },
      handleSaveResponse(saveResponseObj){
          this.notificationMessage = 'Сохранено!';
          let modalNotification = document.getElementById('notification');
          modalNotification.style.display = 'block';
          setTimeout(function (){
              modalNotification.style.display = 'none';
          },2000)
      },
      setSavedPost(){
          axios.get(`/api/post/${this.$route.query.postId}/saved`)
          .then(this.handleGetSavedPostResponse)
          .catch(error => {
             console.log(error);
          });
      },
      handleGetSavedPostResponse(savedPostObj){
           this.title = savedPostObj.data.title;
           this.body = savedPostObj.data.body;
      },
      hideSearchResults(){
        this.showSearchResults = false;
      },
      deletePost(){
        axios.delete(`/api/post/saved/${this.$route.query.postId}`)
        .then(this.handleDeletePostResponse)
        .catch(error => {
           console.log(error);
        });
      },
      publicPost(){
          if(this.title !== '' && this.body !== '' && this.communityForSend !== ''){
              let loadScreen = document.getElementById('loadScreen');
              loadScreen.style.display = 'flex';
              let body = JSON.stringify({body:this.body,
                                              title:this.title,
                                              communityForSendId:this.communityForSendId});

              axios.post('/api/post/publish',body,{
                  headers:{
                   'Content-Type':'application/json'
                  }
              })
              .then(this.handlePublishResponse)
              .catch(error => {
                  console.log(error)
              })
          }
      },
      handlePublishResponse(publishResponseObj){
          let loadScreen = document.getElementById('loadScreen'),
              modalNotification = document.getElementById('notification');

          loadScreen.style.display = 'none';

          this.notificationMessage = 'Пост обрабатывается,вы можете проследить за ним в очереди';
          modalNotification.style.display = 'block';

          setTimeout(function (){
              modalNotification.style.display = 'none';
          },3500);

      },
      handleDeletePostResponse(response) {
          let modalNotification = document.getElementById('notification'),
              router = this.$router;
          this.notificationMessage = 'Пост удалён из черновиков,вас перенесёт на страницу черновиков';
          modalNotification.style.display = 'block';
          setTimeout(function (){
              modalNotification.style.display = 'none';
              router.push('/saved');
          },3000);
      },
      searchCommunity(e){
          let searchValue = e.target.value.trim();
          if(e.target.value.length > 20){
             e.target.value = e.target.value.slice(0,-1);
             alert('Слишком длинное название!');
          }
          if(searchValue !== ''){
              axios.get('/api/community/search?limit=5')
              .then(this.handleSearchResponse)
              .catch();
          }
      },
      handleSearchResponse(searchResponseObj){
          this.searchTarget = searchResponseObj.data;
      }
    },
    computed:{
        isSaved(){
            return this.$route.params.type === 'saved';
        }
    },
    beforeDestroy() {
      localStorage.removeItem('title');
      localStorage.removeItem('body');
    },
    beforeUpdate() {
        if(this.title === null){
            this.title = ''
        }
        if(this.body === null){
            this.body = '';
        }
        //todo Здесь нужно будет сделать тоже самое для графы поиска
        localStorage.setItem('title',this.title);
        localStorage.setItem('body',this.body);
    },
    beforeMount(){
        //todo Нужно продумать изменения компонента при изменении ЮРЛ с помощью перехода и с помощью ручного изменения
        this.title = localStorage.getItem('title');
        this.body = localStorage.getItem('body');
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB|PlayBook|IEMobile|Windows Phone|Kindle|Silk|Opera Mini/i.test(navigator.userAgent)){
            this.$router.push('/');
        }
        if(this.$route.query.community !== undefined) {
            this.communityForSend = this.$route.query.community;
            this.communityForSendId = this.$route.query.communityId;
        }

        if(this.$route.params.type === 'saved' && (this.title === '' || this.title === null) && (this.body === '' || this.body === null)){
            this.setSavedPost();
        }
    },
    watch:{
        $route(to,from){
            if(to.params.type !== from.params.type){
                this.body = '';
                this.title = '';
                this.communityForSendId = 0;
                this.communityForSend = '';
            }
            if(to.params.type === 'saved' && to.fullPath){
                this.setSavedPost();
            }
        }

    }
}
</script>

<style scoped>
.loadScreen{
    width:100vw;
    background-color: rgba(100, 100, 100, .6);
    height: 100vh;
    position: fixed;
    flex-direction: row;
    justify-content: center;
    align-items: center;
}
.search-results{
    width: 100%;
    display: flex;
    flex-direction: column;
    position:absolute;
    max-height: 150px;
    overflow-y: scroll;
    background-color: white;
    z-index: 10;
}
.search-values{
    cursor: pointer;
    height: 50px;
    color:black;
    border-bottom:1px solid black;
    border-top: 1px solid black;
}
.sendCommunity{
    padding: 0;
    color:black;
    word-break: break-word;
}

 .title{
     font-size:1.5rem;
     height: 100px;
 }
 .text-editor{
   padding: 0;
   background-color: white;
 }
</style>
<style>
/*
Todo Ух,костылики!
 */
.ql-editor{
    max-height: 400px;
    overflow-y: scroll!important;
}
.ql-container{
    color:black;
    height: auto!important;
}
</style>
