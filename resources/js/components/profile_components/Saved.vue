<template>
    <div class="component-wrapper py-2">
        <div class="d-sm-flex d-none flex-column align-items-center mb-2">
            <span class="font-weight-bold" :class="toggleTextColor" style="font-size: 1.2rem">
                Вид
            </span>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary active">
                    <input type="radio" name="options" id="option1" autocomplete="off" checked @click="isClassicView=true">Сетка
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="options" id="option2" autocomplete="off" @click="isClassicView=false"> Строки
                </label>
            </div>
        </div>
        <div :class="viewClasses.wrapper">
            <saved-post v-for="post in savePosts"
            :format-class="viewClasses.items"
            :post="post"
            @completeDelete="handleCompleteDelete"></saved-post>
        </div>
    </div>
</template>

<script>

import {colorToggle} from '../../mixins/colorToggle.js'
import SavedPost from "./SavedPost";
export default {
    name: "Saved",
    components:{SavedPost},
    mixins:[colorToggle],
    data(){
        return{
            savePosts : [],
            isClassicView:true,
            classicViewClasses:{
                wrapper:'col-12 d-flex flex-column flex-wrap flex-sm-row justify-content-sm-center  align-items-sm-center ',
                items:'col-sm-5 col-11 col-md-3 p-3 mx-2 my-1 items'
            },
            lineViewClasses:{
                wrapper:'col-12 col-md-9 d-flex flex-column justify-content-start align-items-center',
                items:'col-11 items my-1 p-3'
            }
        }
    },
    methods:{
      handleGetSaveResponse(saveResponseObj){
        console.log(saveResponseObj);
        this.savePosts = saveResponseObj.data;
      },
      handleCompleteDelete(id){
          this.savePosts = this.savePosts.filter(function(post) {
              return post.id !== id;
          })
      }
    },
    computed:{
        viewClasses(){
            return this.isClassicView ? this.classicViewClasses : this.lineViewClasses;
        }
    },
    beforeMount() {
        axios.get('/api/saved/preview')
        .then(this.handleGetSaveResponse)
        .catch()
    }
}
</script>

<style scoped>
</style>
