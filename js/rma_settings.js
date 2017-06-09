jQuery(document).ready(function ($) {
    // $() will work as an alias for jQuery() inside of this function
    var authType = $("input:radio[name='rmaAuthType']");
    var authTypeValue= $("input:radio[name=rmaAuthType]:checked").val();

    var key = $("#rmaApiKey");
    var keyName = $("#rmaKeyFieldName");

    var userName = $("#rmaBasicUsername");
    var password = $("#rmaBasicPassword");

    var dataURI = $("#rmaDataURI");
    var dataURIValue = dataURI.val();

    var setPassword = $("#rmaSetPasswordURI");
    var setPasswordValue = $("#rmaSetPasswordURI").val();

    var get = $("input:checkbox[name=rmaOnlyGet]");
    var getValue = get.is(":checked");

    var membersGet = $("#rmaAllMembersURI");
    var membersGetValue = membersGet.val();

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
        dataURI.parents("tr").hide();
        membersGet.parents("tr").show();
    } else {
        setPassword.parents("tr").show();
        dataURI.parents("tr").show();
        membersGet.parents("tr").hide();
    }

    $(get).change(function () {
        if ($(this).is(":checked") === true) {
            setPassword.parents("tr").hide();
            dataURI.parents("tr").hide();
            membersGet.parents("tr").show();
        } else {
            setPassword.parents("tr").show();
            dataURI.parents("tr").show();
            membersGet.parents("tr").hide();
        }
    });

    //change display as required
    $(authType).change(function () {
        dataURI.val('');
        setPassword.val('');
        membersGet.val('');
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
        key.parents("tr").show();
        keyName.parents("tr").show();
        userName.parents("tr").hide();
        password.parents("tr").hide();
    }

    function showBasic(key, keyName, userName, password) {
        key.parents("tr").hide();
        keyName.parents("tr").hide();
        userName.parents("tr").show();
        password.parents("tr").show();
    }

    function showNone(key, keyName, userName, password) {
        key.parents("tr").hide();
        keyName.parents("tr").hide();
        userName.parents("tr").hide();
        password.parents("tr").hide();
    }

    function resetValues() {
        if (authTypeValue === $("input:radio[name=rmaAuthType]:checked").val()) {
            dataURI.val(dataURIValue);
            setPassword.val(setPasswordValue);
            membersGet.val(membersGetValue);
        }
    }
});
