import{_ as d,a as $,b as g,L as y}from"./LayoutDefault-1bb0b9d5.js";import{d as l,o,c as a,a as s,t as c,b as n,g as D,w as L,e as r,h as F,F as k}from"./app-884ea1ba.js";import{F as S}from"./FigureExcerpt-c49eaa42.js";const b=l({components:{},props:{device:Object}}),B={class:"tile is-child box"},E={class:""},w={class:""},C={class:""},x=["src"];function N(e,p,u,_,m,v){var t;return o(),a("article",B,[s("p",E,c(e.device.name),1),s("p",w,c(e.device.description),1),s("p",C,c((t=e.device.last_reading)==null?void 0:t.timestamp_formatted),1),s("img",{src:e.device.img},null,8,x)])}const V=d(b,[["render",N]]),j=l({layout:$,components:{LayoutDefault:g,Link:y,DeviceSummary:V,FigureExcerpt:S},props:["device"],data(){return{}},computed:{}}),O=s("h2",{class:"title is-3"},"Reading Types",-1),R={class:"card"},T={class:"card-content"},q={class:"columns is-multiline is-centered"};function z(e,p,u,_,m,v){const t=n("DeviceSummary"),f=n("FigureExcerpt"),h=n("LayoutDefault");return o(),D(h,null,{default:L(()=>[r(t,{device:e.device},null,8,["device"]),O,s("div",R,[s("div",T,[s("section",q,[(o(!0),a(k,null,F(e.device.figures,i=>(o(),a("div",{key:i},[r(f,{figure:i,device:e.device},null,8,["figure","device"])]))),128))])])])]),_:1})}const I=d(j,[["render",z]]);export{I as default};
