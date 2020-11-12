<template>
    <div class="nav-wrapper col-10 flex-sm-row justify-sm-content-center justify-content-center">
        <router-link tag="strong"
                     :to="{name:'profile.new',params:{type:'overview',username:userName}}"
                     class="col  profile-link"
                     active-class="active-path">
            Обзор
        </router-link>
        <router-link tag="strong"
                     class="col profile-link"
                     :to="{name:'profile.new',params:{type:'posts',username:userName}}"
                     active-class="active-path">
            Посты
        </router-link>
        <router-link tag="strong"
                     class="col profile-link"
                     :to="{name:'profile.new',params:{type:'comments',username:userName}}"
                     active-class="active-path">
            Комменты
        </router-link>
        <router-link v-if=host
                     tag="strong"
                     class="col profile-link"
                     :to="{name:'profile.saved',params:{username:'test'}}"
                     active-class="active-path">
            Черновики
        </router-link>
    </div>
</template>

<script>
    export default {
        props:['host','userName'],
        name: "ProfileNav",
        data(){
            return{

            }
        },
        mounted() {
          console.log(this.userName)
        }

    }
</script>

<style scoped>

.active-path{
    border-bottom: 2px solid #3490dc;;
}
.profile-link{
    cursor: pointer;
}
.nav-wrapper{
    display: flex;
    flex-direction: column;
}

</style>
