import{_ as S,r as g,t as T,o as t,c as e,F as r,P as c,Q as a,f as y,b as l,p as w,g as B}from"./app.88917cf5.js";const d=_=>(w("data-v-8a7f80d2"),_=_(),B(),_),R={class:"result-tables"},V={key:0},F={class:"result-table"},N=d(()=>l("th",null,"#",-1)),$=d(()=>l("th",null,"\u041A\u043E\u043C\u0430\u043D\u0434\u0430",-1)),j=d(()=>l("th",null,"\u0421\u043E\u0441\u0442\u0430\u0432",-1)),C=d(()=>l("th",null,"\u0418\u0442\u043E\u0433",-1)),D={class:"result-table__name"},E={class:"result-table__name"},L={key:0,class:"result-table__second"},M={__name:"AppResultTable",props:["id"],setup(_){const h=_,m=g(),v={default:"\u0413\u043E\u043D\u043A\u0430",group:"\u0413\u0440\u0443\u043F\u043F\u0430",fleet:"\u0424\u043B\u043E\u0442"},i=g(null);T(async()=>{try{const s=await axios.get(`/api/stage/${h.id}`);m.value=s.data}catch(s){console.log(s.message)}try{const s=await axios.get(`/api/team/users/${h.id}`);s.data.result&&(i.value=s.data.teams)}catch(s){console.log(s.message)}});const k=(s,b,f)=>{var o;return s.null?"\u2014":s.note?s.note:(o=s.place)!=null?o:b};return(s,b)=>(t(),e("div",R,[(t(!0),e(r,null,c(m.value,(f,o)=>(t(),e("div",{class:"result-table__container",key:o},[(t(!0),e(r,null,c(f,(p,x,A)=>(t(),e("div",{class:"result-table__item",key:x},[v[o]!=="\u0413\u043E\u043D\u043A\u0430"?(t(),e("h3",V,a(v[o])+" #"+a(A+1),1)):y("",!0),l("table",F,[l("thead",null,[l("tr",null,[N,$,j,(t(!0),e(r,null,c(Object.keys(p[0]).length-1,u=>(t(),e("th",null," #"+a(u),1))),256)),C])]),l("tbody",null,[(t(!0),e(r,null,c(p,(u,I)=>(t(),e("tr",null,[l("td",null,a(I+1),1),l("td",null,[l("div",D,a(u[0].name),1)]),l("td",null,[l("div",E,[i.value?(t(),e("div",L,[(t(!0),e(r,null,c(i.value.find(n=>n.team_id===u[0].id).users,n=>(t(),e("span",{key:n.id},a(n.surname)+" "+a(n.name)+" "+a(n.patronymic),1))),128))])):y("",!0)])]),(t(!0),e(r,null,c(u,n=>(t(),e("td",null,a(k(n,u.sum,p.length)),1))),256))]))),256))])])]))),128))]))),128))]))}},P=S(M,[["__scopeId","data-v-8a7f80d2"]]);export{P as A};
