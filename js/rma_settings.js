jQuery(document).ready(function ($) {
    // $() will work as an alias for jQuery() inside of this function
    authType = $("input:radio[name='rma_auth_type']");
    api = $("input:radio[name='rma_auth_type'][value='API key']");
    basic = $("input:radio[name='rma_auth_type'][value='HTTP Basic']");
    none = $("input:radio[name='rma_auth_type'][value='None']");
    key = $("#rma_auth_type_api_key");
    keyName = $("#rma_auth_type_api_key_field_name");
    username = $("#rma_auth_type_basic_username");
    password = $("#rma_auth_type_basic_password");
    type = $("input:radio[name=rma_auth_type]:checked").val();
    
    //set initial display
    if (type === 'API key') {
        showKey(key, keyName, username, password);
    } else if (type === 'HTTP Basic') {
        showBasic(key, keyName, username, password);
    } else if (type === 'None') {
        showNone(key, keyName, username, password);
    }
        
    //change display as required
    $(authType).change(function () {
        $("input:text[name='rma_user_data_uri']").val('');
        if (this.value === 'API key') {
            showKey(key, keyName, username, password);
    } else if (this.value === 'HTTP Basic') {
        showBasic(key, keyName, username, password);
    } else if (this.value === 'None') {
        showNone(key, keyName, username, password);
    }
    });
    
    function showKey(key, keyName, username, password) {
        key.show();
        key.parents("tr").show();
        keyName.show();
        keyName.parents("tr").show();
        username.hide();
        username.parents("tr").hide();
        password.hide();
        password.parents("tr").hide();
    }
    
    function showBasic(key, keyName, username, password) {
        key.hide();
        key.parents("tr").hide();
        keyName.hide();
        keyName.parents("tr").hide();
        username.show();
        username.parents("tr").show();
        password.show();
        password.parents("tr").show();
    }
    
    function showNone(key, keyName, username, password) {
        key.hide();
        key.parents("tr").hide();
        keyName.hide();
        keyName.parents("tr").hide();
        username.hide();
        username.parents("tr").hide();
        password.hide();
        password.parents("tr").hide();
    }
});
