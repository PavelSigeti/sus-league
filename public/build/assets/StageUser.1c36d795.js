import{A as C}from"./AppHeader.a983c65a.js";import{_ as L}from"./AppDashStages.2cd1efef.js";import{_ as f,r as g,t as y,o as s,c as r,F as h,P as b,e as p,G as M,D as E,a as l,f as m,b as a,d as x,w as S,h as k,Q as v,V as A}from"./app.68a49c4f.js";import{A as B}from"./AppLoader.156f2d92.js";import{t as H}from"./time.a44078dd.js";const V={class:"stage-container"},D={__name:"AppActualStage",setup(n){const t=g();return y(async()=>{try{const o=await axios.get("/api/stage/actual");t.value=o.data}catch(o){console.log(o.message)}}),(o,e)=>(s(),r("div",V,[(s(!0),r(h,null,b(t.value,i=>(s(),p(L,{key:i.id,stage:i},null,8,["stage"]))),128))]))}},I=f(D,[["__scopeId","data-v-6d5d1ecb"]]),N={name:"AppUserStage",components:{AppLoader:B},props:["stage"],setup(n){const t=()=>{const _=new Date().toLocaleString("ru-RU",{timeZone:"Europe/Moscow"}).split(",");u.value=`${_[0]} ${_[1]}`},o=M(),e=g(n.stage),i=g(!1),u=g();y(()=>{t()});const d=setInterval(t,1e3);return E(()=>{clearInterval(d)}),{stage:e,loading:i,time:H,toggleReg:async()=>{i.value=!0;try{e.value.users_exists?(await axios.post(`/api/stage/${e.value.id}/cancel`),e.value.users_exists=!1,o.dispatch("notification/displayMessage",{value:"\u0412\u044B \u0443\u0441\u043F\u0435\u0448\u043D\u043E \u043E\u0442\u043A\u0430\u0437\u0430\u043B\u0438\u0441\u044C \u043E\u0442 \u0443\u0447\u0430\u0441\u0442\u0438\u0435 \u0432 \u0440\u0435\u0433\u0430\u0442\u0435",type:"primary"})):(await axios.post(`/api/stage/${e.value.id}/accept`),e.value.users_exists=!0,o.dispatch("notification/displayMessage",{value:"\u0412\u044B \u0437\u0430\u0440\u0435\u0433\u0435\u0441\u0442\u0438\u0440\u043E\u0432\u0430\u043D\u044B \u043D\u0430 \u0440\u0435\u0433\u0430\u0442\u0443",type:"primary"}))}catch(_){console.log(_.message),o.dispatch("notification/displayMessage",{value:_.response.data.message,type:"error"})}i.value=!1},now:u}}},T={class:"user-stage"},$={class:"user-stage__header"},F={class:"user-stage__tournament"},z={key:0,class:"user-stage__participant"},G=["innerHTML"],P={class:"user-stage__date"},Q={key:2,class:"user-stage__info"},Z={key:4,class:"user-stage__info"},j={key:5,class:"user-stage__info"};function q(n,t,o,e,i,u){const d=l("AppLoader"),c=l("router-link");return s(),r("div",T,[e.loading?(s(),p(d,{key:0})):m("",!0),a("div",$,[x(c,{to:`/dashboard/stage/${e.stage.id}`,class:"user-stage__title"},{default:S(()=>[k(v(e.stage.title),1)]),_:1},8,["to"]),a("div",F,v(e.stage.tournament),1),e.stage.users_exists?(s(),r("div",z,"\u0412\u044B \u0443\u0447\u0430\u0441\u0442\u0432\u0443\u0435\u0442\u0435")):m("",!0)]),e.stage.excerpt?(s(),r("div",{key:1,class:"user-stage__excerpt content",innerHTML:e.stage.excerpt},null,8,G)):m("",!0),a("div",P,[a("span",null,"\u041D\u0430\u0447\u0430\u043B\u043E \u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u0438: "+v(e.time(e.stage.register_start)),1),a("span",null,"\u041E\u043A\u043E\u043D\u0447\u0430\u043D\u0438\u0435 \u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u0438: "+v(e.time(e.stage.register_end)),1)]),e.time(e.stage.register_start)>e.now?(s(),r("div",Q," \u0420\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u044F \u043D\u0435 \u043D\u0430\u0447\u0430\u043B\u0430\u0441\u044C ")):e.stage.status==="active"&&e.time(e.stage.register_end)>e.now?(s(),r("div",{key:3,class:A(["btn","btn-settings-280",{"btn-default":!e.stage.users_exists},{"btn-border":e.stage.users_exists}]),onClick:t[0]||(t[0]=(..._)=>e.toggleReg&&e.toggleReg(..._))},v(e.stage.users_exists?"\u041E\u0442\u043A\u0430\u0437\u0430\u0442\u044C\u0441\u044F \u043E\u0442 \u0443\u0447\u0430\u0441\u0442\u0438\u044F":"\u041F\u0440\u0438\u043D\u044F\u0442\u044C \u0443\u0447\u0430\u0441\u0442\u0438\u0435"),3)):e.stage.status==="finished"?(s(),r("div",Z," \u0420\u0435\u0433\u0430\u0442\u0430 \u0437\u0430\u043A\u043E\u043D\u0447\u0438\u043B\u0430\u0441\u044C ")):(s(),r("div",j," \u041E\u0436\u0438\u0434\u0430\u0439\u0442\u0435 ")),x(c,{to:`/dashboard/stage/${e.stage.id}`,class:"btn btn-border btn-settings-280 mt10"},{default:S(()=>[k("\u041F\u043E\u0434\u0440\u043E\u0431\u043D\u0435\u0435")]),_:1},8,["to"])])}const U=f(N,[["render",q]]),J={name:"AppRegisteredStage",components:{AppUserStage:U},setup(){const n=g();return y(async()=>{try{const t=await axios.get("/api/stage/registered-stage");n.value=t.data}catch(t){console.log(t.message)}}),{stages:n}}};function K(n,t,o,e,i,u){const d=l("AppUserStage");return s(!0),r(h,null,b(e.stages,c=>(s(),p(d,{key:c.id,stage:c},null,8,["stage"]))),128)}const O=f(J,[["render",K]]),W={name:"AppEndedStage",components:{AppUserStage:U},setup(){const n=g();return y(async()=>{try{const t=await axios.get("/api/stage/ended");n.value=t.data}catch(t){console.log(t.message)}}),{stages:n}}};function X(n,t,o,e,i,u){const d=l("AppUserStage");return s(!0),r(h,null,b(e.stages,c=>(s(),p(d,{key:c.id,stage:c},null,8,["stage"]))),128)}const Y=f(W,[["render",X]]),ee={name:"StageUser",components:{AppHeader:C,AppActualStage:I,AppRegisteredStage:O,AppEndedStage:Y},setup(){return{section:g("actual")}}},te={class:"container-fluid g-0"},se={class:"row"},ae={class:"col-12"},ne={class:"tabs"},oe={class:"col-12"};function re(n,t,o,e,i,u){const d=l("AppHeader"),c=l("AppActualStage"),_=l("AppRegisteredStage"),R=l("AppEndedStage");return s(),r(h,null,[x(d,null,{default:S(()=>[k("\u0420\u0435\u0433\u0430\u0442\u044B")]),_:1}),a("main",null,[a("div",te,[a("div",se,[a("div",ae,[a("div",ne,[a("div",{class:A(["tab-item",{"tab-item__active":e.section==="actual"}]),onClick:t[0]||(t[0]=w=>e.section="actual")},"\u0410\u043A\u0442\u0443\u0430\u043B\u044C\u043D\u044B\u0435 \u0440\u0435\u0433\u0430\u0442\u044B",2),a("div",{class:A(["tab-item",{"tab-item__active":e.section==="registered"}]),onClick:t[1]||(t[1]=w=>e.section="registered")},"\u041C\u043E\u0438 \u0440\u0435\u0433\u0430\u0442\u044B",2),a("div",{class:A(["tab-item",{"tab-item__active":e.section==="ended"}]),onClick:t[2]||(t[2]=w=>e.section="ended")},"\u041F\u0440\u043E\u0448\u0435\u0434\u0448\u0438\u0435 \u0440\u0435\u0433\u0430\u0442\u044B",2)])]),a("div",oe,[e.section==="actual"?(s(),p(c,{key:0})):m("",!0),e.section==="registered"?(s(),p(_,{key:1})):m("",!0),e.section==="ended"?(s(),p(R,{key:2})):m("",!0)])])])])],64)}const ge=f(ee,[["render",re]]);export{ge as default};