"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[368],{3744:(e,n)=>{n.Z=(e,n)=>{const t=e.__vccOpts||e;for(const[e,a]of n)t[e]=a;return t}},8252:(e,n,t)=>{t.d(n,{Z:()=>B});var a=t(821),o={class:"LayoutDefault"},s={class:"LayoutDefault-main"},r={class:"LayoutDefault-inner"};var c={class:"FlashBanner-container"},l={class:"FlashBanner-icon"},i={key:0,class:"fas fa-check-circle"},u={key:1,class:"fas fa-exclamation-circle"},d={key:2,class:"fas fa-exclamation-triangle"},m={key:3,class:"fas fa-info-circle"},p={class:"FlashBanner-message"};const f=(0,a.defineComponent)({data:function(){return{show:!0}},computed:{message:function(){var e,n;return(null===(e=this.$page.props.flash)||void 0===e||null===(n=e.banner)||void 0===n?void 0:n.message)||null},type:function(){var e,n;return(null===(e=this.$page.props.flash)||void 0===e||null===(n=e.banner)||void 0===n?void 0:n.type)||null},classes:function(){switch(this.type){case"success":return"is-success";case"danger":return"is-danger";case"warning":return"is-warning";default:return"is-info"}}}});var v=t(3744);const N=(0,v.Z)(f,[["render",function(e,n,t,o,s,r){return e.show&&e.message?((0,a.openBlock)(),(0,a.createElementBlock)("div",{key:0,class:(0,a.normalizeClass)(["FlashBanner",e.classes])},[(0,a.createElementVNode)("div",c,[(0,a.createElementVNode)("div",l,["success"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",i)):(0,a.createCommentVNode)("",!0),"danger"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",u)):(0,a.createCommentVNode)("",!0),"warning"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",d)):(0,a.createCommentVNode)("",!0),"info"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",m)):(0,a.createCommentVNode)("",!0)]),(0,a.createElementVNode)("p",p,(0,a.toDisplayString)(e.message),1)])],2)):(0,a.createCommentVNode)("",!0)}]]);var k={class:"PageHeader"},h={class:"PageHeader-container"};const V=(0,a.defineComponent)({props:{title:String},components:{},data:function(){return{}},methods:{}}),E=(0,v.Z)(V,[["render",function(e,n,t,o,s,r){return(0,a.openBlock)(),(0,a.createElementBlock)("div",k,[(0,a.createElementVNode)("div",h,[(0,a.createElementVNode)("h1",null,(0,a.toDisplayString)(e.title),1)])])}]]),g=(0,a.defineComponent)({props:{title:String},components:{PageHeader:E,FlashBanner:N},data:function(){return{}},methods:{},computed:{}}),B=(0,v.Z)(g,[["render",function(e,n,t,c,l,i){var u=(0,a.resolveComponent)("FlashBanner");return(0,a.openBlock)(),(0,a.createElementBlock)("div",o,[(0,a.createVNode)(u),(0,a.createElementVNode)("main",s,[(0,a.createElementVNode)("div",r,[(0,a.renderSlot)(e.$slots,"default")])])])}]])},7368:(e,n,t)=>{t.r(n),t.d(n,{default:()=>Ue});var a=t(821),o=(0,a.createTextVNode)(" Test ");var s={class:"LayoutGlobal"};var r=[(0,a.createElementVNode)("span",{class:"fas fa-times"},null,-1),(0,a.createElementVNode)("span",{class:"u-sr"},"Close",-1)],c={class:"Overlay-scroll"},l={class:"Overlay-inner"},i=(0,a.createElementVNode)("span",{class:"fas fa-user"},null,-1),u=(0,a.createTextVNode)(" Dashboard "),d=(0,a.createElementVNode)("div",{class:"Overlay-head"},[(0,a.createElementVNode)("span",{class:"fas fa-dice"}),(0,a.createTextVNode)(" Resources ")],-1),m=(0,a.createElementVNode)("div",{class:"Overlay-foot"}," Footer ",-1),p=[(0,a.createElementVNode)("span",{class:"fas fa-times"},null,-1),(0,a.createElementVNode)("span",{class:"u-sr"},"Close",-1)],f={class:"Overlay-scroll"},v={class:"Overlay-inner"},N={class:"Overlay-head"},k=(0,a.createElementVNode)("span",{class:"fas fa-user"},null,-1),h=(0,a.createElementVNode)("div",{class:"Overlay-head"},[(0,a.createElementVNode)("span",{class:"fas fa-users"}),(0,a.createTextVNode)(" Group ")],-1),V=(0,a.createElementVNode)("div",{class:"Overlay-foot"},[(0,a.createElementVNode)("span",{class:"fas fa-envelope"}),(0,a.createTextVNode)(" Latest Notifications ")],-1),E={class:"Header"},g=(0,a.createElementVNode)("span",{class:"MenuMain-button-mark"},[(0,a.createElementVNode)("img",{class:"MenuMain-button-mark-img",src:"/img/logo_mark.svg",alt:"Game Board"})],-1),B=(0,a.createElementVNode)("span",{class:"MenuMain-button-logo"},[(0,a.createElementVNode)("img",{class:"MenuMain-button-logo-img",src:"/img/logo_type.svg",alt:"Game Board"})],-1),C={key:0,class:"MenuMain-button-icon fas fa-fw fa-times"},b={key:1,class:"MenuMain-button-icon fas fa-fw fa-bars"},y=(0,a.createElementVNode)("span",{class:"MenuMain-button-label"},"Main Menu",-1),M={key:0,class:"MenuProfile-button-icon fas fa-fw fa-times"},T={key:1,class:"MenuProfile-button-icon fas fa-fw fa-bars"},w=(0,a.createElementVNode)("span",{class:"MenuProfile-button-label"},"User Menu",-1),P={class:"MenuProfile-button-group"},x=(0,a.createElementVNode)("span",{class:"MenuProfile-button-group-label"},"Current group:",-1),L={key:2,class:"MenuProfile-button-notifications"},$=(0,a.createElementVNode)("span",{class:"MenuProfile-button-notifications-icon fas fa-fw fa-envelope"},null,-1),S={class:"MenuProfile-button-notifications-badge"},_={class:"MenuProfile-button-avatar"},A=["src","alt"],O={class:"Header-nav"},D=[(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-caret-down"},null,-1),(0,a.createTextVNode)(" More ")];var Z={class:"NavTop"},H={class:"NavTop-item"},G=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-th"},null,-1),z=(0,a.createTextVNode)(" Dashboard "),F={class:"NavTop-item"},U=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-dice"},null,-1),W=(0,a.createTextVNode)(" Games "),R={class:"NavTop-item"},I=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-trophy"},null,-1),j=(0,a.createTextVNode)(" Plays "),q={class:"NavTop-item"},J=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-users"},null,-1),K=(0,a.createTextVNode)(" Groups "),Q={class:"NavTop-item"},X=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-user"},null,-1),Y=(0,a.createTextVNode)(" Users "),ee={class:"NavTop-item"},ne=(0,a.createElementVNode)("span",{class:"NavTop-icon fas fa-map"},null,-1),te=(0,a.createTextVNode)(" Locations ");var ae=t(9038);const oe=(0,a.defineComponent)({components:{Link:ae.rU},props:["href","active"],computed:{classes:function(){return this.active?"is-active":""}}});var se=t(3744);const re=(0,se.Z)(oe,[["render",function(e,n,t,o,s,r){var c=(0,a.resolveComponent)("Link",!0);return(0,a.openBlock)(),(0,a.createBlock)(c,{href:e.href,class:(0,a.normalizeClass)(e.classes)},{default:(0,a.withCtx)((function(){return[(0,a.renderSlot)(e.$slots,"default")]})),_:3},8,["href","class"])}]]),ce=(0,a.defineComponent)({props:{},components:{Link:re}}),le=(0,se.Z)(ce,[["render",function(e,n,t,o,s,r){var c=(0,a.resolveComponent)("Link");return(0,a.openBlock)(),(0,a.createElementBlock)("ul",Z,[(0,a.createElementVNode)("li",H,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("dashboard.show"),active:e.route().current("dashboard.show")},{default:(0,a.withCtx)((function(){return[G,z]})),_:1},8,["href","active"])]),(0,a.createElementVNode)("li",F,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("game.index"),active:e.route().current("game.index")},{default:(0,a.withCtx)((function(){return[U,W]})),_:1},8,["href","active"])]),(0,a.createElementVNode)("li",R,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("play.index"),active:e.$page.component.startsWith("Play")},{default:(0,a.withCtx)((function(){return[I,j]})),_:1},8,["href","active"])]),(0,a.createElementVNode)("li",q,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("group.index"),active:e.$page.component.startsWith("Group")},{default:(0,a.withCtx)((function(){return[J,K]})),_:1},8,["href","active"])]),(0,a.createElementVNode)("li",Q,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("user.index"),active:e.$page.component.startsWith("User")},{default:(0,a.withCtx)((function(){return[X,Y]})),_:1},8,["href","active"])]),(0,a.createElementVNode)("li",ee,[(0,a.createVNode)(c,{class:"NavTop-link",href:e.route("location.index"),active:e.$page.component.startsWith("Location")},{default:(0,a.withCtx)((function(){return[ne,te]})),_:1},8,["href","active"])])])}]]);var ie={class:"NavLink-item"},ue={key:1,class:"NavLink-chevron fas fa-chevron-down"},de={key:2,class:"NavLink-chevron fas fa-chevron-circle-down"},me={key:0,class:"NavLink-sub"};const pe=(0,a.defineComponent)({components:{Link:re},props:{href:String,active:Boolean},data:function(){return{opened:!1}},watch:{active:{immediate:!0,handler:function(e,n){this.opened=!!e}}},methods:{toggle:function(){this.opened=!this.opened},open:function(){this.opened=!0},close:function(){this.opened=!1}},computed:{hasSlotSub:function(){return!!this.$slots.sub},hasSlotLink:function(){return!!this.$slots.link}}}),fe=(0,se.Z)(pe,[["render",function(e,n,t,o,s,r){var c=(0,a.resolveComponent)("Link");return(0,a.openBlock)(),(0,a.createElementBlock)("div",{class:(0,a.normalizeClass)(["NavLink",{"is-open":e.opened,"is-active":e.active}])},[(0,a.createElementVNode)("div",ie,[e.href?((0,a.openBlock)(),(0,a.createBlock)(c,{key:0,class:"NavLink-link",href:e.href},{default:(0,a.withCtx)((function(){return[(0,a.renderSlot)(e.$slots,"default")]})),_:3},8,["href"])):(0,a.createCommentVNode)("",!0),e.hasSlotSub?((0,a.openBlock)(),(0,a.createElementBlock)("button",{key:1,class:"NavLink-button",onClick:n[0]||(n[0]=function(n){return e.toggle()})},[e.href?(0,a.createCommentVNode)("",!0):(0,a.renderSlot)(e.$slots,"default",{key:0}),e.opened?((0,a.openBlock)(),(0,a.createElementBlock)("span",ue)):(0,a.createCommentVNode)("",!0),e.opened?(0,a.createCommentVNode)("",!0):((0,a.openBlock)(),(0,a.createElementBlock)("span",de))])):(0,a.createCommentVNode)("",!0)]),e.hasSlotSub?((0,a.openBlock)(),(0,a.createElementBlock)("div",me,[(0,a.renderSlot)(e.$slots,"sub")])):(0,a.createCommentVNode)("",!0)],2)}]]),ve=(0,a.defineComponent)({props:{},components:{NavTop:le,NavNotifications,NavLink:fe},data:function(){return{MenuMainActive:!1,MenuProfileActive:!1}},watch:{MenuMainActive:{immediate:!0,handler:function(e){}},MenuProfileActive:{immediate:!0,handler:function(e){}}},methods:{menuMainOpen:function(){this.MenuMainActive=!0},menuMainClose:function(){this.MenuMainActive=!1},menuMainToggle:function(){this.MenuMainActive?this.MenuMainActive=!1:this.MenuMainActive=!0},menuProfileOpen:function(){this.MenuProfileActive=!0},menuProfileClose:function(){this.MenuProfileActive=!1},menuProfileToggle:function(){this.MenuProfileActive?this.MenuProfileActive=!1:this.MenuProfileActive=!0},closeAll:function(){this.menuMainClose(),this.menuProfileClose()}}}),Ne=(0,se.Z)(ve,[["render",function(e,n,t,o,s,Z){var H,G,z,F,U=(0,a.resolveComponent)("NavLink"),W=(0,a.resolveComponent)("NavResources"),R=(0,a.resolveComponent)("NavProfile"),I=(0,a.resolveComponent)("NavGroup"),j=(0,a.resolveComponent)("NavNotifications"),q=(0,a.resolveComponent)("NavTop");return(0,a.openBlock)(),(0,a.createElementBlock)("div",null,[(0,a.createElementVNode)("div",{class:(0,a.normalizeClass)(["Overlay is-left",{"is-active":e.MenuMainActive}])},[(0,a.createElementVNode)("button",{class:"Overlay-close",type:"button",onClick:n[0]||(n[0]=function(){return e.menuMainClose&&e.menuMainClose.apply(e,arguments)})},r),(0,a.createElementVNode)("div",c,[(0,a.createElementVNode)("div",l,[(0,a.createVNode)(U,{href:e.route("dashboard.show"),active:e.route().current("dashboard.show")},{default:(0,a.withCtx)((function(){return[i,u]})),_:1},8,["href","active"]),d,(0,a.createVNode)(W),m])])],2),(0,a.createElementVNode)("div",{class:(0,a.normalizeClass)(["Overlay is-right",{"is-active":e.MenuProfileActive}])},[(0,a.createElementVNode)("button",{class:"Overlay-close",type:"button",onClick:n[1]||(n[1]=function(){return e.menuProfileClose&&e.menuProfileClose.apply(e,arguments)})},p),(0,a.createElementVNode)("div",f,[(0,a.createElementVNode)("div",v,[(0,a.createElementVNode)("div",N,[k,(0,a.createTextVNode)(" "+(0,a.toDisplayString)(null===(H=e.$page.props.user)||void 0===H?void 0:H.name),1)]),(0,a.createVNode)(R),h,(0,a.createVNode)(I),V,(0,a.createVNode)(j)])])],2),(0,a.createElementVNode)("div",E,[(0,a.createElementVNode)("div",{class:(0,a.normalizeClass)(["Header-menu Header-menu--main MenuMain",{"is-active":e.MenuMainActive}])},[(0,a.createElementVNode)("button",{type:"button",class:"MenuMain-button",onClick:n[2]||(n[2]=function(){return e.menuMainToggle&&e.menuMainToggle.apply(e,arguments)})},[g,B,e.MenuMainActive?((0,a.openBlock)(),(0,a.createElementBlock)("span",C)):((0,a.openBlock)(),(0,a.createElementBlock)("span",b)),y])],2),e.$page.props.user?((0,a.openBlock)(),(0,a.createElementBlock)("div",{key:0,class:(0,a.normalizeClass)(["Header-menu Header-menu--profile MenuProfile",{"is-active":e.MenuProfileActive}])},[(0,a.createElementVNode)("button",{type:"button",class:"MenuProfile-button",onClick:n[3]||(n[3]=function(){return e.menuProfileToggle&&e.menuProfileToggle.apply(e,arguments)})},[e.MenuProfileActive?((0,a.openBlock)(),(0,a.createElementBlock)("span",M)):((0,a.openBlock)(),(0,a.createElementBlock)("span",T)),w,(0,a.createElementVNode)("span",P,[x,(0,a.createTextVNode)(" "+(0,a.toDisplayString)(null===(G=e.$page.props.user.current_group)||void 0===G?void 0:G.name),1)]),e.$page.props.notification_count>0?((0,a.openBlock)(),(0,a.createElementBlock)("span",L,[$,(0,a.createElementVNode)("span",S,(0,a.toDisplayString)(e.$page.props.notification_count),1)])):(0,a.createCommentVNode)("",!0),(0,a.createElementVNode)("span",_,[(0,a.createElementVNode)("img",{class:"MenuProfile-button-avatar-img",src:null===(z=e.$page.props.user)||void 0===z?void 0:z.profile_picture_url,alt:null===(F=e.$page.props.user)||void 0===F?void 0:F.name},null,8,A)])])],2)):(0,a.createCommentVNode)("",!0),(0,a.createElementVNode)("nav",O,[(0,a.createVNode)(q),(0,a.createElementVNode)("button",{class:"NavTop-link",type:"button",onClick:n[4]||(n[4]=function(){return e.menuMainOpen&&e.menuMainOpen.apply(e,arguments)})},D)])])])}]]);var ke={class:"Notifications"},he={class:"Notifications-timer"},Ve={class:"Notifications-inner"},Ee={class:"Notifications-icon"},ge={key:0,class:"fas fa-check-circle"},Be={key:1,class:"fas fa-exclamation-circle"},Ce={key:2,class:"fas fa-exclamation-triangle"},be={key:3,class:"fas fa-info-circle"},ye={class:"Notifications-message"};const Me=(0,a.defineComponent)({data:function(){return{lifespan:1e4,notifications:[]}},methods:{add:function(e){this.notifications.push({timer:0,message:e.message,created:Date.now(),type:e.type,class:this.classname(e.type)})},check:function(){var e=this;this.notifications.forEach((function(n){Date.now()-e.lifespan>n.created?e.notifications.shift():n.timer=(Date.now()-n.created)/e.lifespan*100}))},classname:function(e){switch(e){case"success":return"is-success";case"danger":return"is-danger";case"warning":return"is-warning";default:return"is-info"}}},watch:{"$page.props.flash.notification":function(e){e&&this.add(e)}},mounted:function(){var e=this;setInterval((function(){e.check()}),100)},unmounted:function(){}}),Te=(0,se.Z)(Me,[["render",function(e,n,t,o,s,r){return(0,a.openBlock)(),(0,a.createElementBlock)("div",ke,[((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.notifications,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("div",{class:(0,a.normalizeClass)(["Notifications-popup",e.class]),key:e},[(0,a.createElementVNode)("div",he,[(0,a.createElementVNode)("div",{class:"Notifications-timer-bar",style:(0,a.normalizeStyle)({width:e.timer+"%"})},null,4)]),(0,a.createElementVNode)("div",Ve,[(0,a.createElementVNode)("div",Ee,["success"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",ge)):(0,a.createCommentVNode)("",!0),"danger"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",Be)):(0,a.createCommentVNode)("",!0),"warning"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",Ce)):(0,a.createCommentVNode)("",!0),"info"==e.type?((0,a.openBlock)(),(0,a.createElementBlock)("span",be)):(0,a.createCommentVNode)("",!0)]),(0,a.createElementVNode)("p",ye,(0,a.toDisplayString)(e.message),1)])],2)})),128))])}]]),we=(0,a.defineComponent)({components:{GlobalHeader:Ne,Notifications:Te}}),Pe=(0,se.Z)(we,[["render",function(e,n,t,o,r,c){var l=(0,a.resolveComponent)("GlobalHeader"),i=(0,a.resolveComponent)("Notifications");return(0,a.openBlock)(),(0,a.createElementBlock)("div",s,[(0,a.createVNode)(l),(0,a.renderSlot)(e.$slots,"default"),(0,a.createVNode)(i)])}]]);var xe=t(8252),Le={class:"Title"},$e={class:"Title-breadcrumbs"},Se={class:"Title-title"},_e={class:"Title-controls"};var Ae={class:"Breadcrumbs"},Oe={class:"Breadcrumbs-list"},De=["innerHTML"];const Ze=(0,a.defineComponent)({components:{Link:re},computed:{breadcrumbs:function(){var e;return null===(e=this.$page.props)||void 0===e?void 0:e.breadcrumbs},breadcrumbs_first:function(){var e;return null===(e=this.$page.props)||void 0===e?void 0:e.breadcrumbs.slice(0,3)},breadcrumbs_more:function(){var e,n;return(null===(e=this.$page.props)||void 0===e?void 0:e.breadcrumbs.slice(2).length)>1?null===(n=this.$page.props)||void 0===n?void 0:n.breadcrumbs.slice(2):[]}}}),He=(0,se.Z)(Ze,[["render",function(e,n,t,o,s,r){var c=(0,a.resolveComponent)("Link");return(0,a.openBlock)(),(0,a.createElementBlock)("nav",Ae,[(0,a.createElementVNode)("ul",Oe,[((0,a.openBlock)(!0),(0,a.createElementBlock)(a.Fragment,null,(0,a.renderList)(e.breadcrumbs,(function(e){return(0,a.openBlock)(),(0,a.createElementBlock)("li",{class:"Breadcrumbs-item",key:e},[e.is_current_page?(0,a.createCommentVNode)("",!0):((0,a.openBlock)(),(0,a.createBlock)(c,{key:0,class:"Breadcrumbs-link",href:e.url},{default:(0,a.withCtx)((function(){return[(0,a.createElementVNode)("span",{innerHTML:e.title},null,8,De)]})),_:2},1032,["href"]))])})),128))])])}]]),Ge=(0,a.defineComponent)({components:{Breadcrumbs:He}}),ze=(0,se.Z)(Ge,[["render",function(e,n,t,o,s,r){var c=(0,a.resolveComponent)("Breadcrumbs");return(0,a.openBlock)(),(0,a.createElementBlock)("div",Le,[(0,a.createElementVNode)("div",$e,[(0,a.createVNode)(c)]),(0,a.createElementVNode)("div",Se,[(0,a.renderSlot)(e.$slots,"default")]),(0,a.createElementVNode)("div",_e,[(0,a.renderSlot)(e.$slots,"controls")])])}]]);t(9680);const Fe=(0,a.defineComponent)({layout:Pe,components:{LayoutDefault:xe.Z,Title:ze,Link:re},props:["devices"],data:function(){return{}},methods:{}}),Ue=(0,se.Z)(Fe,[["render",function(e,n,t,s,r,c){var l=(0,a.resolveComponent)("LayoutDefault");return(0,a.openBlock)(),(0,a.createBlock)(l,null,{default:(0,a.withCtx)((function(){return[o]})),_:1})}]])}}]);