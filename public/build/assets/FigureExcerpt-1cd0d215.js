import{b as _,_ as u}from"./LayoutDefault-b28fd55f.js";import{T as y}from"./Timestamp-9b0db0d2.js";import{d as g,o as s,c as t,h as o,a as i,g as r,t as a,e as l,F as k,f as F,G as $,x as D}from"./app-cc5863f5.js";const b=g({components:{Link:_,Timestamp:y},props:{device:Object}}),x={class:"DeviceExcerpt"},L={key:0,class:"DeviceExcerpt-alerted"},T=["title"],N={class:"DeviceExcerpt-details"},V={class:"FigureExcerpt-title"},j={class:"DeviceExcerpt-name"},B={key:0,class:"DeviceExcerpt-summary"},C={key:0,class:"DeviceExcerpt-timestamp"},O={key:1,class:"DeviceExcerpt-image"},w=["src"];function S(e,m,p,v,f,h){const n=l("Link"),c=l("Timestamp");return s(),t("article",x,[o(n,{class:"DeviceExcerpt-link",href:e.route("device.show",e.device)},null,8,["href"]),e.device.alerted?(s(),t("div",L,[i("span",{class:"fa-solid fa-warning",title:e.device.alert_activated_formatted},null,8,T)])):r("",!0),i("div",N,[i("div",V,[i("h2",j,a(e.device.name),1),e.device.summary?(s(),t("p",B,a(e.device.summary),1)):r("",!0)]),e.device.last_reading?(s(),t("div",C,[o(c,{date:e.device.last_reading.created_at_date,time:e.device.last_reading.created_at_time,diff:e.device.last_reading.created_at_diff},null,8,["date","time","diff"])])):r("",!0)]),e.device.image_src?(s(),t("div",O,[i("img",{class:"DeviceExcerpt-image-img",src:e.device.image_src},null,8,w)])):r("",!0)])}const ie=u(b,[["render",S]]);const z=g({components:{Link:_},props:{figure:Object,device:Object}}),G={class:"FigureExcerpt"},q={key:0,class:"FigureExcerpt-alerts"},A={class:"FigureExcerpt-alerts-alert"},H=["title"],I={key:1,class:"FigureExcerpt-icon"},J=["src"],K=["src"],M={class:"FigureExcerpt-details"},P={class:"FigureExcerpt-title"},Q={class:"FigureExcerpt-name"},R={key:0,class:"FigureExcerpt-summary"},U={key:0,class:"FigureExcerpt-time"},W=i("span",{class:"fa-solid fa-clock"},null,-1),X={key:1,class:"FigureExcerpt-value"},Y={key:2,class:"FigureExcerpt-chart"};function Z(e,m,p,v,f,h){var c,d;const n=l("Link");return s(),t("div",G,[o(n,{class:"FigureExcerpt-link",href:e.route("device.figure.history",[e.device,e.figure])},null,8,["href"]),e.figure.active_alerts?(s(),t("div",q,[(s(!0),t(k,null,F(e.figure.active_alerts,E=>(s(),t("div",A,[i("i",{class:"fa-solid fa-triangle-exclamation",title:E.activated_formatted},null,8,H)]))),256))])):r("",!0),e.figure.icon_src?(s(),t("figure",I,[i("img",{class:"FigureExcerpt-icon-img",src:e.figure.icon_src},null,8,J),i("img",{class:"FigureExcerpt-icon-small",src:e.figure.icon_small_src},null,8,K)])):r("",!0),i("div",M,[i("div",P,[i("h2",Q,a(e.figure.name),1),e.figure.summary?(s(),t("p",R,a(e.figure.summary),1)):r("",!0)]),e.figure.last_reading?(s(),t("h4",U,[W,$(" "+a(e.figure.last_reading.timestamp_time)+" ",1),i("small",null,a(e.figure.last_reading.timestamp_date),1)])):r("",!0),e.figure.last_reading?(s(),t("h4",X,a(e.figure.last_reading.value_string),1)):r("",!0)]),e.figure.last_reading?(s(),t("div",Y,[i("div",{class:"FigureExcerpt-chart-bar",style:D({height:((c=e.figure.last_reading)==null?void 0:c.range_percentage)+"%",background:(d=e.figure.last_reading)==null?void 0:d.range_color})},null,4)])):r("",!0)])}const re=u(z,[["render",Z]]);export{ie as D,re as F};