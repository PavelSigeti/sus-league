import{A as k}from"./AppHeader.ed32a72a.js";import{A as x}from"./AppResultTable.37e65b08.js";import{r as v,i as b,o as s,c as a,b as e,F as h,P as f,Q as l,f as c,_ as $,X as w,d as g,w as T,q as p,g as S,I as A,J as H}from"./app.1ccdcff4.js";import{t as y}from"./time.a44078dd.js";import"./logo.adef3026.js";const I={key:0,class:"result-table"},L=e("thead",null,[e("tr",null,[e("th",null,"#"),e("th",null,"\u041A\u043E\u043C\u0430\u043D\u0434\u0430")])],-1),M={class:"result-table__name"},N={class:"result-table__first"},P={class:"result-table__second"},V={__name:"AppTeamsTabels",props:["id"],setup(d){const t=d,u=v([]);return b(async()=>{const i=await axios.get(`/api/team/users/${t.id}`);i.data.result&&(u.value=i.data.teams)}),(i,m)=>u.value?(s(),a("table",I,[L,e("tbody",null,[(s(!0),a(h,null,f(u.value,(n,o)=>(s(),a("tr",{key:n.team_id},[e("td",null,l(o+1),1),e("td",null,[e("div",M,[e("div",N,l(n.team_name),1),e("div",P,[(s(!0),a(h,null,f(n.users,r=>(s(),a("span",{key:r.id},l(r.surname)+" "+l(r.name)+" "+l(r.patronymic),1))),128))])])])]))),128))])])):c("",!0)}};const B=d=>(A("data-v-dd206157"),d=d(),H(),d),C={class:"container-fluid g-0"},F={class:"row"},R={class:"col-12"},q={key:0,class:"dashboard-item"},D={class:"user-stage__date mb0"},E={key:1,class:"dashboard-item"},J=B(()=>e("h3",null,"\u0414\u043E\u043A\u0443\u043C\u0435\u043D\u0442\u044B",-1)),Q={class:"docs-container"},X=["href"],j={key:0,class:"ri-file-pdf-2-line"},z=["src"],G={class:"docs-title"},K={key:2,class:"dashboard-item"},O=["innerHTML"],U={key:3,class:"dashboard-item"},W=["innerHTML"],Y={key:4,class:"dashboard-item"},Z={key:5,class:"dashboard-item"},ee={__name:"StagePage",setup(d){const t=v({}),i=w().params.id,m=v(""),n=v([]);return b(async()=>{try{const o=await axios.get(`/api/stage/${i}/show-users`);t.value=o.data,m.value=t.value.title}catch(o){console.log(o.message)}try{const o=await axios.get(`/api/stage/${i}/files`);o.data.result&&(n.value=[...o.data.files])}catch(o){console.log(o.message)}}),(o,r)=>(s(),a(h,null,[g(k,null,{default:T(()=>[S(l(m.value),1)]),_:1}),e("main",null,[e("div",C,[e("div",F,[e("div",R,[t.value&&t.value.register_start?(s(),a("div",q,[e("div",D,[e("span",null,"\u041D\u0430\u0447\u0430\u043B\u043E \u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u0438: "+l(p(y)(t.value.register_start)),1),e("span",null,"\u041E\u043A\u043E\u043D\u0447\u0430\u043D\u0438\u0435 \u0440\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u0438: "+l(p(y)(t.value.register_end)),1)])])):c("",!0),n.value.length>0?(s(),a("div",E,[J,e("div",Q,[(s(!0),a(h,null,f(n.value,_=>(s(),a("div",{class:"docs-item",key:_.id},[e("a",{href:"/storage/"+_.path,class:"docs-img",target:"_blank"},[_.path.includes("pdf")?(s(),a("i",j)):(s(),a("img",{key:1,src:"/storage/"+_.path,alt:"doc-img"},null,8,z))],8,X),e("div",G,l(_.title),1)]))),128))])])):c("",!0),t.value&&t.value.participant_text&&t.value.status!=="active"?(s(),a("div",K,[e("div",{class:"content",innerHTML:t.value.participant_text},null,8,O)])):c("",!0),t.value&&t.value.description?(s(),a("div",U,[e("div",{class:"content",innerHTML:t.value.description},null,8,W)])):c("",!0),t.value&&t.value.status==="active"?(s(),a("div",Y,[g(V,{id:p(i)},null,8,["id"])])):c("",!0),t.value&&t.value.status!=="active"?(s(),a("div",Z,[g(x,{id:p(i)},null,8,["id"])])):c("",!0)])])])])],64))}},ie=$(ee,[["__scopeId","data-v-dd206157"]]);export{ie as default};
