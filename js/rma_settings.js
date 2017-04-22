jQuery(document).ready(function ($) {
    // $() will work as an alias for jQuery() inside of this function
    authType = $("input[type='radio'][name=rma_auth_type]");
    basic = $("input[name=rma_auth_type][value='HTTP Basio']");
    api = $("input[name=rma_auth_type][value='API key']");
    key = $("#rma_auth_type_api_key");
    username = $("#rma_auth_type_basic_username");
    password = $("#rma_auth_type_basic_password");
    authType.prop('checked', false);
    basic.prop('checked', false);
    key.hide();
    key.parents("tr").hide();
    username.hide();
    username.parents("tr").hide();
    password.hide();
    password.parents("tr").hide();
    
    
    $(authType).change(function () {
        if (this.value === 'API key') {
        key.show();
        key.parents("tr").show();
        username.hide();
        username.parents("tr").hide();
        password.hide();
        password.parents("tr").hide();
    } else {
        key.hide();
        key.parents("tr").hide();
        username.show();
        username.parents("tr").show();
        password.show();
        password.parents("tr").show();
    }
    });
});
