jQuery(document).ready(function ($) {
    // $() will work as an alias for jQuery() inside of this function
    var authType = $("input:radio[name='rma_auth_type']");
    var authTypeValue= $("input:radio[name=rma_auth_type]:checked").val();

    var key = $("#rma_auth_type_api_key");
    var keyValue = key.val();

    var keyName = $("#rma_auth_type_api_key_field_name");
    var keyNameValue = keyName.val();

    var userName = $("#rma_auth_type_basic_username");
    var userNameValue = userName.val();

    var password = $("#rma_auth_type_basic_password");
    var passwordValue = password.val();

    var dataURI = $("#rma_user_data_uri");
    var dataURIValue = dataURI.val();

    var setPassword = $("#rma_set_password_uri");
    var setPasswordValue = $("#rma_set_password_uri").val();

    var get = $("input:checkbox[name=rma_user_get_only]");
    var getValue = get.is(":checked");

    //set initial display
    if (authTypeValue=== 'API key') {
        showKey(key, keyName, userName, password);
    } else if (authTypeValue=== 'HTTP Basic') {
        showBasic(key, keyName, userName, password);
    } else if (authTypeValue=== 'None') {
        showNone(key, keyName, userName, password);
    }

    if (getValue === true) {
        setPassword.parents("tr").hide();
    } else {
        setPassword.parents("tr").show();
    }

    $(get).change(function () {
        if ($(this).is(":checked") === true) {
            setPassword.parents("tr").hide();
        } else {
            setPassword.parents("tr").show();
        }
    });

    //change display as required
    $(authType).change(function () {
        $("input:text[name='rma_user_data_uri']").val('');
        $("input:text[name='rma_set_password_uri']").val('');
        $("input:text[name='rma_reset_password_uri']").val('');
        resetValues();
        if (this.value === 'API key') {
            showKey(key, keyName, userName, password);
        } else if (this.value === 'HTTP Basic') {
            showBasic(key, keyName, userName, password);
        } else if (this.value === 'None') {
            showNone(key, keyName, userName, password);
        }
    });

    function showKey(key, keyName, userName, password) {
        key.show();
        key.parents("tr").show();
        keyName.show();
        keyName.parents("tr").show();
        userName.hide();
        userName.parents("tr").hide();
        password.hide();
        password.parents("tr").hide();
    }

    function showBasic(key, keyName, userName, password) {
        key.hide();
        key.parents("tr").hide();
        keyName.hide();
        keyName.parents("tr").hide();
        userName.show();
        userName.parents("tr").show();
        password.show();
        password.parents("tr").show();
    }

    function showNone(key, keyName, userName, password) {
        key.hide();
        key.parents("tr").hide();
        keyName.hide();
        keyName.parents("tr").hide();
        userName.hide();
        userName.parents("tr").hide();
        password.hide();
        password.parents("tr").hide();
    }

    function resetValues() {
        if (authTypeValue === $("input:radio[name=rma_auth_type]:checked").val()) {
            dataURI.val(dataURIValue);
            setPassword.val(setPasswordValue);
        }
    }
});
