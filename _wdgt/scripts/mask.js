/*
 * Name: Criardor de Mascara JavaScript
 *   By: 23/04/2016 Jeterson Lordano.
 */

// @var mask: Chama ativa mascara no html com onkeyup
function mask(o, f) {
    v_obj = o
    v_fun = f
    setTimeout("execmascara()", 1)
}

// @var execmascara: Executa funções da mascara.
function execmascara() {
    v_obj.value = v_fun(v_obj.value)
}

// @var _tel: Mascara de telefone
function _tel(v) {
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v = v.replace(/(\d)(\d{4})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

// @var _cep: Mascara de CEP
function _cep(v) {
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/(\d)(\d{3})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

// @var _cpf: Mascara de CPF
function _cpf(v) {
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/(\d)(\d{8})$/, "$1.$2"); //coloca ponto 
    v = v.replace(/(\d)(\d{5})$/, "$1.$2"); //coloca ponto 
    v = v.replace(/(\d)(\d{2})$/, "$1-$2"); //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

// @var _rg: Mascara de RG
function _rg(v) {
    v = v.replace(/\D/g, ""); //Remove tudo o que não é dígito
    v = v.replace(/(\d{2})(\d{3})(\d{3})(\d{1})$/, "$1.$2.$3-$4"); //Coloca ponto e traço
    return v;
}

// @var _date: Mascara de DATA
function _date(v) {
    v = v.replace(/\D/g, "");
    v = v.replace(/(\d)(\d{6})$/, "$1/$2");
    v = v.replace(/(\d)(\d{4})$/, "$1/$2");
    return v;
}

// @var cnpj: Mascara de cnpj 27.643.621/0001-40
function _cnpj(v) {
    v = v.replace(/\D/g, "");
    //v=v.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,"$1.$2.$3/$4-$5");
    v = v.replace(/(\d{2})(\d{3})/, "$1.$2");
    v = v.replace(/(\d{3})(\d{3})/, "$1.$2");
    v = v.replace(/(\d{3})(\d{4})(\d{2})$/, "$1/$2-$3");
    return v;
}
