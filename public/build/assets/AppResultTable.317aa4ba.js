import{_ as I,r as k,i as A,o as e,c as t,F as u,P as r,Q as n,f as S,b as l,I as T,J as B}from"./app.6c603b3d.js";const i=o=>(T("data-v-5ccce37c"),o=o(),B(),o),R={class:"result-tables"},V={key:0},$={class:"result-table"},w=i(()=>l("th",null,"#",-1)),F=i(()=>l("th",null,"\u041A\u043E\u043C\u0430\u043D\u0434\u0430",-1)),N=i(()=>l("th",null,"\u0418\u0442\u043E\u0433",-1)),j={class:"result-table__name"},C={__name:"AppResultTable",props:["id"],setup(o){const b=o,p=k(),h={default:"\u0413\u043E\u043D\u043A\u0430",group:"\u0413\u0440\u0443\u043F\u043F\u0430",fleet:"\u0424\u043B\u043E\u0442"};A(async()=>{try{const s=await axios.get(`/api/stage/${b.id}`);p.value=s.data}catch(s){console.log(s.message)}});const f=(s,m,_)=>{var a;return s.null?"\u2014":s.drop?s.place===_+1?`(dnf, ${_+1})`:`(${s.place})`:(a=s.place)!=null?a:m};return(s,m)=>(e(),t("div",R,[(e(!0),t(u,null,r(p.value,(_,a)=>(e(),t("div",{class:"result-table__container",key:a},[(e(!0),t(u,null,r(_,(d,v,y)=>(e(),t("div",{class:"result-table__item",key:v},[h[a]!=="\u0413\u043E\u043D\u043A\u0430"?(e(),t("h3",V,n(h[a])+" #"+n(y+1),1)):S("",!0),l("table",$,[l("thead",null,[l("tr",null,[w,F,(e(!0),t(u,null,r(Object.keys(d[0]).length-1,c=>(e(),t("th",null," #"+n(c),1))),256)),N])]),l("tbody",null,[(e(!0),t(u,null,r(d,(c,g)=>(e(),t("tr",null,[l("td",null,n(g+1),1),l("td",null,[l("div",j,n(c[0].name)+" "+n(c[0].surname),1)]),(e(!0),t(u,null,r(c,x=>(e(),t("td",null,n(f(x,c.sum,d.length)),1))),256))]))),256))])])]))),128))]))),128))]))}},E=I(C,[["__scopeId","data-v-5ccce37c"]]);export{E as A};
