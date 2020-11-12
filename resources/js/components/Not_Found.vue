<template>
    <div class="vh-100 wrapper" :class="toggle_BG_APP">
        <transition name="fade" mode="out-in">
            <span v-if="needToShow" class="plug" :class="toggleTextColor">404|Страница не найдена</span>
            <router-link v-else :to="{name:'lenta'}"
                                :class="[plug,toggleTextColor]">
                Перейти на главную
            </router-link>
        </transition>
    </div>
</template>

<script>
    import {colorToggle} from '../mixins/colorToggle.js'
    export default {
        name: "Not_Found",
        mixins:[colorToggle],
        data(){
          return {
              needToShow:true,
              plug:'plug'
          }
        },
        beforeMount() {

            document.title = 'Not Found';
           setTimeout(this.reverse,1500);

        },
        methods:{

            reverse(){
               this.needToShow = !this.needToShow;
            }

        }
    }

</script>

<style scoped>
    .plug{
        font-size: 44px;
    }
    .wrapper{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .fade-enter-active,.fade-leave-active {
        transition: opacity 0.5s ease-out;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }




</style>
