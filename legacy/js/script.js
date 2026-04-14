function cancel_scheduled() {

    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var sms_ids = get_items('scheduled');
    if (sms_ids.length > 0) {
        if (confirm("Are you sure you want to cancel selected messages")) {
            var phpurl = "cancel_scheduled.php?sms_ids=" + sms_ids;
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    get_scheduled(start_row, per_page);
                }
            }
            xmlhttp.open("GET", phpurl, false);
            xmlhttp.send();
        }
    }
}


function delete_sms_order(order_id) {
    if (confirm("Are you sure you want to delete this order?")) {
        var phpurl = "delete_sms_order.php?order_id=" + order_id;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;

                get_sales(start_row, per_page);
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();
    }
}


function delete_reseller(reseller_id) {
    if (confirm("Are you sure you want to delete this reseller?")) {
        document.location.href = "delete_reseller.php?reseller_id=" + reseller_id;
    }
}

function delete_agent(agent_id) {
    if (confirm("Are you sure you want to delete this agent?")) {
        document.location.href = "delete_agent.php?agent_id=" + agent_id;
    }
}

function save_transfer() {
    var reseller_id = document.getElementById('transfer_reseller_id').value;

    var errors = 0;
    document.getElementById('transfer_form_errors').innerHTML = "";


    if (reseller_id.length == 0) {
        document.getElementById('transfer_form_errors').innerHTML += "<div> - You must enter client name</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('transfer_form').submit();
    }
}


function transfer_client(client_id) {
    var phpurl = "transfer_client_modal.php?client_id=" + client_id;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('transfer_client_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('transfer_client').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function delete_client(client_id) {
    if (confirm("Are you sure you want to delete this client?")) {
        document.location.href = "delete_client.php?client_id=" + client_id;
    }
}

function save_agent() {
    var agent_name = document.getElementById('agent_name').value;
    var email = document.getElementById('email').value;
    var phone_number = document.getElementById('phone_number').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var new_password = document.getElementById('new_password').value;
    var confirm_password = document.getElementById('confirm_password').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (agent_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter agent name</div>";
        errors += 1;
    }


    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!email.match(mailformat)) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
        errors += 1;
    }

    if (scheme_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }


    if (new_password.length < 6) {
        document.getElementById('form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_form').submit();
    }
}


function save_reseller() {
    var business_name = document.getElementById('business_name').value;
    var email = document.getElementById('email').value;
    var phone_number = document.getElementById('phone_number').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var new_password = document.getElementById('new_password').value;
    var confirm_password = document.getElementById('confirm_password').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (business_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter reseller name</div>";
        errors += 1;
    }


    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (!email.match(mailformat)) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
        errors += 1;
    }

    if (scheme_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }


    if (new_password.length < 6) {
        document.getElementById('form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_form').submit();
    }
}



function update_reseller() {
    var business_name = document.getElementById('business_name').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var phone_number = document.getElementById('phone_number').value;
    var email = document.getElementById('email').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";



    if (phone_number.length < 12) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (business_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter reseller name</div>";
        errors += 1;
    }

    if (email.length != 0) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(mailformat)) {
            document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
            errors += 1;
        }
    }

    if (scheme_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_form').submit();
    }
}


function update_agent() {
    var agent_name = document.getElementById('agent_name').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var phone_number = document.getElementById('phone_number').value;
    var email = document.getElementById('email').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";



    if (phone_number.length < 12) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (agent_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter agent name</div>";
        errors += 1;
    }

    if (email.length != 0) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(mailformat)) {
            document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
            errors += 1;
        }
    }

    if (scheme_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('agent_form').submit();
    }
}

function edit_reseller(reseller_id) {
    var phpurl = "edit_reseller_modal.php?reseller_id=" + reseller_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_reseller_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_reseller').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function edit_agent(agent_id) {
    var phpurl = "edit_agent_modal.php?agent_id=" + agent_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_agent_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_agent').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function get_agents(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_agents.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function get_resellers(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_resellers.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function update_sender() {
    var id_type = document.getElementById('id_type').value;
    var id_status = document.getElementById('id_status').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";



    if (id_type.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select ID type</div>";
        errors += 1;
    }

    if (id_status.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select ID status</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('sender_form').submit();
    }
}

function edit_sender(id) {
    var phpurl = "edit_sender_modal.php?id=" + id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_sender_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_sender').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function save_pricing(scheme_id) {

    var min_sms = document.getElementById('min_sms').value;
    var max_sms = document.getElementById('max_sms').value;
    var price = document.getElementById('price').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (min_sms.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter start quantity</div>";
        errors += 1;
    }


    if (max_sms.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter end quantity</div>";
        errors += 1;
    }

    if (price.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter price</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_pricing.php?scheme_id=" + scheme_id + "&min_sms=" + min_sms + "&max_sms=" + max_sms + "&price=" + price;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //    document.getElementById('edit_scheme').click();
                edit_scheme(scheme_id);
            }
        }
        xmlhttp.open("GET", phpurl, false); edit_scheme(scheme_id);
        xmlhttp.send();
    }
}


function delete_pricing(pricing_id, scheme_id) {

    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var phpurl = "delete_pricing.php?scheme_id=" + scheme_id + "&pricing_id=" + pricing_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_scheme').click();
            edit_scheme(scheme_id);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function edit_scheme(scheme_id) {
    var phpurl = "edit_scheme_modal.php?scheme_id=" + scheme_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_scheme_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_scheme').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function delete_scheme(scheme_id) {

    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var phpurl = "delete_scheme.php?scheme_id=" + scheme_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            get_schemes(start_row, per_page);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function save_scheme(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;
    var scheme_name = document.getElementById('scheme_name').value;

    var account_type = document.getElementById('account_type').value;


    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (scheme_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter scheme name</div>";
        errors += 1;
    }

    if (account_type.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select account type</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_scheme.php?scheme_name=" + scheme_name + "&account_type=" + account_type;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_schemes(1, per_page);
                document.getElementById('create_scheme').click();
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();


    }
}



function get_schemes(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_schemes.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function save_payment(order_id) {
    var receipt_number = document.getElementById('receipt_number').value;
    var payment_method = document.getElementById('payment_method').value;
    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";


    if (receipt_number.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter receipt number</div>";
        errors += 1;
    }

    if (payment_method.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select payment method</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_payment.php?order_id=" + order_id + "&receipt_number=" + receipt_number + "&payment_method=" + payment_method;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                get_sales(start_row, per_page);
                document.getElementById('allocate_credit').click();
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();
    }
}


function allocate_credit(order_id) {
    var phpurl = "allocate_credit_modal.php?order_id=" + order_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('allocate_credit_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('allocate_credit').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}



function update_client(client_id) {
    var client_name = document.getElementById('client_name').value;
    var contact_phone = document.getElementById('contact_phone').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var username = document.getElementById('username').value;
    var errors = 0;
    document.getElementById('edit_form_errors').innerHTML = "";


    if (client_name.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter client name</div>";
        errors += 1;
    }

    if (contact_phone.length != 12) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (scheme_id.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }

    if (username.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter username</div>";
        errors += 1;
    }

    var available = check_update_username(username, client_id);

    if (available == false) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - Username already taken</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_form').submit();
    } else {
        document.getElementById('edit_form_errors').scrollIntoView();
    }
}


function edit_client(client_id) {
    var phpurl = "edit_client_modal.php?client_id=" + client_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_client_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_client').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function save_client() {
    var client_name = document.getElementById('client_name').value;
    var phone_number = document.getElementById('phone_number').value;
    var scheme_id = document.getElementById('scheme_id').value;
    var username = document.getElementById('username').value;
    var new_password = document.getElementById('new_password').value;
    var confirm_password = document.getElementById('confirm_password').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";



    if (client_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter client name</div>";
        errors += 1;
    }

    if (phone_number.length != 12) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (scheme_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select pricing scheme</div>";
        errors += 1;
    }

    if (username.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter username</div>";
        errors += 1;
    }

    var available = check_username(username);

    if (available == false) {
        document.getElementById('form_errors').innerHTML += "<div> - Username already taken</div>";
        errors += 1;
    }

    if (new_password.length < 6) {
        document.getElementById('form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_form').submit();
    } else {
        document.getElementById('form_errors').scrollIntoView();
    }
}

function check_username(username) {
    var available = false;

    var phpurl = "check_username.php?username=" + username;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == "Available") {
                available = true;
            } else {
                available = false;
            }
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

    return available;
}


function check_update_username(username, client_id) {
    var available = false;

    var phpurl = "check_update_username.php?username=" + username + "&client_id=" + client_id;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if (xmlhttp.responseText == "Available") {
                available = true;
            } else {
                available = false;
            }
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

    return available;
}

function client_change_password() {
    var new_password = document.getElementById('client_new_password').value;
    var confirm_password = document.getElementById('client_confirm_password').value;

    var errors = 0;
    document.getElementById('password_form_errors').innerHTML = "";

    if (new_password.length < 6) {
        document.getElementById('password_form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('password_form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('client_password_form').submit();
    }
}

function agent_change_password() {
    var new_password = document.getElementById('agent_new_password').value;
    var confirm_password = document.getElementById('agent_confirm_password').value;

    var errors = 0;
    document.getElementById('password_form_errors').innerHTML = "";

    if (new_password.length < 6) {
        document.getElementById('password_form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('password_form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }

    if (errors == 0) {
        document.getElementById('agent_password_form').submit();
    }
}


function get_sales(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_sales.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function place_order() {
    var quantity = +document.getElementById('quantity').value;
    if (quantity > 0) {
        document.location.href = "place_order.php?quantity=" + quantity;
    } else {
        document.getElementById('qty_error').innerHTML = "You must enter quantity";
        return false;
    }
}

function get_clients(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_clients.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function place_order() {
    var quantity = +document.getElementById('quantity').value;
    if (quantity > 0) {
        document.location.href = "place_order.php?quantity=" + quantity;
    } else {
        document.getElementById('qty_error').innerHTML = "You must enter quantity";
        return false;
    }
}

function get_total_cost() {
    var quantity = +document.getElementById('quantity').value;
    var total_cost = 0;
    if (quantity >= 0) {
        var price_array = document.getElementById('price_array').value.split(",");

        for (var i = 0; i < price_array.length; i++) {
            var tier = price_array[i].split('@');

            var pricing_range = tier[0].split('-');

            var min_sms = pricing_range[0];
            var max_sms = pricing_range[1];
            var price = tier[1];
            if (quantity >= min_sms && quantity <= max_sms) {
                total_cost = quantity * price;
            } else {
                if (max_sms == 0 && quantity >= min_sms) {
                    total_cost = quantity * price;
                }
            }
        }

    } else {
        document.getElementById('quantity').value = "";
    }

    var formatter = new Intl.NumberFormat();
    document.getElementById('total_cost').innerHTML = "TSH " + formatter.format(total_cost);
}


function change_password() {
    var current_password = document.getElementById('current_password').value;
    var new_password = document.getElementById('new_password').value;
    var confirm_password = document.getElementById('confirm_password').value;

    var errors = 0;
    document.getElementById('user_form_errors').innerHTML = "";

    if (current_password.length == 0) {
        document.getElementById('user_form_errors').innerHTML += "<div> - You must enter your current password</div>";
        errors += 1;
    }


    if (new_password.length < 6) {
        document.getElementById('user_form_errors').innerHTML += "<div> - Password length must be atleast 6 characters</div>";
        errors += 1;
    }

    if (confirm_password != new_password) {
        document.getElementById('user_form_errors').innerHTML += "<div> - Password did not match</div>";
        errors += 1;
    }
    if (errors == 0) {
        document.getElementById('user_password_form').submit();
    }
}

function delete_senders(sender_id) {
    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var phpurl = "delete_senders.php?sender_id=" + sender_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            get_senders(start_row, per_page);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function update_template(start_row, per_page, template_id) {
    var keyword = document.getElementById('keyword').value;
    var title = document.getElementById('edit_title').value;
    var message = document.getElementById('edit_message').value;

    var errors = 0;
    document.getElementById('edit_form_errors').innerHTML = "";

    if (title.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a title</div>";
        errors += 1;
    }

    if (message.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter message</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "update_template.php?title=" + title + "&message=" + message + "&template_id=" + template_id;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_templates(start_row, per_page);
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();

        document.getElementById('edit_template').click();
    }
}


function edit_template(template_id) {
    var phpurl = "edit_template_modal.php?template_id=" + template_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_template_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_template').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function delete_templates(template_id) {
    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var phpurl = "delete_templates.php?template_id=" + template_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            get_templates(start_row, per_page);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function save_template(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;
    var title = document.getElementById('title').value;
    var message = document.getElementById('message').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (title.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a title</div>";
        errors += 1;
    }

    if (message.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter message</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_template.php?title=" + title + "&message=" + message;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_templates(1, per_page);
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();

        document.getElementById('create_template').click();
    }
}


function save_sender(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;
    var client_id = document.getElementById('client_id').value;
    var sender_id = document.getElementById('sender_id').value;
    var message = document.getElementById('message').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (client_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select a Client</div>";
        errors += 1;
    }
    if (sender_id.length == 0 || sender_id.length > 11) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid Sender ID</div>";
        errors += 1;
    }

    if (message.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter sample message</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_sender.php?client_id=" + client_id + "&sender_id=" + sender_id + "&message=" + message;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_senders(1, per_page);
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();

        document.getElementById('create_sender').click();
    }
}



function update_contact(start_row, per_page, contact_id) {
    var group_id = document.getElementById('group_id').value;
    var keyword = document.getElementById('keyword').value;
    var phone_number = document.getElementById('edit_phone_number').value;
    var contact_name = document.getElementById('edit_contact_name').value;
    var email = document.getElementById('edit_email').value;

    var errors = 0;
    document.getElementById('edit_form_errors').innerHTML = "";

    if (phone_number.length != 12) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (contact_name.length == 0) {
        document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter contact name</div>";
        errors += 1;
    }

    if (email.length != 0) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(mailformat)) {
            document.getElementById('edit_form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
            errors += 1;
        }
    }

    if (errors == 0) {
        var phpurl = "update_contact.php?contact_id=" + contact_id + "&group_id=" + group_id + "&phone_number=" + phone_number + "&contact_name=" + contact_name + "&email=" + email;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText == "Duplicate") {
                    document.getElementById('edit_form_errors').innerHTML += "<div> - Contact with this phone number already created</div>";
                } else {
                    var start_row = document.getElementById('start_row').value;
                    var per_page = document.getElementById('per_page').value;
                    get_contacts(start_row, per_page);
                    document.getElementById('edit_contact').click();
                }
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();


    }
}


function edit_contact(contact_id) {
    var phpurl = "edit_contact_modal.php?contact_id=" + contact_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('edit_contact_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('edit_contact').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function bulk_delete_contacts() {
    var contacts = get_items('contacts').split(",");
    var contact_ids = "";

    for (var i = 0; i < (contacts.length - 1); i++) {
        contact_ids += (contacts[i].split("_")[1]) + ",";
    }


    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var group_id = document.getElementById('group_id').value;

    var phpurl = "bulk_delete_contacts.php?contact_ids=" + contact_ids;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            get_contacts(start_row, per_page);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}




function delete_contacts(contact_id, group_id) {
    var start_row = document.getElementById('start_row').value;
    var per_page = document.getElementById('per_page').value;

    var phpurl = "delete_contacts.php?contact_id=" + contact_id + "&group_id=" + group_id;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            get_contacts(start_row, per_page);
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function get_groups_list() {
    var phpurl = "get_groups_list.php";
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('group_id').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function save_group(start_row, per_page) {
    var group_name = document.getElementById('group_name').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";


    if (group_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter group name</div>";
        errors += 1;
    }

    if (errors == 0) {
        var phpurl = "save_group.php?group_name=" + group_name;
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_groups_list();
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();
        document.getElementById('group_name').value = "";
        document.getElementById('create_group').click();
    }
}


function save_contact(start_row, per_page) {
    var group_id = document.getElementById('group_id').value;
    var keyword = document.getElementById('keyword').value;
    var phone_number = document.getElementById('phone_number').value;
    var contact_name = document.getElementById('contact_name').value;
    var email = document.getElementById('email').value;

    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";

    if (phone_number.length != 12) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid phone number</div>";
        errors += 1;
    }

    if (contact_name.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter contact name</div>";
        errors += 1;
    }

    if (email.length != 0) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (!email.match(mailformat)) {
            document.getElementById('form_errors').innerHTML += "<div> - You must enter a valid email address</div>";
            errors += 1;
        }
    }
    if (errors == 0) {
        var phpurl = "save_contact.php?group_id=" + group_id + "&phone_number=" + phone_number + "&contact_name=" + contact_name + "&email=" + email;

        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var start_row = document.getElementById('start_row').value;
                var per_page = document.getElementById('per_page').value;
                get_contacts(1, per_page);
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();

        document.getElementById('create_contact').click();
    }
}

function send_sms() {
    var contacts = document.getElementById('contacts').value;
    var groups = document.getElementById('groups').value;
    var sender_id = document.getElementById('sender_id').value;
    var message = document.getElementById('message').value;
    var schedule = document.getElementById('schedule').value;
    var start_date = document.getElementById('start_date').value;
    var end_date = document.getElementById('end_date').value;
    var send_hour = document.getElementById('send_hour').value;
    var send_minute = document.getElementById('send_minute').value;



    var total_recipients = document.getElementById('total_recipients').innerHTML;
    var errors = 0;
    document.getElementById('form_errors').innerHTML = "";
    if (total_recipients == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter recipients</div>";
        errors += 1;
    }

    if (message.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must enter a message</div>";
        errors += 1;
    }

    if (sender_id.length == 0) {
        document.getElementById('form_errors').innerHTML += "<div> - You must select Sender ID</div>";
        errors += 1;
    }
    if (errors == 0) {
        document.getElementById('composer').submit();
    } else {
        return false;
    }

}

function count_message() {
    var message = document.getElementById('message').value;
    var message_length = message.length;
    var sms_count = Math.ceil(message_length / 160);
    var sms_length = message_length - (160 * sms_count) + 160;
    document.getElementById('message_length').innerHTML = message_length + "/" + sms_count * 160;
    document.getElementById('sms_count').innerHTML = sms_count;
}


function parse_message(message) {
    document.getElementById('message').innerHTML = parseHTML(message);

}

function parseHTML(html) {
    return html;
}


function remove_recipient_contact(this_contact, phone_number) {
    this_contact.parentNode.parentNode.removeChild(this_contact.parentNode);
    var this_number = phone_number + ",";

    document.getElementById('contacts').value = document.getElementById('contacts').value.replace(this_number, "");
    get_total_recipients()
}

function remove_recipient_group(this_group, group_id) {
    this_group.parentNode.parentNode.removeChild(this_group.parentNode);
    var this_group_id = group_id + ",";

    document.getElementById('groups').value = document.getElementById('groups').value.replace(this_group_id, "");
    get_total_recipients()
}



function toggle_all(status, table_name) {
    var fields = document.getElementById(table_name).getElementsByTagName("input");
    for (var i = 0, max = fields.length; i < max; i++) {
        if (fields[i].type === 'checkbox' && fields[i].id.length !== 0) {

            var id = fields[i].id.split("_");
            if (id[0] == "item") {
                if (status == true) {
                    fields[i].checked = true;
                } else {
                    fields[i].checked = false;
                }
            }
        }
    }
}

function get_items(table_name) {
    var items = "";
    var fields = document.getElementById(table_name).getElementsByTagName("input");
    for (var i = 0, max = fields.length; i < max; i++) {
        if (fields[i].type === 'checkbox' && fields[i].id.length !== 0) {
            var id = fields[i].id;
            var item_id = fields[i].id.split("_");
            if (item_id[0] == "item") {
                var ticked = fields[i].checked;
                if (ticked) {
                    items = items + id + ",";
                }
            }
        }
    }
    return items;
}

function insert_templates() {
    var templates = get_items('modal_templates').split(",");

    var template_ids = "";
    for (var i = 0; i < (templates.length - 1); i++) {
        template_ids += (templates[i].split("_")[1]) + ",";
    }

    // document.getElementById('templates').value += template_ids;

    var phpurl = "get_template_text.php?items=" + template_ids;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var message = document.getElementById('message').innerHTML + xmlhttp.responseText;
            document.getElementById('message').innerHTML = message;
            count_message();
            document.getElementById('insert_templates').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function insert_groups() {
    var groups = get_items('modal_groups').split(",");

    var group_ids = "";

    for (var i = 0; i < (groups.length - 1); i++) {
        group_ids += (groups[i].split("_")[1]) + ",";
    }

    document.getElementById('groups').value += group_ids;

    var phpurl = "get_group_names.php?items=" + group_ids;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var recipient_list = xmlhttp.responseText + document.getElementById('recipient_list').innerHTML;
            document.getElementById('recipient_list').innerHTML = recipient_list;
            get_total_recipients();
            document.getElementById('insert_groups').click();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function insert_contacts() {
    var contacts = get_items('modal_contacts').split(",");

    var phone_numbers = "";

    for (var i = 0; i < (contacts.length - 1); i++) {
        phone_numbers += (contacts[i].split("_")[1]) + ",";
    }

    document.getElementById('contacts').value += phone_numbers;

    var phpurl = "get_contact_names.php?items=" + phone_numbers;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var recipient_list = xmlhttp.responseText + document.getElementById('recipient_list').innerHTML;
            document.getElementById('recipient_list').innerHTML = recipient_list;
            document.getElementById('insert_contacts').click();

            get_total_recipients();
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function get_contacts_modal(start_row, per_page) {

    var phpurl = "get_contacts_modal.php?start_row=" + start_row + "&per_page=" + per_page;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('contacts_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}


function get_groups_modal(start_row, per_page) {
    var phpurl = "get_groups_modal.php?start_row=" + start_row + "&per_page=" + per_page;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('groups_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}



function get_templates_modal(start_row, per_page) {
    var phpurl = "get_templates_modal.php?start_row=" + start_row + "&per_page=" + per_page;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('templates_modal_content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();

}

function parse_recipient() {
    var recipient = document.getElementById('recipient').value.replace(" ", "");
    if (recipient.length >= 10 && recipient.length <= 15) {
        var recipient_list = document.getElementById('recipient_list').innerHTML;
        var contacts = document.getElementById('contacts').value;
        contacts = contacts + recipient + ",";

        var phpurl = "get_contact_names.php?items=" + recipient + ",";
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var recipient_list = xmlhttp.responseText + document.getElementById('recipient_list').innerHTML;
                document.getElementById('recipient_list').innerHTML = recipient_list;
                document.getElementById('recipient').value = '';
            }
        }
        xmlhttp.open("GET", phpurl, false);
        xmlhttp.send();


        document.getElementById('contacts').value = contacts;
        get_total_recipients();
    }
}

function get_total_recipients() {
    var total_contacts = (document.getElementById('contacts').value.split(",").length) - 1;

    var total_group_contacts = 0;

    var groups = document.getElementById('groups').value;

    var phpurl = "get_total_group_contacts.php?groups=" + groups;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            total_group_contacts = + xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();


    var total_recipients = total_contacts + total_group_contacts;
    document.getElementById('total_recipients').innerHTML = total_recipients;
}

function get_history(start_row, per_page) {
    var from_date = document.getElementById('from_date').value;
    var to_date = document.getElementById('to_date').value;
    var keyword = document.getElementById('keyword').value;
    var client_id = document.getElementById('client_id').value;
    var sms_status = document.getElementById('sms_status').value;

    document.getElementById('page-content').innerHTML = "";

    var phpurl = "get_history.php?start_row=" + start_row + "&per_page=" + per_page + "&from_date=" + from_date + "&to_date=" + to_date + "&keyword=" + keyword+ "&client_id=" + client_id+ "&sms_status=" + sms_status;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}

function get_contacts(start_row, per_page) {

    var group_id = document.getElementById('group_id').value;
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_contacts.php?start_row=" + start_row + "&per_page=" + per_page + "&group_id=" + group_id + "&keyword=" + keyword;

    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function get_templates(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_templates.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function get_senders(start_row, per_page) {
    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_senders.php?start_row=" + start_row + "&per_page=" + per_page + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


function get_scheduled(start_row, per_page) {
    var from_date = document.getElementById('from_date').value;
    var to_date = document.getElementById('to_date').value;

    var keyword = document.getElementById('keyword').value;

    var phpurl = "get_scheduled.php?start_row=" + start_row + "&per_page=" + per_page + "&from_date=" + from_date + "&to_date=" + to_date + "&keyword=" + keyword;
    var xmlhttp;
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById('page-content').innerHTML = xmlhttp.responseText;
            document.getElementById('per_page').value = per_page;
        }
    }
    xmlhttp.open("GET", phpurl, false);
    xmlhttp.send();
}


// Restricts input for the given textbox to the given inputFilter.
function setInputFilter(textbox, inputFilter) {
    ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
        textbox.addEventListener(event, function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    });
}
