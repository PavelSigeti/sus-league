import{A as g}from"./AppHeader.69c7222b.js";import{_ as y,u as x,r as k,i as C,a as w,o,c as d,d as u,w as p,b as s,F as f,P as I,a4 as S,g as r,Q as l,I as D,J as N}from"./app.935610ff.js";import"./logo.38533404.js";const _=n=>(D("data-v-b1bf9bcc"),n=n(),N(),n),V={class:"container-fluid g-0"},$={class:"row"},B={class:"col-12 mb30"},M=_(()=>s("i",{class:"ri-arrow-left-s-line"},null,-1)),A={class:"col-12"},F=_(()=>s("h2",null,"\u0424\u0430\u0439\u043B\u044B \u043D\u0430 \u043C\u043E\u0434\u0435\u0440\u0430\u0446\u0438\u0438",-1)),E={class:"file-table"},H=S('<div class="file-table__head file-item" data-v-b1bf9bcc><div class="file-item__1" data-v-b1bf9bcc>ID</div><div class="file-item__2" data-v-b1bf9bcc>\u0424\u0430\u0439\u043B</div><div class="file-item__3" data-v-b1bf9bcc>\u041F\u043E\u043B\u044C\u0437\u043E\u0432\u0430\u0442\u0435\u043B\u044C</div><div class="file-item__4" data-v-b1bf9bcc>\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043D\u0438\u0435</div><div class="file-item__5" data-v-b1bf9bcc>\u041E\u0442\u043A\u0430\u0437</div></div>',1),J={class:"file-item__1"},L={class:"file-id"},P={class:"file-item__2"},Q=["href"],T={key:0,class:"ri-file-pdf-2-line"},j=["src"],q={class:"file-item__3"},z={class:"file-user"},G={class:"file-user__top"},K={class:"file-user__bottom"},O={class:"file-item__4"},R=["onClick"],U=_(()=>s("div",{class:"file-item__5"},"\u041E\u0442\u043A\u043B\u043E\u043D\u0435\u043D",-1)),W={class:"file-item__6"},X=["onClick"],Y=_(()=>s("i",{class:"ri-delete-bin-line"},null,-1)),Z={__name:"DocsCancel",setup(n){const v=x(),a=k([]);C(async()=>{const t=await axios.get("/api/admin/docs/cancel");a.value=t.data.files});const b=async t=>{try{if((await axios.post(`/api/admin/docs/${t}/approve`)).data.result===!0){const c=a.value.findIndex(e=>e.id===t);a.value.splice(c,1)}}catch(i){console.log(i.message),v.dispatch("notification/displayMessage",{value:"\u041E\u0448\u0438\u0431\u043A\u0430 \u043F\u0440\u0438 \u043F\u043E\u0434\u0442\u0432\u0435\u0440\u0436\u0434\u0435\u043D\u0438\u0438 \u0444\u0430\u0439\u043B\u0430",type:"error"})}},m=async t=>{try{if((await axios.delete(`/api/admin/docs/${t}/delete`)).data.result===!0){const c=a.value.findIndex(e=>e.id===t);a.value.splice(c,1)}}catch(i){console.log(i.message),v.dispatch("notification/displayMessage",{value:"\u041E\u0448\u0438\u0431\u043A\u0430 \u043F\u0440\u0438 \u0443\u0434\u0430\u043B\u0435\u043D\u0438\u0438 \u0444\u0430\u0439\u043B\u0430",type:"error"})}};return(t,i)=>{const c=w("router-link");return o(),d(f,null,[u(g,null,{default:p(()=>[r("\u0414\u043E\u043A\u0443\u043C\u0435\u043D\u0442\u044B (\u043E\u0442\u043C\u0435\u043D\u0430)")]),_:1}),s("main",null,[s("div",V,[s("div",$,[s("div",B,[u(c,{class:"btn btn-default btn-settings",to:{name:"admin.docs"}},{default:p(()=>[M,r(" \u041D\u0430\u0437\u0430\u0434")]),_:1},8,["to"])]),s("div",A,[F,s("div",E,[H,(o(!0),d(f,null,I(a.value,e=>(o(),d("div",{class:"file-item",key:e.id},[s("div",J,[s("div",L,l(e.id),1)]),s("div",P,[s("a",{href:"/storage/"+e.path,target:"_blank"},[e.path.includes("pdf")?(o(),d("i",T)):(o(),d("img",{key:1,src:"/storage/"+e.path,alt:"img"},null,8,j))],8,Q)]),s("div",q,[s("div",z,[s("div",G,l(e.user.surname)+" "+l(e.user.name),1),s("div",K,l(e.user.email),1)])]),s("div",O,[s("div",{class:"btn btn-default btn-settings",onClick:h=>b(e.id)},"\u041F\u043E\u0434\u0442\u0432\u0435\u0440\u0434\u0438\u0442\u044C",8,R)]),U,s("div",W,[s("div",{class:"remove-file",onClick:h=>m(e.id)},[Y,r(" \u0423\u0434\u0430\u043B\u0438\u0442\u044C")],8,X)])]))),128))])])])])])],64)}}},as=y(Z,[["__scopeId","data-v-b1bf9bcc"]]);export{as as default};