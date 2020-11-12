import Vuex from "vuex";
import Vue from 'vue'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state:{
        colorStatus:{
            is_B_White:true,
            is_APP_White:true,
            is_T_White:false,
        },
        nickName : '',
        avatarUrl : "/images/user.svg",
        auth : false,
        userId : 0//todo нужен ли ID?
    },
    mutations:{
        'set_B_Color'(state,value){
            state.colorStatus.is_B_White = value;
        },
        'logOut'(state){
            state.auth = false;
            state.nickName = '';
            state.avatarUrl = "/images/user.svg";
            state.userId = 0;
        },
        'auth'(state,authResponse){
            state.auth = true;
            state.nickName = authResponse.name;
            state.userId = authResponse.id
        },
        'set_APP_Color'(state,value){
            state.colorStatus.is_APP_White = value;
        },
        'set_T_Color'(state,value){
            state.colorStatus.is_T_White = value;
        },
        'setNickName'(state,value){
            state.nickName = value;
        }
    },
    actions:{
        toggleColor(context){
            context.commit('set_B_Color',!this.state.colorStatus.is_B_White);
            context.commit('set_APP_Color',!this.state.colorStatus.is_APP_White);
            context.commit('set_T_Color',!this.state.colorStatus.is_T_White);
            localStorage.setItem('is_B_White',this.state.colorStatus.is_B_White);
            localStorage.setItem('is_APP_White',this.state.colorStatus.is_APP_White);
            localStorage.setItem('is_T_White',this.state.colorStatus.is_T_White);
        },

        checkLocalStorage(context){
            let background = localStorage.getItem('is_B_White'),
                app = localStorage.getItem('is_B_White'),
                text = localStorage.getItem('is_T_White');
            let issetValues = (typeof(background) != "undefined" && background !== null && background !== '') &&
                (typeof(app) != "undefined" && app !== null && app !== '') &&
                (typeof(text) != "undefined" && text !== null && text !== '');
            background = 'true'===background;
            app = 'true' === app;
            text = 'true' === text;
            if(issetValues){
                context.commit('set_B_Color',background);
                context.commit('set_APP_Color',app);
                context.commit('set_T_Color',text);
            }
        },

        logOut({commit}){
            commit('logOut');
        },
        auth({commit},authResponse) {
            commit('auth',authResponse)
        },

        setNickName(context,value) {
            context.commit('setNickName',value)
        },

    },
    getters: {
        isAuth : state => {
          return state.auth;
        },
        getID : state => {
          return state.userId;
        },
        getAvatarSrc : state => {
           return state.avatarUrl;
        },
        getNickName : state => {
          return state.nickName;
        },
        getTextStatus:state=>{
            return state.colorStatus.is_T_White;
        },
        getBackGroundStatus:state=>{
            return state.colorStatus.is_B_White;
        },
        getAPPStatus:state=>{
            return state.colorStatus.is_APP_White;
        }
    }
})
