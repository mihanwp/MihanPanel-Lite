let form = document.querySelector('form'),
    formEl = document.createElement('form'),
    pageActionKey = 'resend_activation_email',
    usernameFieldGroup = document.createElement('p'),
    buttonFieldGroup = document.createElement('p'),
    locationUrl = location.origin + location.pathname,
    pageUrl = locationUrl + '?action=resend_activation_email',
    linkNav = document.getElementById('nav'),
    link;

const urlParams = new URLSearchParams(window.location.search);
class mwpLoginMessage{
    add(message, type = null){
        this.remove();
        let msgEl = document.createElement('p');
        if(type === 'error'){
            msgEl.id = 'login_error';
        } else {
            msgEl.id = 'msg';
        }
        msgEl.classList.add('message');
        msgEl.innerHTML = message;
        document.querySelector('form').insertAdjacentElement('beforebegin', msgEl);
    }

    remove(){
        let msg = document.querySelector('.message');
        if(msg){
            msg.parentNode.removeChild(msg);
        }
    }
}

if(urlParams.has('action')){
    if(urlParams.get('action') === pageActionKey && form){
        document.title = mwp_rea.translate.title;

        formEl.action = pageUrl;
        formEl.id = 'resend-activation-email-form';
        formEl.method = 'post';
        formEl.style.borderWidth = '0';

        let label = document.createElement('label'),
            input = document.createElement('input');
        label.setAttribute('for', 'username');
        label.innerText = mwp_rea.translate.username_label;
        input.type = 'text';
        input.name = input.id = 'username';
        input.classList = 'input';
        usernameFieldGroup.appendChild(label);
        usernameFieldGroup.appendChild(input);

        let button = document.createElement('input');
        button.type = 'submit';
        button.name = button.id = 'wp-submit';
        button.classList = 'button button-primary button-large';
        button.value = mwp_rea.translate.send_activation_link;
        buttonFieldGroup.appendChild(button);

        formEl.appendChild(usernameFieldGroup);
        formEl.appendChild(buttonFieldGroup);
        form.insertAdjacentElement('afterend', formEl);
        form.remove();

        formEl.addEventListener('submit',  function(e){
            e.preventDefault();
            let usernameVal = document.getElementById('username').value,
                message = new mwpLoginMessage();
            message.remove();
            if(usernameVal){
                jQuery.ajax({
                    url: mwp_rea.au,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: "mwp_resend_user_account_activation_email",
                        username: usernameVal
                    },
                    beforeSend: function(){
                        formEl.classList.add('is-doing');
                    },
                    complete: function(res){
                        res = res.responseJSON;
                        let success = typeof res.success !== "undefined" && res.success === true;
                        formEl.classList.remove('is-doing');
                        if(typeof res !== "undefined"){
                            if(typeof res.data !== "undefined"){
                                if(typeof res.data.msg !== "undefined"){
                                    if(success){
                                        message.add(res.data.msg);
                                    } else {
                                        message.add(res.data.msg, 'error');
                                    }
                                }
                            }
                            if(success){
                                formEl.reset();
                            }
                        }
                    },
                    error: function(){
                        message.remove();
                        formEl.classList.remove('is-doing');
                    }
                })
            }
        });
    }
}

if(!urlParams.has('action') || (urlParams.has('action') && urlParams.get('action') !== pageActionKey)){
    if(linkNav){
        link = document.createElement('a');
        link.href = pageUrl;
        link.id = 'resend-activation-email-btn';
        link.innerText = mwp_rea.translate.resend_activation_link;
        linkNav.appendChild(link);
    }
} else {
    if(linkNav){
        link = document.createElement('a');
        link.href = locationUrl;
        link.id = 'login-page-btn';
        link.innerText = mwp_rea.translate.login;
        linkNav.insertBefore(link, linkNav.firstChild);
    }
}