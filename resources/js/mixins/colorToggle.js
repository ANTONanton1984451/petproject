
export const colorToggle = {
    data(){
        return{

        }
    },
    methods:{

    },
    computed:{
        toggleTextColor(){
            if(this.$store.getters.getTextStatus){
                return 't-black';
            }else{
                return 't-white';
            }
        },
        toggleBackGround(){
            if(this.$store.getters.getBackGroundStatus){
                return 'b-black';
            }else{
                return 'b-white';
            }
        },
        toggle_BG_APP(){
            if(this.$store.getters.getAPPStatus){
                return 'app-black';
            }else{
                return 'app-white';
            }
        },
        toggleBtnColor(){
            if(this.$store.getters.getBackGroundStatus){
                return 'btn-bg-dark';
            }else{
                return 'btn-bg-light';
            }
        }
    }
};
