var vSubMenu = new Array();
var vSubIcon = new Array();
var vSubLink = new Array();
var vSubPopup = new Array();
var vTabCurSel = -1;


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

function wm(v) {
    for (var s in v) {
        document.write("<a>" + s + "</a>");
    }
}