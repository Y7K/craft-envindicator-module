
document.addEventListener('DOMContentLoaded', (event) => {

    const labelElement = document.createElement('div');
    labelElement.className = `envindicator envindicator-front envindicator-${window.appEnv}`;
    labelElement.innerHTML = `${window.envText} <div id="envindicator-close"><span></span><span></span></div>`;
    document.body.appendChild(labelElement);

    const close = document.getElementById('envindicator-close');

    close.addEventListener('click', () => {
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.open('GET', '/actions/environment-indicator-module/frontend-controller/hide-label', true);
        xmlhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xmlhttp.setRequestHeader('Accept', 'application/json');
        xmlhttp.send();
        labelElement.parentNode.removeChild(labelElement);
    });

});

