var Ajax = false;

function AjaxRequest() {
    Ajax = false;
    if (window.XMLHttpRequest) {
        Ajax = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        try {
            Ajax = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                Ajax = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
        }
    }
}

function resposta(arq, cam) {
    AjaxRequest();
    if (!Ajax) {
        document.write('[Erro na chamada]');
        return;
    }

    Ajax.onreadystatechange = function () {
        if (Ajax.readyState == 4) {
            if (Ajax.status == 200) {
                document.getElementById(cam).innerHTML = Ajax.responseText;
            }
        }
    }

    Ajax.open('GET', arq, true);
    Ajax.send(null);
}

function usaAjax(arquivo, camada) {
    resposta(arquivo, camada);
}
