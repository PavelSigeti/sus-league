import{A as u,b as A,a as F}from"./AppForgetPassword.f87ab5a9.js";import{_ as k}from"./AppLoader.c73a9146.js";import{_ as w,r as g,a as i,o as s,c as b,b as t,d as C,w as R,e as l,f as c,K as d}from"./app.88917cf5.js";const x={name:"AppHomeHeader",components:{AppLoginForm:u,AppRegisterForm:A,AppForgetPassword:F},setup(){const n=g(!1),o=g(!1),a=g(!1);return{login:n,register:o,changeModal:()=>{n.value=!n.value,o.value=!o.value},forget:a,toggleForget:()=>{n.value=!n.value,a.value=!a.value}}}},B={class:"container"},H={class:"row aic"},M={class:"col-6"},S=t("img",{src:k,alt:"vrisc logo",class:"logo"},null,-1),h={class:"col-6 jcfe"};function y(n,o,a,e,p,L){const m=i("router-link"),v=i("AppLoginForm"),f=i("AppForgetPassword"),_=i("AppRegisterForm");return s(),b("header",null,[t("div",B,[t("div",H,[t("div",M,[C(m,{to:"/"},{default:R(()=>[S]),_:1})]),t("div",h,[t("div",{class:"login-btn btn btn-border",onClick:o[0]||(o[0]=r=>e.login=!0)},"\u0412\u0445\u043E\u0434"),t("div",{class:"register-btn btn btn-default",onClick:o[1]||(o[1]=r=>e.register=!0)},"\u0420\u0435\u0433\u0438\u0441\u0442\u0440\u0430\u0446\u0438\u044F")])])]),e.login?(s(),l(v,{key:0,onClose:o[2]||(o[2]=r=>e.login=!1),onSwitchReg:e.changeModal,onForget:o[3]||(o[3]=r=>e.toggleForget())},null,8,["onSwitchReg"])):c("",!0),(s(),l(d,null,[e.forget?(s(),l(f,{key:0,onClose:o[4]||(o[4]=r=>e.forget=!1),onChange:o[5]||(o[5]=r=>e.toggleForget())})):c("",!0)],1024)),(s(),l(d,null,[e.register?(s(),l(_,{key:0,onClose:o[6]||(o[6]=r=>e.register=!1),onSwitchReg:e.changeModal},null,8,["onSwitchReg"])):c("",!0)],1024))])}const K=w(x,[["render",y]]);export{K as A};
