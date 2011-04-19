<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
</head>
<body>
    <form id="form1" runat="server">
    <div>
        <asp:TextBox ID="Text1" runat="server" TextMode="MultiLine"></asp:TextBox>
        <asp:Button ID="tbtn" runat="server" Text="Tweet" onclick="tbtn_Click" />
    </div>
    <div>
        <asp:Button ID="ConnectSinaBtn" runat="server" Text="连接Sina" 
            onclick="ConnectSinaBtn_Click" />
        <asp:Button ID="ConnectQQBtn" runat="server" Text="连接QQ" 
            onclick="ConnectQQBtn_Click" />
    </div>
    </form>
</body>
</html>
