/// <reference path="../../Scripts/jquery-1.4.1-vsdoc.js" />
var vSubMenu = new Array();
var vSubIcon = new Array();
var vSubLink = new Array();
var vSubPopup = new Array();
var vTabCurSel = -1;
var gTag = 5;
var m = Request(location.href)["m"];
if (m != null && m != "") {
    gTag = m;
}

vSubMenu[0] = ['Sales Report', 'Bank Card Report', 'Cancellation Report', 'CC Trans Log', 'Plan Renew/Change', 'Reward Report', 'inDirect Sales Report', 'Hanyastar Report manage', 'Survey', 'Market Refund', 'AU Post Pay', 'Promotion Code', 'Ticket Report', 'NXX Request', 'Sales Rep Manage', 'TW DID Report', 'Profitablity & Usage Report', 'Ads Manage', 'Save Option Manage', 'Cancel Rate'];
vSubMenu[1] = ['US BB', 'US HK', 'US-TW', 'US-DM', 'AU BB', 'AU Star BB', 'AU DSL', 'SG BB', 'iTalkLite CA', 'iTalkLite US', 'CA BB', 'Tai Seng', 'AU Plan Dealer Support Only', 'Pop Out Window', 'CA HK', 'Delivery', 'US-KR BB', 'US-KR JA BB', 'CA-KR BB', 'CA-KR JA BB', 'US IFD per minute', 'CA IFD', 'INT BB', 'US Hanya Signup', 'CA Hanya Signup', 'PA-IN', 'KR DAILY', 'Sales Inbound Register', 'SalesInboundReport', 'Call Report', 'Pre Signup Report', 'Customer Request Info'];
vSubMenu[2] = ['Dealer Search', 'Manage Commission', 'Commission Request', 'Inventory Order', 'Customer Search', 'Dealer Approve', 'Dealer Donate', 'Customer Move Log', 'Dealer Report', 'Dealer Pre Signup Report', 'CHEQUE LIST', 'Top Up History Query', 'BB Credit Sign Up Payment Report', 'Transaction Report', 'Product Plan', 'Dealer Sales Cost', 'call quality feedback', 'Churn Rate Report', 'Adv Dealer Search', 'Box Installation Order', 'Check Repeat Register', 'Demo Plan Report'];
vSubMenu[3] = ['Call Rep', 'Ticket Batch Job', 'Trouble Tickets', 'Call Notes', 'Shipping/Handling', 'Customer Mail', 'PORT IN CHECK', 'Repeat Cancellation Report', 'Bible Adm', 'System Account', '新加坡GIRO管理员', 'Hanya CRM', 'Customer Report', 'CreditCard Expiration Report', 'Negative Balance', 'DemoAccount Manage', 'Outbound Commission', 'SG Customer Manage', 'Satisfaction Report'];
vSubMenu[4] = ['Search/Upload Requests', 'CA CSV update Requests', 'US CSV update Requests', 'Export Requests', 'Monthly export Requests', 'Weekly Report', 'AU Change Status', 'ExportELoa'];
vSubMenu[5] = ['Visit Back Rep', 'Visit Back Admin', 'Cancel Rep', 'Cancel Report', 'Cancel Refund Report', 'Cancel Rep Manage'];
vSubMenu[6] = ['911 Report', 'Web Stats', 'Audit Report', 'Technical Service Bulletin', 'Inventory', 'iTalkDID'];
vSubMenu[7] = ['Category Edit', 'Noc Tickets', 'Noc Ticket Report', 'System Issue', 'Routing Change', 'Traffic Report', 'WIKI', 'Web Monitor', 'Account Manual Block', 'Server Monitor'];
vSubMenu[8] = ['BBS'];
vSubMenu[9] = ['Tools'];
vSubMenu[10] = ['新建员工', '查询员工', 'Rep Manage', 'Rep Team', 'Role Manage', 'Assignto List', 'Menu Edit', 'Assignto Center', 'Assignto Team', 'Assignto Role'];
//http://italklite.com/LT/chtu/img/logo.gif
//http://crm.italkcs.com/images/company_logo.gif
function rnd() {
    var i = Math.random() * 100;
    return i;
}
//document.title = rnd();
function wm(v, el) {
    var fl = rnd();
    var f = 0;
    f++;
    var st = '<table style="border:0;" ><tr><td>';
    if (fl >= 50) {
        st += '<div style="height:40px"><img src="http://crm.italkcs.com/images/company_logo.gif" alt="patapage" width="150"></div>';
    } else {
        st += '<div style="height:60px"><img src="http://italklite.com/LT/chtu/img/logo.gif" alt="patapage" width="150"></div>';
    }
    
    for (var i = 0; i < v.length; i++) {
        if (f == gTag) {
            st += "</td><td>";
//            if (fl >= 50) {
//                st += '<div style="height:40px"><img src="http://crm.italkcs.com/images/company_logo.gif" alt="patapage" width="150"></div>';
//            } else {
//                st += '<div style="height:60px"><img src="http://italklite.com/LT/chtu/img/logo.gif" alt="patapage" width="150"></div>';
//            }
            f = 0;
        }
        st += '<a>' + v[i] + '</a>';
        f++;
        
    }
    st += '</td></tr></table>';

    $("#" + el).append(st);
}

function Request(url) {
    var query = url.replace(/^[^\?]+\??/, '');
    var Params = {};
    if (!query) { return Params; } // return empty object
    var Pairs = query.split(/[;&]/);
    for (var i = 0; i < Pairs.length; i++) {
        var KeyVal = Pairs[i].split('=');
        if (!KeyVal || KeyVal.length != 2) { continue; }
        var key = unescape(KeyVal[0]);
        var val = unescape(KeyVal[1]);
        val = val.replace(/\+/g, ' ');
        Params[key] = val;
    }
    return Params;
}
