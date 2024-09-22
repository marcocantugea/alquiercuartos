const apiHost="http://localhost/src/api/apicontrolcuartos/public/";
const apiPath="api/v1/";

const headersRequest=new Headers({
    'Authorization': 'Basic '+sessionStorage.getItem('token'), 
});