<template>
    <tred :key="$route.path"></tred>
</template>

<script>
import Tred from "./Tred";
export default {
    name: "TredWrapper",
    components:{Tred}
}
</script>

<style scoped>

</style>
