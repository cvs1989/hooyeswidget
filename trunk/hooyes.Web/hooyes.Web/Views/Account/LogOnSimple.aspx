<%@ Page Language="C#" Inherits="System.Web.Mvc.ViewPage<hooyes.Web.Models.LogOnModel>" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>LogOnSimple</title>
    <link href="../../Content/Site.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
    .simpleLogon{ background-color:#fff; margin-top:-15px; padding:0px;}
    form{ margin:0px; padding:0px;}
    </style>
</head>
<body>
    <div class="simpleLogon">
        <h2>Welcome!</h2>
    <p>
        Please enter your username and password. <%: Html.ActionLink("Register", "Register") %> if you don't have an account.
    </p>

    <% using (Html.BeginForm()) { %>
        <%: Html.ValidationSummary(true, "Login was unsuccessful. Please correct the errors and try again.") %>
        <div>
            <fieldset>
                <legend>Logon</legend>
                
                <div class="editor-label">
                    <%: Html.LabelFor(m => m.UserName) %>
                </div>
                <div class="editor-field">
                    <%: Html.TextBoxFor(m => m.UserName) %>
                    <%: Html.ValidationMessageFor(m => m.UserName) %>
                </div>
                
                <div class="editor-label">
                    <%: Html.LabelFor(m => m.Password) %>
                </div>
                <div class="editor-field">
                    <%: Html.PasswordFor(m => m.Password) %>
                    <%: Html.ValidationMessageFor(m => m.Password) %>
                </div>
                
                <div class="editor-label">
                    <%: Html.CheckBoxFor(m => m.RememberMe) %>
                    <%: Html.LabelFor(m => m.RememberMe) %>
                </div>
                
                <p>
                    <input type="submit" value="Log On" />
                </p>
            </fieldset>
        </div>
    <% } %>
    </div>
</body>
</html>
