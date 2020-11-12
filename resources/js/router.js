import New from "./components/get_components/New";
import Hot from "./components/get_components/Hot";
import Top from "./components/get_components/Top";
import Main from "./components/Main";
import Saved from "./components/profile_components/Saved";
import Lenta from "./components/Lenta";
import Not_Found from "./components/Not_Found";
import Community from "./components/Community"
import VueRouter from "vue-router";
import BaseProfileLayout from "./components/profile_components/BaseProfileLayout";
import Editing from "./components/Editing";
import Settings from "./components/Settings";
import Statistic from "./components/Statistic";
import CreateCommunity from "./components/CreateCommunity";
import TredWrapper from "./components/TredWrapper";

export const router = new VueRouter({
    mode:'history',
    routes:[
        {
            path:'/main',
            component:Main,
            name:'main',
            props:true
        },
        {
            path:'/create_community',
            component: CreateCommunity,
            name:'community.create',
            props: true,
            meta : {
                needleAuth: true
            }
        },
        {
            path:'/lenta',
            component:Lenta,
            name:'lenta',
            props:true,
            meta:{
                needleAuth : false
            }
        },
        {
            path:'/',
            component:Main,
            name:'main',
            props:true
        },
        {
           path:'/settings',
           component: Settings,
           name:'settings',
           props: true,
            meta : {
               needleAuth : true
            }
        },
        {
          path: '/statistic',
          component: Statistic,
          name:'statistic',
          props: true,
            meta : {
                needleAuth : true
            }
        },
        {
            path:'/user/:username/:type',
            component:BaseProfileLayout,
            props:true,
            children: [
                {
                    path:'',
                    name:'profile.new',
                    component:New,
                    props:true
                },
                {
                    path:'hot',
                    name:'profile.hot',
                    component:Hot,
                    props:true
                },
                {
                    path:'top',
                    name:'profile.top',
                    component: Top,
                    props: true
                }
            ],
            meta : {
                needleAuth : false
            }
        },

        {
            path:'/saved',
            name:'profile.saved',
            component: Saved,
            props: true,
            meta : {
                needleAuth : true
            }
        },
        {
            path:'/tred/:tredId',
            component:TredWrapper,
            name:'tred',
            props:true,
            meta : {
                needleAuth : false
            }
        },
        {
            path:'/community/:id',
            component:Community,
            name:'community',
            props:true,
            meta : {
                needleAuth : false
            }

        },
        {
            path:'/community/:id/new',
            component:Community,
            name:'community.new',
            props:true,
            meta : {
                needleAuth : false
            }

        },
        {
            path:'/community/:id/top',
            component:Community,
            name:'community.top',
            props:true,
            meta : {
                needleAuth : false
            }

        },
        {
            path:'/community/:id/hot',
            component:Community,
            name:'community.hot',
            props:true,
            meta : {
                needleAuth : false
            }

        },
        {
            path:'/createPost/:type',
            component: Editing,
            name:'create',
            props: true,
            meta : {
                needleAuth : false
            }
        },
        {
            path:'*',
            redirect:'/404',
            meta : {
                needleAuth : false
            }
        },
        {
            path:'/404',
            component: Not_Found,
            name:'not found',
            meta : {
                needleAuth : false
            }
        },
    ]
});
