import{_ as b,r as x,x as v,o as e,c as s,F as c,P as _,Q as l,f,b as t,g as y}from"./app.d24363b2.js";const A={name:"AppResultTable",props:["id"],setup(p){const d=x(),i={default:"\u0413\u043E\u043D\u043A\u0430",group:"\u0413\u0440\u0443\u043F\u043F\u0430",fleet:"\u0424\u043B\u043E\u0442"};return v(async()=>{try{const n=await axios.get(`/api/stage/${p.id}`);d.value=n.data}catch(n){console.log(n.message)}}),{results:d,statusTitle:i,printValue:(n,h,r)=>{var a;return n.null?"\u2014":n.drop?n.place===r+1?`(dnf, ${r+1})`:`(${n.place})`:(a=n.place)!=null?a:h}}}},V={class:"result-tables"},B={key:0},N={class:"result-table"},F=t("th",null,"#",-1),R=t("th",null,"\u042F\u0445\u0442\u0441\u043C\u0435\u043D",-1),U=t("th",null,"\u0418\u0442\u043E\u0433",-1),j={class:"result-table__name"},w={class:"result-table__nick"};function C(p,d,i,o,n,h){return e(),s("div",V,[(e(!0),s(c,null,_(o.results,(r,a)=>(e(),s("div",{class:"result-table__container",key:a},[(e(!0),s(c,null,_(r,(m,$,k)=>(e(),s("div",{class:"result-table__item",key:$},[o.statusTitle[a]!=="\u0413\u043E\u043D\u043A\u0430"?(e(),s("h3",B,l(o.statusTitle[a])+" #"+l(k+1),1)):f("",!0),t("table",N,[t("thead",null,[t("tr",null,[F,R,(e(!0),s(c,null,_(Object.keys(m[0]).length-1,u=>(e(),s("th",null," #"+l(u),1))),256)),U])]),t("tbody",null,[(e(!0),s(c,null,_(m,(u,T)=>(e(),s("tr",null,[t("td",null,l(T+1),1),t("td",null,[t("div",j,[y(l(u[0].name)+" "+l(u[0].surname)+" ",1),t("span",w,l(u[0].nickname),1)])]),(e(!0),s(c,null,_(u,g=>(e(),s("td",null,l(o.printValue(g,u.sum,m.length)),1))),256))]))),256))])])]))),128))]))),128))])}const S=b(A,[["render",C]]),D={name:"AppUsersTables",props:{users:{type:Array,default:null}}},E={key:0,class:"result-table"},L=t("thead",null,[t("tr",null,[t("th",null,"#"),t("th",null,"\u042F\u0445\u0442\u0441\u043C\u0435\u043D")])],-1),M={class:"result-table__name"},O={class:"result-table__nick"};function P(p,d,i,o,n,h){return i.users?(e(),s("table",E,[L,t("tbody",null,[(e(!0),s(c,null,_(i.users,(r,a)=>(e(),s("tr",{key:a},[t("td",null,l(a+1),1),t("td",null,[t("div",M,[y(l(r.name)+" "+l(r.surname)+" ",1),t("span",O,l(r.nickname),1)])])]))),128))])])):f("",!0)}const q=b(D,[["render",P]]);export{S as A,q as a};
