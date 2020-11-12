<template>
    <div class="d-flex flex-column align-items-start pointer"
         @click="showPostView = true"
         :class="[formatClass,toggleBackGround,toggleTextColor]">
        <div class="title font-weight-bold">
            {{post.title}}
        </div>
        <div class="size">
            Длина: {{textLength}}
        </div>
        <div class="date">
            Последнее изменение: {{post.last_update_time|formDate}}
        </div>
        <post-view :post="post"
                   v-show="showPostView"
                   @noShowMe="showPostView = false"
                   @completeDelete="emitCompleteDelete"
        ></post-view>
    </div>
</template>

<script>
import {colorToggle} from "../../mixins/colorToggle";
import {formDate} from "../../mixins/formDate";
import SavedPostView from "./SavedPostView";

export default {
   data(){
       return {
           showPostView : false,
       }
   },
   components : {'post-view' : SavedPostView},
   name: "SavedPost",
   mixins:[colorToggle,formDate],
   props:['post','formatClass'],
   methods : {
        emitCompleteDelete(id) {
            this.$emit('completeDelete',id);
        }
   },
   computed : {
        textLength(){
            return this.post.body.length;
        }
   },
   beforeUpdate() {
       console.log(this.showPostView);
   }
}
</script>

<style scoped>
.items {
    cursor: pointer;
    min-height: 50px;
    border-radius: 6px;
}
</style>
