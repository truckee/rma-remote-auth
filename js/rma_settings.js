jQuery(document).ready(function ($) {
    // $() will work as an alias for jQuery() inside of this function
    var authType = $("input:radio[name='rma_auth_type']");
//    var api = $("input:radio[name='rma_auth_type'][value='API key']");
//    var basic = $("input:radio[name='rma_auth_type'][value='HTTP Basic']");
//    var none = $("input:radio[name='rma_auth_type'][value='None']");
    var key = $("#rma_auth_type_api_key");
    var keyName = $("#rma_auth_type_api_key_field_name");
    var username = $("#rma_auth_type_basic_username");
    var password = $("#rma_auth_type_basic_password");
    var type = $("input:radio[name=rma_auth_type]:checked").val();
    var userValue = $("#rma_user_data_uri").val();
    var passwordValue = $("#rma_user_password_uri").val();
    var forgotValue = $("#rma_forgot_password_uri").val();
    
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
        $("input:text[name='rma_user_password_uri']").val('');
        $("input:text[name='rma_forgot_password_uri']").val('');
        resetValues();
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
    
    function resetValues() {
        if (type === $("input:radio[name=rma_auth_type]:checked").val()) {
            $("#rma_user_data_uri").val(userValue);
            $("#rma_user_password_uri").val(passwordValue);
            $("#rma_forgot_password_uri").val(forgotValue);
        }
    }
});
