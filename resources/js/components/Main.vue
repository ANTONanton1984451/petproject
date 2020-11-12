<template>
    <div :class="toggle_BG_APP">
        <h1 :class="toggleTextColor">Компонент главной страницы</h1>
        <router-link :to="{name:'lenta'}" >Лента</router-link>
    </div>

</template>

<script>
    import {colorToggle} from '../mixins/colorToggle.js'
    export default {
        name:'Main',
        mixins: [colorToggle],
        mounted() {
            document.title = 'главная';
        }
    }
</script>
